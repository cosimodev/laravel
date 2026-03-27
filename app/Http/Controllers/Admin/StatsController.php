<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Dashboard con statistiche aggregate per l'admin.
 *
 * Espone sia la pagina Inertia iniziale che un endpoint JSON
 * per il polling real-time (il frontend chiama /admin/stats/live
 * ogni 5 secondi per aggiornare i dati senza ricaricare la pagina).
 */
class StatsController extends Controller
{
    /** Pagina dashboard — i dati vengono passati come props Inertia. */
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => $this->getStats(),
        ]);
    }

    /** Endpoint JSON per il polling real-time dal frontend. */
    public function live(): JsonResponse
    {
        return response()->json($this->getStats());
    }

    /**
     * Aggrega le statistiche principali:
     * - Totale workshop creati
     * - Totale iscrizioni confermate
     * - Workshop più popolare (per numero di confermati)
     * - Breakdown confermati/in attesa per ogni workshop (usato nel grafico)
     */
    private function getStats(): array
    {
        $totalWorkshops = Workshop::count();
        $totalRegistrations = Registration::where('status', 'confirmed')->count();

        $mostPopular = Workshop::withCount(['registrations as confirmed_count' => function ($q) {
            $q->where('status', 'confirmed');
        }])->orderByDesc('confirmed_count')->first();

        // Dati per il grafico a barre nel frontend
        $workshopsData = Workshop::withCount([
            'registrations as confirmed_count' => fn ($q) => $q->where('status', 'confirmed'),
            'registrations as waiting_count' => fn ($q) => $q->where('status', 'waiting'),
        ])->orderBy('date_time')->get()->map(fn ($w) => [
            'title' => $w->title,
            'confirmed' => $w->confirmed_count,
            'waiting' => $w->waiting_count,
        ]);

        return [
            'total_workshops' => $totalWorkshops,
            'total_registrations' => $totalRegistrations,
            'most_popular' => $mostPopular?->title,
            'workshops_data' => $workshopsData,
        ];
    }
}
