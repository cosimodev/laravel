<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Lato employee: consultazione workshop e gestione iscrizioni.
 *
 * L'employee vede solo i workshop futuri, può iscriversi (con gestione
 * automatica della waiting list) e cancellare la propria registrazione.
 */
class WorkshopController extends Controller
{
    /**
     * Workshop futuri con stato di iscrizione dell'utente corrente.
     *
     * Carichiamo le registrazioni filtrate per l'utente loggato
     * così il frontend sa subito se è già iscritto a ciascun workshop.
     */
    public function index(Request $request): Response
    {
        $workshops = Workshop::withCount(['registrations as confirmed_count' => function ($q) {
            $q->where('status', 'confirmed');
        }, 'registrations as waiting_count' => function ($q) {
            $q->where('status', 'waiting');
        }])->with(['registrations' => function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        }])->where('date_time', '>', now())->latest('date_time')->paginate(10);

        return Inertia::render('Employee/Workshops/Index', [
            'workshops' => $workshops,
        ]);
    }

    /** Dettaglio workshop con la registrazione dell'utente (se presente). */
    public function show(Workshop $workshop, Request $request): Response
    {
        $workshop->loadCount(['registrations as confirmed_count' => function ($q) {
            $q->where('status', 'confirmed');
        }, 'registrations as waiting_count' => function ($q) {
            $q->where('status', 'waiting');
        }]);

        $myRegistration = Registration::where('user_id', $request->user()->id)
            ->where('workshop_id', $workshop->id)
            ->first();

        return Inertia::render('Employee/Workshops/Show', [
            'workshop' => $workshop,
            'myRegistration' => $myRegistration,
        ]);
    }

    /**
     * Iscrizione a un workshop con logica confirmed/waiting.
     *
     * Prima di iscrivere controlliamo:
     * 1. Che l'utente non sia già iscritto (doppia iscrizione bloccata)
     * 2. Che non ci sia un altro workshop in sovrapposizione oraria
     *
     * Poi dentro una transazione con lockForUpdate (per evitare race condition
     * in caso di iscrizioni concorrenti) decidiamo se confermare direttamente
     * o mettere in coda. Il lock è fondamentale: senza, due richieste
     * simultanee potrebbero entrambe vedere "c'è posto" e sforare la capienza.
     */
    public function register(Request $request, Workshop $workshop): RedirectResponse
    {
        $user = $request->user();

        // Controllo doppia iscrizione
        $existing = Registration::where('user_id', $user->id)
            ->where('workshop_id', $workshop->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Sei già registrato a questo workshop.');
        }

        // Controllo sovrapposizione oraria con altri workshop a cui è iscritto.
        // Un overlap si verifica quando: inizio_A < fine_B AND inizio_B < fine_A.
        // Usiamo datetime() di SQLite per calcolare l'orario di fine dinamicamente.
        $newStart = $workshop->date_time;
        $newEnd = $workshop->endTime();

        $overlap = Registration::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'waiting'])
            ->whereHas('workshop', function ($q) use ($newStart, $newEnd) {
                $q->where('date_time', '<', $newEnd)
                  ->whereRaw("datetime(date_time, '+' || duration_minutes || ' minutes') > ?", [$newStart]);
            })->exists();

        if ($overlap) {
            return back()->with('error', 'Hai già un workshop in sovrapposizione oraria.');
        }

        // Transazione con lock pessimistico per gestire iscrizioni concorrenti
        DB::transaction(function () use ($workshop, $user) {
            $workshop = Workshop::lockForUpdate()->find($workshop->id);

            if ($workshop->confirmedCount() < $workshop->capacity) {
                // C'è ancora posto → confermato subito
                Registration::create([
                    'user_id' => $user->id,
                    'workshop_id' => $workshop->id,
                    'status' => 'confirmed',
                ]);
            } else {
                // Workshop pieno → entra in coda (FIFO)
                $lastPosition = Registration::where('workshop_id', $workshop->id)
                    ->where('status', 'waiting')
                    ->max('position') ?? 0;

                Registration::create([
                    'user_id' => $user->id,
                    'workshop_id' => $workshop->id,
                    'status' => 'waiting',
                    'position' => $lastPosition + 1,
                ]);
            }
        });

        return back()->with('success', 'Registrazione effettuata con successo.');
    }

    /**
     * Cancella la propria iscrizione.
     *
     * Se era confermata, promuove il primo in waiting list (FIFO).
     * Se era in attesa, riordina le posizioni per riempire il "buco".
     */
    public function cancelRegistration(Registration $registration, Request $request): RedirectResponse
    {
        // Verifica che l'utente stia cancellando la propria iscrizione
        if ($registration->user_id !== $request->user()->id) {
            abort(403);
        }

        $workshop = $registration->workshop;
        $wasConfirmed = $registration->status === 'confirmed';

        $registration->delete();

        if ($wasConfirmed) {
            // Si è liberato un posto → promuovi il primo dalla waiting list
            $workshop->promoteFromWaitingList();
        } else {
            // Era in attesa → ricalcola le posizioni in coda
            $workshop->reorderWaitingList();
        }

        return back()->with('success', 'Registrazione cancellata.');
    }
}
