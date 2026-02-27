<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()?->tenant_id;
        $query = Role::query()->with('permissions')->withCount('users');

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roles = $query->latest()->paginate(15)->withQueryString();
        $permissions = Permission::query()->where('tenant_id', $tenantId)->get();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
            'permissions' => $permissions,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $tenantId = $request->user()?->tenant_id;

        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('roles', 'name')->where('tenant_id', $tenantId)],
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => [Rule::exists('permissions', 'id')->where('tenant_id', $tenantId)],
        ]);

        $role = Role::create([
            'tenant_id' => $tenantId,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->attach($validated['permissions']);
        }

        return redirect()->back()->with('success', 'Rol creado exitosamente');
    }

    public function update(Request $request, Role $role)
    {
        $tenantId = $request->user()?->tenant_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('roles', 'name')->where('tenant_id', $tenantId)->ignore($role->id),
            ],
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => [Rule::exists('permissions', 'id')->where('tenant_id', $tenantId)],
        ]);

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->back()->with('success', 'Rol actualizado exitosamente');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->back()->with('success', 'Rol eliminado exitosamente');
    }
}
