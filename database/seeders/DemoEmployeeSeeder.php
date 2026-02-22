<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'employee.test@example.com'],
            [
                'name' => 'Empleado Test',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
            ]
        );

        $userRole = Role::whereIn('name', ['empleado', 'user'])->first();
        if ($userRole) {
            $user->roles()->syncWithoutDetaching([$userRole->id]);
        }

        $leadIds = Lead::orderBy('id')->limit(3)->pluck('id');
        Lead::whereIn('id', $leadIds)->update(['assigned_to' => $user->id]);
    }
}
