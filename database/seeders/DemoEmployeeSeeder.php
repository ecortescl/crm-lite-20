<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-workspace')->first();
        if (! $tenant) {
            return;
        }

        $user = User::updateOrCreate(
            ['email' => 'employee.test@example.com'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Empleado Test',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
            ]
        );

        $userRole = Role::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenant->id)
            ->whereIn('name', ['empleado', 'user'])
            ->first();
        if ($userRole) {
            $user->roles()->syncWithoutDetaching([$userRole->id]);
        }

        $leadIds = Lead::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenant->id)
            ->orderBy('id')
            ->limit(3)
            ->pluck('id');
        Lead::whereIn('id', $leadIds)->update(['assigned_to' => $user->id]);
    }
}
