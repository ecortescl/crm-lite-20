<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PlatformDashboardController extends Controller
{
    public function index(): Response
    {
        $totalTenants = Tenant::query()->count();
        $totalUsers = User::query()->count();
        $totalLeads = Lead::withoutGlobalScope('tenant')->count();
        $totalQuotations = Quotation::withoutGlobalScope('tenant')->count();
        $totalCompanies = Company::withoutGlobalScope('tenant')->count();
        $totalMeetings = Lead::withoutGlobalScope('tenant')->whereNotNull('scheduled_at')->count();
        $totalApiTokens = DB::table('personal_access_tokens')
            ->join('users', 'personal_access_tokens.tokenable_id', '=', 'users.id')
            ->where('personal_access_tokens.tokenable_type', User::class)
            ->count();

        $since = now()->subDays(30);

        $tenantStats = Tenant::query()
            ->withCount([
                'users',
                'leads',
                'quotations',
                'companies',
                'leads as meetings_count' => fn ($query) => $query->whereNotNull('scheduled_at'),
            ])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Tenant $tenant) => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
                'users_count' => $tenant->users_count,
                'leads_count' => $tenant->leads_count,
                'quotations_count' => $tenant->quotations_count,
                'companies_count' => $tenant->companies_count,
                'meetings_count' => $tenant->meetings_count,
                'api_tokens_count' => DB::table('personal_access_tokens')
                    ->join('users', 'personal_access_tokens.tokenable_id', '=', 'users.id')
                    ->where('personal_access_tokens.tokenable_type', User::class)
                    ->where('users.tenant_id', $tenant->id)
                    ->count(),
                'leads_last_30_days' => Lead::withoutGlobalScope('tenant')
                    ->where('tenant_id', $tenant->id)
                    ->where('created_at', '>=', $since)
                    ->count(),
                'quotations_last_30_days' => Quotation::withoutGlobalScope('tenant')
                    ->where('tenant_id', $tenant->id)
                    ->where('created_at', '>=', $since)
                    ->count(),
                'created_at' => $tenant->created_at?->toDateTimeString(),
            ]);

        $topTenantByLeads = $tenantStats->sortByDesc('leads_count')->first();
        $topTenantByQuotations = $tenantStats->sortByDesc('quotations_count')->first();

        return Inertia::render('Admin/PlatformDashboard', [
            'globalStats' => [
                'total_tenants' => $totalTenants,
                'total_users' => $totalUsers,
                'total_leads' => $totalLeads,
                'total_quotations' => $totalQuotations,
                'total_companies' => $totalCompanies,
                'total_meetings' => $totalMeetings,
                'total_api_tokens' => $totalApiTokens,
            ],
            'platformInsights' => [
                'top_tenant_by_leads' => $topTenantByLeads
                    ? ['name' => $topTenantByLeads['name'], 'value' => $topTenantByLeads['leads_count']]
                    : null,
                'top_tenant_by_quotations' => $topTenantByQuotations
                    ? ['name' => $topTenantByQuotations['name'], 'value' => $topTenantByQuotations['quotations_count']]
                    : null,
            ],
            'tenantStats' => $tenantStats,
        ]);
    }
}
