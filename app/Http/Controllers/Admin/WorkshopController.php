<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkshopRequest;
use App\Models\Workshop;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Gestione CRUD dei workshop — accessibile solo agli admin.
 *
 * L'admin può creare, modificare ed eliminare workshop, oltre a
 * visualizzare l'elenco con i conteggi delle iscrizioni e il
 * dettaglio con la lista completa dei partecipanti.
 */
class WorkshopController extends Controller
{
    /**
     * Lista paginata di tutti i workshop, ordinati per data (più recenti prima).
     * Per ogni workshop carichiamo i conteggi di confermati e in attesa
     * così da mostrarli direttamente nella tabella senza query extra.
     */
    public function index(): Response
    {
        $workshops = Workshop::withCount(['registrations as confirmed_count' => function ($q) {
            $q->where('status', 'confirmed');
        }, 'registrations as waiting_count' => function ($q) {
            $q->where('status', 'waiting');
        }])->latest('date_time')->paginate(10);

        return Inertia::render('Admin/Workshops/Index', [
            'workshops' => $workshops,
        ]);
    }

    /** Form di creazione — il componente Vue è lo stesso usato per l'edit. */
    public function create(): Response
    {
        return Inertia::render('Admin/Workshops/Form');
    }

    /**
     * Salva un nuovo workshop. La validazione è delegata al WorkshopRequest,
     * l'admin corrente viene associato come creatore.
     */
    public function store(WorkshopRequest $request): RedirectResponse
    {
        Workshop::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop creato con successo.');
    }

    /**
     * Dettaglio di un singolo workshop con elenco completo dei partecipanti.
     * Carichiamo eager le registrazioni con gli utenti associati per
     * evitare N+1 query nella vista.
     */
    public function show(Workshop $workshop): Response
    {
        $workshop->load([
            'creator',
            'registrations.user',
        ]);
        $workshop->loadCount(['registrations as confirmed_count' => function ($q) {
            $q->where('status', 'confirmed');
        }, 'registrations as waiting_count' => function ($q) {
            $q->where('status', 'waiting');
        }]);

        return Inertia::render('Admin/Workshops/Show', [
            'workshop' => $workshop,
        ]);
    }

    /** Form di modifica — riusa lo stesso componente Vue del create. */
    public function edit(Workshop $workshop): Response
    {
        return Inertia::render('Admin/Workshops/Form', [
            'workshop' => $workshop,
        ]);
    }

    public function update(WorkshopRequest $request, Workshop $workshop): RedirectResponse
    {
        $workshop->update($request->validated());

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop aggiornato con successo.');
    }

    /**
     * Elimina il workshop e, grazie al cascadeOnDelete nella migration,
     * vengono rimosse automaticamente anche tutte le iscrizioni associate.
     */
    public function destroy(Workshop $workshop): RedirectResponse
    {
        $workshop->delete();

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop eliminato con successo.');
    }
}
