<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\Permission;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $logoPath = \App\Models\Setting::get('platform_logo', '');
        $logoUrl = $logoPath ? asset('storage/' . $logoPath) : '';
        $user = $request->user();
        $isAdmin = $user?->isJefatura() ?? false;
        $permissionNames = $user
            ? Permission::query()
                ->whereHas('roles.users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->pluck('name')
                ->unique()
                ->values()
                ->all()
            : [];

        $upcomingMeetings = [];
        if ($user) {
            $meetingStatusId = LeadStatus::whereRaw('LOWER(name) like ?', ['%reuni%'])
                ->orderBy('order')
                ->value('id');

            $meetingsQuery = Lead::with(['assignedUser', 'company', 'status'])
                ->whereNotNull('scheduled_at')
                ->where('scheduled_at', '>=', now())
                ->orderBy('scheduled_at')
                ->limit(5);

            if ($meetingStatusId) {
                $meetingsQuery->where('lead_status_id', $meetingStatusId);
            }

            if (! $isAdmin) {
                $meetingsQuery->where(function ($query) use ($user) {
                    $query->where('assigned_to', $user->id)
                        ->orWhere('scheduled_by', $user->id);
                });
            }

            $upcomingMeetings = $meetingsQuery->get();
        }

        return [
            ...parent::share($request),
            'name' => \App\Models\Setting::get('platform_name', config('app.name')),
            'auth' => [
                'user' => $user,
                'isAdmin' => $isAdmin,
                'permissions' => $permissionNames,
            ],
            'upcomingMeetings' => $upcomingMeetings,
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'platformLogo' => $logoUrl,
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'newToken' => $request->session()->get('newToken'),
            ],
        ];
    }
}
