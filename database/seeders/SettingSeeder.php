<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::query()->each(function (Tenant $tenant) {
            Setting::set('platform_name', 'CRM '.$tenant->name, $tenant->id);
            Setting::set('platform_logo', '', $tenant->id);
        });
    }
}
