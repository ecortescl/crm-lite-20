<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()?->tenant_id;
        $query = User::query()
            ->where('tenant_id', $tenantId)
            ->with(['roles' => fn ($q) => $q->where('tenant_id', $tenantId)]);

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::query()->where('tenant_id', $tenantId)->get();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $request->only(['search', 'role']),
        ]);
    }

    public function store(Request $request)
    {
        $tenantId = $request->user()?->tenant_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'roles' => 'array',
            'roles.*' => [Rule::exists('roles', 'id')->where('tenant_id', $tenantId)],
        ]);

        $user = User::create([
            'tenant_id' => $tenantId,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (isset($validated['roles'])) {
            $user->roles()->attach($validated['roles']);
        }

        return redirect()->back()->with('success', 'Usuario creado exitosamente');
    }

    public function update(Request $request, User $user)
    {
        $tenantId = $request->user()?->tenant_id;
        abort_unless($user->tenant_id === $tenantId, 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'roles' => 'array',
            'roles.*' => [Rule::exists('roles', 'id')->where('tenant_id', $tenantId)],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => isset($validated['password']) ? Hash::make($validated['password']) : $user->password,
        ]);

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        return redirect()->back()->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(User $user)
    {
        abort_unless($user->tenant_id === auth()->user()?->tenant_id, 404);
        $user->delete();

        return redirect()->back()->with('success', 'Usuario eliminado exitosamente');
    }
}
