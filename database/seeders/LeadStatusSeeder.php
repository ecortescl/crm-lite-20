<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Services\TenantProvisioningService;
use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    public function run(): void
    {
        $provisioning = app(TenantProvisioningService::class);
        Tenant::query()->each(function (Tenant $tenant) use ($provisioning) {
            $provisioning->provision($tenant);
        });
    }
}
