<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantProvisioningService;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'demo-workspace'],
            ['name' => 'Demo Workspace']
        );

        $admin = User::updateOrCreate(
            ['email' => 'hola@ecortes.cl'],
            [
                'tenant_id' => $tenant->id,
                'is_platform_admin' => true,
                'name' => 'Super Admin',
                'password' => Hash::make('asdf1234'),
                'email_verified_at' => now(),
            ]
        );

        app(TenantProvisioningService::class)->provision($tenant, $admin);

        $this->call([
            CompanySeeder::class,
            LeadSeeder::class,
            DemoEmployeeSeeder::class,
        ]);
    }
}
