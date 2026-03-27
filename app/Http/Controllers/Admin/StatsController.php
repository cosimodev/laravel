<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class StatsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => $this->getStats(),
        ]);
    }

    public function live(): JsonResponse
    {
        return response()->json($this->getStats());
    }

    private function getStats(): array
    {
        $totalWorkshops = Workshop::count();
        $totalRegistrations = Registration::where('status', 'confirmed')->count();

        $mostPopular = Workshop::withCount(['registrations as confirmed_count' => function ($q) {
            $q->where('status', 'confirmed');
        }])->orderByDesc('confirmed_count')->first();

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
