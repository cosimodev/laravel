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

class WorkshopController extends Controller
{
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

    public function register(Request $request, Workshop $workshop): RedirectResponse
    {
        $user = $request->user();

        $existing = Registration::where('user_id', $user->id)
            ->where('workshop_id', $workshop->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Sei già registrato a questo workshop.');
        }

        // Check time overlap
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

        DB::transaction(function () use ($workshop, $user) {
            $workshop = Workshop::lockForUpdate()->find($workshop->id);

            if ($workshop->confirmedCount() < $workshop->capacity) {
                Registration::create([
                    'user_id' => $user->id,
                    'workshop_id' => $workshop->id,
                    'status' => 'confirmed',
                ]);
            } else {
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

    public function cancelRegistration(Registration $registration, Request $request): RedirectResponse
    {
        if ($registration->user_id !== $request->user()->id) {
            abort(403);
        }

        $workshop = $registration->workshop;
        $wasConfirmed = $registration->status === 'confirmed';

        $registration->delete();

        if ($wasConfirmed) {
            $workshop->promoteFromWaitingList();
        } else {
            $workshop->reorderWaitingList();
        }

        return back()->with('success', 'Registrazione cancellata.');
    }
}
