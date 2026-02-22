<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Lead;
use App\Models\Company;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::with(['user', 'lead', 'company']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('client_rut', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quotations = $query->latest()->paginate(15)->withQueryString();

        return Inertia::render('Quotations/Index', [
            'quotations' => $quotations,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function create(Request $request)
    {
        $lead = null;
        $company = null;

        if ($request->filled('lead_id')) {
            $lead = Lead::with('company')->find($request->lead_id);
        }

        if ($request->filled('company_id')) {
            $company = Company::find($request->company_id);
        }

        $companies = Company::orderBy('business_name')->get();
        $leads = Lead::with('company')->orderBy('name')->get();

        return Inertia::render('Quotations/Create', [
            'lead' => $lead,
            'company' => $company,
            'companies' => $companies,
            'leads' => $leads,
            'nextQuotationNumber' => Quotation::generateQuotationNumber(),
            'defaultTaxRate' => Setting::get('tax_rate', 19),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quotation_number' => 'required|string|unique:quotations,quotation_number',
            'lead_id' => 'nullable|exists:leads,id',
            'company_id' => 'nullable|exists:companies,id',
            'client_name' => 'required|string|max:255',
            'client_rut' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:500',
            'issue_date' => 'required|date',
            'valid_until' => 'required|date|after:issue_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        $quotation = new Quotation($validated);
        $quotation->calculateTotals();
        $quotation->save();

        // Si hay un lead asociado, actualizar su quotation_id
        if ($validated['lead_id']) {
            Lead::where('id', $validated['lead_id'])->update(['quotation_id' => $quotation->id]);
        }

        return redirect()->route('quotations.index');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['user', 'lead', 'company']);

        $companySettings = [
            'name' => Setting::get('company_name'),
            'rut' => Setting::get('company_rut'),
            'giro' => Setting::get('company_giro'),
            'address' => Setting::get('company_address'),
            'email' => Setting::get('company_email'),
            'phone' => Setting::get('company_phone'),
            'logo' => Setting::get('company_logo') ? asset('storage/' . Setting::get('company_logo')) : null,
        ];

        return Inertia::render('Quotations/Show', [
            'quotation' => $quotation,
            'companySettings' => $companySettings,
        ]);
    }

    public function edit(Quotation $quotation)
    {
        $quotation->load(['lead', 'company']);
        $companies = Company::orderBy('business_name')->get();
        $leads = Lead::with('company')->orderBy('name')->get();

        return Inertia::render('Quotations/Edit', [
            'quotation' => $quotation,
            'companies' => $companies,
            'leads' => $leads,
            'defaultTaxRate' => Setting::get('tax_rate', 19),
        ]);
    }

    public function update(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'quotation_number' => 'required|string|unique:quotations,quotation_number,' . $quotation->id,
            'lead_id' => 'nullable|exists:leads,id',
            'company_id' => 'nullable|exists:companies,id',
            'client_name' => 'required|string|max:255',
            'client_rut' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:500',
            'issue_date' => 'required|date',
            'valid_until' => 'required|date|after:issue_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'status' => 'nullable|in:draft,sent,accepted,rejected,expired',
        ]);

        $quotation->fill($validated);
        $quotation->calculateTotals();
        $quotation->save();

        return redirect()->route('quotations.index');
    }

    public function destroy(Quotation $quotation)
    {
        // Remover la referencia en leads si existe
        Lead::where('quotation_id', $quotation->id)->update(['quotation_id' => null]);
        
        $quotation->delete();

        return redirect()->route('quotations.index');
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
        ]);

        $quotation->update(['status' => $validated['status']]);

        return back();
    }

    public function pdf(Quotation $quotation)
    {
        $quotation->load(['user', 'lead', 'company']);

        $companySettings = [
            'name' => Setting::get('company_name'),
            'rut' => Setting::get('company_rut'),
            'giro' => Setting::get('company_giro'),
            'address' => Setting::get('company_address'),
            'email' => Setting::get('company_email'),
            'phone' => Setting::get('company_phone'),
            'logo' => Setting::get('company_logo'),
        ];

        $pdf = Pdf::loadView('quotations.pdf', [
            'quotation' => $quotation,
            'companySettings' => $companySettings,
        ]);

        $pdf->setPaper('letter', 'portrait');

        return $pdf->download('cotizacion-' . $quotation->quotation_number . '.pdf');
    }
}
