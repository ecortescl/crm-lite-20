<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['status', 'assignedUser', 'company']);

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

        // Filtro por usuario asignado
        if ($request->filled('assigned')) {
            $query->where('assigned_to', $request->assigned);
        }

        // Filtro por origen
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $leads = $query->latest()->paginate(15)->withQueryString();

        $statuses = LeadStatus::orderBy('order')->get();
        $users = User::select('id', 'name')->get();
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
        // Filtros de fecha - por defecto últimos 30 días
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        // Página actual para cada estado (formato: status_1_page, status_2_page, etc.)
        $perPage = 5;

        $statuses = LeadStatus::orderBy('order')->get()->map(function ($status) use ($request, $startDate, $endDate, $perPage) {
            $pageParam = "status_{$status->id}_page";
            $currentPage = $request->input($pageParam, 1);
            
            $leadsQuery = $status->leads()
                ->with(['assignedUser', 'company'])
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->latest();
            
            $leads = $leadsQuery->paginate($perPage, ['*'], $pageParam, $currentPage);
            
            return [
                'id' => $status->id,
                'name' => $status->name,
                'color' => $status->color,
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
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'contact_company' => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'notes' => 'nullable|string',
            'lead_status_id' => 'required|exists:lead_statuses,id',
            'assigned_to' => 'nullable|exists:users,id',
            'source' => 'nullable|string|max:255',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
            'meeting_notes' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'quote_items' => 'nullable',
            'invoice_number' => 'nullable|string|max:255',
            'final_amount' => 'nullable|numeric|min:0',
            'closed_at' => 'nullable|date',
            'payment_status' => 'nullable|in:pending,partial,paid',
        ]);

        Lead::create($validated);

        return redirect()->back()->with('success', 'Lead creado exitosamente');
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'contact_company' => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'notes' => 'nullable|string',
            'lead_status_id' => 'sometimes|required|exists:lead_statuses,id',
            'assigned_to' => 'nullable|exists:users,id',
            'source' => 'nullable|string|max:255',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
            'meeting_notes' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'quote_items' => 'nullable',
            'invoice_number' => 'nullable|string|max:255',
            'final_amount' => 'nullable|numeric|min:0',
            'closed_at' => 'nullable|date',
            'payment_status' => 'nullable|in:pending,partial,paid',
        ]);

        $lead->update($validated);

        return redirect()->back()->with('success', 'Lead actualizado exitosamente');
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'lead_status_id' => 'required|exists:lead_statuses,id',
        ]);

        $lead->update($validated);

        return redirect()->back();
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->back()->with('success', 'Lead eliminado exitosamente');
    }
}
