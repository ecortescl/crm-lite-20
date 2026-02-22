<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'view_leads', 'description' => 'Ver leads'],
            ['name' => 'create_leads', 'description' => 'Crear leads'],
            ['name' => 'edit_leads', 'description' => 'Editar leads'],
            ['name' => 'delete_leads', 'description' => 'Eliminar leads'],
            ['name' => 'manage_users', 'description' => 'Gestionar usuarios'],
            ['name' => 'manage_roles', 'description' => 'Gestionar roles'],
            ['name' => 'manage_permissions', 'description' => 'Gestionar permisos'],
            ['name' => 'manage_lead_statuses', 'description' => 'Gestionar estados de leads'],
            ['name' => 'manage_api_tokens', 'description' => 'Gestionar tokens de API'],
            ['name' => 'manage_platform_settings', 'description' => 'Gestionar configuracion de plataforma'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']],
            );
        }

        $legacyAdminRole = Role::where('name', 'admin')->first();
        $legacyUserRole = Role::where('name', 'user')->first();

        $jefaturaRole = Role::where('name', 'jefatura')->first();
        $empleadoRole = Role::where('name', 'empleado')->first();

        if (! $jefaturaRole && $legacyAdminRole) {
            $legacyAdminRole->update([
                'name' => 'jefatura',
                'description' => 'Jefatura con acceso completo',
            ]);
            $jefaturaRole = $legacyAdminRole;
        }

        if (! $empleadoRole && $legacyUserRole) {
            $legacyUserRole->update([
                'name' => 'empleado',
                'description' => 'Empleado estándar',
            ]);
            $empleadoRole = $legacyUserRole;
        }

        $jefaturaRole = $jefaturaRole ?? Role::updateOrCreate(
            ['name' => 'jefatura'],
            ['description' => 'Jefatura con acceso completo'],
        );

        $empleadoRole = $empleadoRole ?? Role::updateOrCreate(
            ['name' => 'empleado'],
            ['description' => 'Empleado estándar'],
        );

        if ($legacyAdminRole && $legacyAdminRole->id !== $jefaturaRole->id) {
            $jefaturaRole->users()->syncWithoutDetaching($legacyAdminRole->users()->pluck('users.id')->all());
            $legacyAdminRole->delete();
        }

        if ($legacyUserRole && $legacyUserRole->id !== $empleadoRole->id) {
            $empleadoRole->users()->syncWithoutDetaching($legacyUserRole->users()->pluck('users.id')->all());
            $legacyUserRole->delete();
        }

        $jefaturaRole->permissions()->sync(Permission::query()->pluck('id')->all());
        $empleadoRole->permissions()->sync(
            Permission::whereIn('name', ['view_leads', 'create_leads', 'edit_leads'])->pluck('id')->all()
        );
    }
}
