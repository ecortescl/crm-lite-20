<?php

namespace App\Services;

use App\Models\LeadStatus;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Tenant;
use App\Models\User;

class TenantProvisioningService
{
    public function provision(Tenant $tenant, ?User $owner = null): void
    {
        $permissions = collect([
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
        ])->map(function (array $permission) use ($tenant) {
            return Permission::withoutGlobalScope('tenant')->updateOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $permission['name']],
                ['description' => $permission['description']]
            );
        });

        $jefaturaRole = Role::withoutGlobalScope('tenant')->updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'jefatura'],
            ['description' => 'Jefatura con acceso completo'],
        );

        $empleadoRole = Role::withoutGlobalScope('tenant')->updateOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'empleado'],
            ['description' => 'Empleado estándar'],
        );

        $jefaturaRole->permissions()->sync($permissions->pluck('id')->all());
        $empleadoRole->permissions()->sync(
            $permissions
                ->whereIn('name', ['view_leads', 'create_leads', 'edit_leads'])
                ->pluck('id')
                ->all()
        );

        if ($owner) {
            $owner->roles()->syncWithoutDetaching([$jefaturaRole->id]);
        }

        $statuses = [
            ['name' => 'Nuevo registro', 'color' => '#3b82f6', 'icon' => 'FileText', 'order' => 1, 'is_default' => true],
            ['name' => 'Contactado', 'color' => '#8b5cf6', 'icon' => 'Phone', 'order' => 2, 'is_default' => false],
            ['name' => 'Descartado', 'color' => '#ef4444', 'icon' => 'XCircle', 'order' => 3, 'is_default' => false],
            ['name' => 'Reunión', 'color' => '#f59e0b', 'icon' => 'Calendar', 'order' => 4, 'is_default' => false],
            ['name' => 'Negociación', 'color' => '#10b981', 'icon' => 'TrendingUp', 'order' => 5, 'is_default' => false],
            ['name' => 'Concretado', 'color' => '#059669', 'icon' => 'CheckCircle2', 'order' => 6, 'is_default' => false],
        ];

        foreach ($statuses as $status) {
            LeadStatus::withoutGlobalScope('tenant')->updateOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $status['name']],
                $status
            );
        }

        Setting::set('platform_name', 'CRM '.($tenant->name ?: config('app.name')), $tenant->id);
        Setting::set('platform_logo', '', $tenant->id);
    }
}
