<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('platform_name', 'CRM landings.cl');
        Setting::set('platform_logo', '');
    }
}
