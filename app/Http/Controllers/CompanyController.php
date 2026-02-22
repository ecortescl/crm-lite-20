<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query()->withCount('leads');

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('fantasy_name', 'like', "%{$search}%")
                  ->orWhere('rut', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por región
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // Filtro por tamaño
        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        $companies = $query->latest()->paginate(15)->withQueryString();

        return Inertia::render('Companies/Index', [
            'companies' => $companies,
            'filters' => $request->only(['search', 'region', 'size']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'rut' => 'required|string|max:20|unique:companies,rut',
            'fantasy_name' => 'nullable|string|max:255',
            'giro' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'size' => 'nullable|in:micro,small,medium,large',
            'industry' => 'nullable|string|max:100',
        ]);

        // Limpiar el RUT antes de guardar
        if (isset($validated['rut'])) {
            $validated['rut'] = preg_replace('/[^0-9kK]/', '', $validated['rut']);
        }

        Company::create($validated);

        return redirect()->route('companies.index');
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'rut' => 'required|string|max:20|unique:companies,rut,' . $company->id,
            'fantasy_name' => 'nullable|string|max:255',
            'giro' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'size' => 'nullable|in:micro,small,medium,large',
            'industry' => 'nullable|string|max:100',
        ]);

        // Limpiar el RUT antes de guardar
        if (isset($validated['rut'])) {
            $validated['rut'] = preg_replace('/[^0-9kK]/', '', $validated['rut']);
        }

        $company->update($validated);

        return redirect()->route('companies.index');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index');
    }
}
