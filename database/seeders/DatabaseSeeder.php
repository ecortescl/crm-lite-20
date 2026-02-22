<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            LeadStatusSeeder::class,
            SettingSeeder::class,
            CompanySeeder::class,
        ]);

        $admin = User::create([
            'name' => 'Jefatura User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $jefaturaRoleId = Role::whereIn('name', ['jefatura', 'admin'])->value('id');
        if ($jefaturaRoleId) {
            $admin->roles()->attach($jefaturaRoleId);
        }

        $this->call([
            LeadSeeder::class,
            DemoEmployeeSeeder::class,
        ]);
    }
}
