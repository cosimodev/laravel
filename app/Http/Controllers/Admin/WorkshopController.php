<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkshopRequest;
use App\Models\Workshop;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class WorkshopController extends Controller
{
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

    public function create(): Response
    {
        return Inertia::render('Admin/Workshops/Form');
    }

    public function store(WorkshopRequest $request): RedirectResponse
    {
        Workshop::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop creato con successo.');
    }

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

    public function destroy(Workshop $workshop): RedirectResponse
    {
        $workshop->delete();

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop eliminato con successo.');
    }
}
