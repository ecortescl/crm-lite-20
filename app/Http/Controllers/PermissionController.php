<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::query();

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $permissions = $query->latest()->paginate(15)->withQueryString();

        return Inertia::render('Permissions/Index', [
            'permissions' => $permissions,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $tenantId = $request->user()?->tenant_id;

        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('permissions', 'name')->where('tenant_id', $tenantId)],
            'description' => 'nullable|string',
        ]);

        Permission::create([
            'tenant_id' => $tenantId,
            ...$validated,
        ]);

        return redirect()->back()->with('success', 'Permiso creado exitosamente');
    }

    public function update(Request $request, Permission $permission)
    {
        $tenantId = $request->user()?->tenant_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('permissions', 'name')->where('tenant_id', $tenantId)->ignore($permission->id),
            ],
            'description' => 'nullable|string',
        ]);

        $permission->update($validated);

        return redirect()->back()->with('success', 'Permiso actualizado exitosamente');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->back()->with('success', 'Permiso eliminado exitosamente');
    }
}
