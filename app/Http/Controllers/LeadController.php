<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\User;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user?->isJefatura() ?? false;
        $query = Lead::with(['status', 'assignedUser', 'company']);

        if (! $isAdmin && $user) {
            $query->where('assigned_to', $user->id);
        }

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('contact_company', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('lead_status_id', $request->status);
        }

        // Filtro por usuario asignado (solo admin)
        if ($isAdmin && $request->filled('assigned')) {
            $query->where('assigned_to', $request->assigned);
        }

        // Filtro por origen
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $leads = $query->latest()->paginate(15)->withQueryString();

        $statuses = LeadStatus::orderBy('order')->get();
        $users = $isAdmin
            ? User::select('id', 'name')->get()
            : User::select('id', 'name')->where('id', $user?->id)->get();
        $companies = \App\Models\Company::select('id', 'business_name', 'fantasy_name', 'rut')
            ->orderBy('business_name')
            ->get();

        return Inertia::render('Leads/Index', [
            'leads' => $leads,
            'statuses' => $statuses,
            'users' => $users,
            'companies' => $companies,
            'filters' => $request->only(['search', 'status', 'assigned', 'source']),
        ]);
    }

    public function kanban(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user?->isJefatura() ?? false;

        // Filtros de fecha - por defecto últimos 30 días
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        // Página actual para cada estado (formato: status_1_page, status_2_page, etc.)
        $perPage = 5;

        $statuses = LeadStatus::orderBy('order')->get()->map(function ($status) use ($request, $startDate, $endDate, $perPage, $isAdmin, $user) {
            $pageParam = "status_{$status->id}_page";
            $currentPage = $request->input($pageParam, 1);
            
            $leadsQuery = $status->leads()
                ->with(['assignedUser', 'company', 'quotation'])
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->latest();

            if (! $isAdmin && $user) {
                $leadsQuery->where('assigned_to', $user->id);
            }
            
            $leads = $leadsQuery->paginate($perPage, ['*'], $pageParam, $currentPage);
            
            return [
                'id' => $status->id,
                'name' => $status->name,
                'color' => $status->color,
                'icon' => $status->icon,
                'order' => $status->order,
                'leads' => $leads->items(),
                'pagination' => [
                    'current_page' => $leads->currentPage(),
                    'last_page' => $leads->lastPage(),
                    'total' => $leads->total(),
                    'per_page' => $leads->perPage(),
                ],
            ];
        });

        return Inertia::render('Leads/Kanban', [
            'statuses' => $statuses,
            'quotations' => Quotation::select('id', 'quotation_number', 'client_name', 'total', 'status', 'lead_id')
                ->when(! $isAdmin && $user, function ($query) use ($user) {
                    $query->where(function ($q) use ($user) {
                        $q->whereNull('lead_id')
                            ->orWhereHas('lead', function ($leadQuery) use ($user) {
                                $leadQuery->where('assigned_to', $user->id);
                            });
                    });
                })
                ->latest()
                ->limit(200)
                ->get(),
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $tenantId = $request->user()?->tenant_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'contact_company' => 'nullable|string|max:255',
            'company_id' => ['nullable', Rule::exists('companies', 'id')->where('tenant_id', $tenantId)],
            'notes' => 'nullable|string',
            'lead_status_id' => ['required', Rule::exists('lead_statuses', 'id')->where('tenant_id', $tenantId)],
            'assigned_to' => ['nullable', Rule::exists('users', 'id')->where('tenant_id', $tenantId)],
            'source' => 'nullable|string|max:255',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
            'meeting_notes' => 'nullable|string',
            'meeting_link' => 'nullable|string|max:2048',
            'budget' => 'nullable|numeric|min:0',
            'quote_items' => 'nullable',
            'invoice_number' => 'nullable|string|max:255',
            'final_amount' => 'nullable|numeric|min:0',
            'closed_at' => 'nullable|date',
            'payment_status' => 'nullable|in:pending,partial,paid',
        ]);

        if (!empty($validated['scheduled_at'])) {
            $validated['scheduled_by'] = auth()->id();
        }

        Lead::create($validated);

        return redirect()->back()->with('success', 'Lead creado exitosamente');
    }

    public function update(Request $request, Lead $lead)
    {
        $tenantId = $request->user()?->tenant_id;
        $user = $request->user();
        if (! $user?->isJefatura() && $lead->assigned_to !== $user?->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'contact_company' => 'nullable|string|max:255',
            'company_id' => ['nullable', Rule::exists('companies', 'id')->where('tenant_id', $tenantId)],
            'notes' => 'nullable|string',
            'lead_status_id' => ['sometimes', 'required', Rule::exists('lead_statuses', 'id')->where('tenant_id', $tenantId)],
            'assigned_to' => ['nullable', Rule::exists('users', 'id')->where('tenant_id', $tenantId)],
            'source' => 'nullable|string|max:255',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
            'meeting_notes' => 'nullable|string',
            'meeting_link' => 'nullable|string|max:2048',
            'budget' => 'nullable|numeric|min:0',
            'quote_items' => 'nullable',
            'quotation_id' => ['nullable', Rule::exists('quotations', 'id')->where('tenant_id', $tenantId)],
            'invoice_number' => 'nullable|string|max:255',
            'final_amount' => 'nullable|numeric|min:0',
            'closed_at' => 'nullable|date',
            'payment_status' => 'nullable|in:pending,partial,paid',
        ]);

        if (array_key_exists('scheduled_at', $validated)) {
            if (!empty($validated['scheduled_at'])) {
                $validated['scheduled_by'] = auth()->id();
            } else {
                $validated['scheduled_by'] = null;
            }
        }

        $previousQuotationId = $lead->quotation_id;
        $lead->update($validated);

        if (array_key_exists('quotation_id', $validated)) {
            $newQuotationId = $validated['quotation_id'];

            if ($previousQuotationId !== $newQuotationId) {
                if ($previousQuotationId) {
                    Quotation::where('id', $previousQuotationId)->update(['lead_id' => null]);
                }

                if ($newQuotationId) {
                    $existingLeadId = Quotation::where('id', $newQuotationId)->value('lead_id');
                    if ($existingLeadId && $existingLeadId !== $lead->id) {
                        Lead::where('id', $existingLeadId)->update(['quotation_id' => null]);
                    }

                    Quotation::where('id', $newQuotationId)->update(['lead_id' => $lead->id]);
                }
            }
        }

        return redirect()->back()->with('success', 'Lead actualizado exitosamente');
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $tenantId = $request->user()?->tenant_id;
        $user = $request->user();
        if (! $user?->isJefatura() && $lead->assigned_to !== $user?->id) {
            abort(403);
        }

        $validated = $request->validate([
            'lead_status_id' => ['required', Rule::exists('lead_statuses', 'id')->where('tenant_id', $tenantId)],
        ]);

        $lead->update($validated);

        return redirect()->back();
    }

    public function destroy(Lead $lead)
    {
        $user = auth()->user();
        if (! $user?->isJefatura() && $lead->assigned_to !== $user?->id) {
            abort(403);
        }

        $lead->delete();

        return redirect()->back()->with('success', 'Lead eliminado exitosamente');
    }
}
