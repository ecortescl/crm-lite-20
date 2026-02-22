<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\Quotation;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user?->isJefatura() ?? false;

        $statuses = LeadStatus::orderBy('order')->get();

        $leadBaseQuery = Lead::query();
        if (! $isAdmin && $user) {
            $leadBaseQuery->where('assigned_to', $user->id);
        }

        $leadCounts = (clone $leadBaseQuery)->selectRaw('lead_status_id, COUNT(*) as count')
            ->groupBy('lead_status_id')
            ->pluck('count', 'lead_status_id');

        $stats = $statuses->map(function ($status) use ($leadCounts) {
            return [
                'id' => $status->id,
                'name' => $status->name,
                'color' => $status->color,
                'icon' => $status->icon,
                'count' => (int) ($leadCounts[$status->id] ?? 0),
            ];
        });

        $kanbanStatuses = LeadStatus::with(['leads' => function ($query) use ($isAdmin, $user) {
            $query->with('assignedUser')
                ->when(! $isAdmin && $user, fn ($q) => $q->where('assigned_to', $user->id))
                ->latest()
                ->take(5);
        }])->orderBy('order')->get();

        $successStatusIds = $statuses
            ->filter(fn ($status) => Str::of($status->name)->lower()->contains('concretado'))
            ->pluck('id');

        $discardedStatusIds = $statuses
            ->filter(fn ($status) => Str::of($status->name)->lower()->contains('descartado'))
            ->pluck('id');

        $negotiationStatusIds = $statuses
            ->filter(fn ($status) => Str::of($status->name)->lower()->contains('negoci'))
            ->pluck('id');

        $successCount = $successStatusIds->isEmpty()
            ? 0
            : (clone $leadBaseQuery)->whereIn('lead_status_id', $successStatusIds)->count();

        $discardedCount = $discardedStatusIds->isEmpty()
            ? 0
            : (clone $leadBaseQuery)->whereIn('lead_status_id', $discardedStatusIds)->count();

        $totalLeads = (int) (clone $leadBaseQuery)->count();
        $conversionBase = $successCount + $discardedCount;
        $conversionRate = $conversionBase > 0
            ? round(($successCount / $conversionBase) * 100, 2)
            : 0;
        $negotiationCount = $negotiationStatusIds->isEmpty()
            ? 0
            : (clone $leadBaseQuery)->whereIn('lead_status_id', $negotiationStatusIds)->count();
        $negotiationRate = $totalLeads > 0
            ? round(($negotiationCount / $totalLeads) * 100, 2)
            : 0;
        $scheduledCount = (clone $leadBaseQuery)->whereNotNull('scheduled_at')->count();
        $scheduledRate = $totalLeads > 0
            ? round(($scheduledCount / $totalLeads) * 100, 2)
            : 0;

        $totalQuoted = (float) Quotation::query()
            ->when(! $isAdmin && $user, function ($query) use ($user) {
                $query->whereHas('lead', function ($leadQuery) use ($user) {
                    $leadQuery->where('assigned_to', $user->id);
                });
            })
            ->sum('total');

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats,
            'kanbanStatuses' => $kanbanStatuses,
            'kanbanChart' => $stats,
            'metrics' => [
                'totalLeads' => $totalLeads,
                'successCount' => $successCount,
                'discardedCount' => $discardedCount,
                'conversionRate' => $conversionRate,
                'totalQuoted' => $totalQuoted,
                'negotiationCount' => $negotiationCount,
                'negotiationRate' => $negotiationRate,
                'scheduledCount' => $scheduledCount,
                'scheduledRate' => $scheduledRate,
            ],
        ]);
    }
}
