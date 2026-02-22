<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user?->isJefatura() ?? false;

        $meetingStatusId = LeadStatus::whereRaw('LOWER(name) like ?', ['%reuni%'])
            ->orderBy('order')
            ->value('id');

        $meetingsQuery = Lead::with(['assignedUser', 'company', 'status'])
            ->whereNotNull('scheduled_at')
            ->orderBy('scheduled_at');

        if ($meetingStatusId) {
            $meetingsQuery->where('lead_status_id', $meetingStatusId);
        }

        if (! $isAdmin && $user) {
            $meetingsQuery->where(function ($query) use ($user) {
                $query->where('assigned_to', $user->id)
                    ->orWhere('scheduled_by', $user->id);
            });
        }

        $leadsQuery = Lead::select('id', 'name', 'assigned_to', 'lead_status_id', 'scheduled_at')
            ->orderBy('name');

        if (! $isAdmin && $user) {
            $leadsQuery->where(function ($query) use ($user) {
                $query->where('assigned_to', $user->id)
                    ->orWhere('scheduled_by', $user->id);
            });
        }

        return Inertia::render('Calendar/Index', [
            'meetings' => $meetingsQuery->get(),
            'leads' => $leadsQuery->get(),
            'meetingStatusId' => $meetingStatusId,
            'isAdmin' => $isAdmin,
        ]);
    }
}
