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
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrador con acceso completo',
        ]);

        $userRole = Role::create([
            'name' => 'user',
            'description' => 'Usuario estándar',
        ]);

        $adminRole->permissions()->attach(Permission::all());
        $userRole->permissions()->attach(Permission::whereIn('name', ['view_leads', 'create_leads', 'edit_leads'])->get());
    }
}
