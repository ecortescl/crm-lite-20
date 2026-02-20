<?php

namespace App\Http\Controllers;

use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadStatusController extends Controller
{
    public function index(Request $request)
    {
        $query = LeadStatus::query()->withCount('leads');

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $statuses = $query->orderBy('order')->get();

        return Inertia::render('LeadStatuses/Index', [
            'statuses' => $statuses,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'order' => 'required|integer',
        ]);

        LeadStatus::create($validated);

        return redirect()->back()->with('success', 'Estado creado exitosamente');
    }

    public function update(Request $request, LeadStatus $leadStatus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'order' => 'required|integer',
        ]);

        $leadStatus->update($validated);

        return redirect()->back()->with('success', 'Estado actualizado exitosamente');
    }

    public function destroy(LeadStatus $leadStatus)
    {
        $leadStatus->delete();

        return redirect()->back()->with('success', 'Estado eliminado exitosamente');
    }
}
