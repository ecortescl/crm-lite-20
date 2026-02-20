<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadStatus;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $statuses = LeadStatus::orderBy('order')->get();
        
        $stats = $statuses->map(function ($status) {
            return [
                'id' => $status->id,
                'name' => $status->name,
                'color' => $status->color,
                'count' => Lead::where('lead_status_id', $status->id)->count(),
            ];
        });

        $kanbanStatuses = LeadStatus::with(['leads' => function ($query) {
            $query->with('assignedUser')->latest()->take(5);
        }])->orderBy('order')->get();

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats,
            'kanbanStatuses' => $kanbanStatuses,
        ]);
    }
}
