<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = LeadStatus::all();
        $users = User::all();
        $companies = \App\Models\Company::all();

        $leads = [
            ['name' => 'Juan Pérez', 'email' => 'juan@example.com', 'phone' => '+56912345678', 'company_id' => $companies->first()?->id, 'contact_company' => null],
            ['name' => 'María González', 'email' => 'maria@example.com', 'phone' => '+56987654321', 'company_id' => $companies->skip(1)->first()?->id, 'contact_company' => null],
            ['name' => 'Carlos Rodríguez', 'email' => 'carlos@example.com', 'phone' => '+56911223344', 'company_id' => null, 'contact_company' => 'StartupXYZ'],
            ['name' => 'Ana Martínez', 'email' => 'ana@example.com', 'phone' => '+56922334455', 'company_id' => $companies->skip(2)->first()?->id, 'contact_company' => null],
            ['name' => 'Pedro Sánchez', 'email' => 'pedro@example.com', 'phone' => '+56933445566', 'company_id' => null, 'contact_company' => 'Innovation Labs'],
            ['name' => 'Laura Torres', 'email' => 'laura@example.com', 'phone' => '+56944556677', 'company_id' => null, 'contact_company' => 'Marketing Pro'],
            ['name' => 'Diego Ramírez', 'email' => 'diego@example.com', 'phone' => '+56955667788', 'company_id' => $companies->skip(3)->first()?->id, 'contact_company' => null],
            ['name' => 'Sofía Vargas', 'email' => 'sofia@example.com', 'phone' => '+56966778899', 'company_id' => null, 'contact_company' => null], // Sin empresa
        ];

        foreach ($leads as $leadData) {
            Lead::create([
                'name' => $leadData['name'],
                'email' => $leadData['email'],
                'phone' => $leadData['phone'],
                'company_id' => $leadData['company_id'],
                'contact_company' => $leadData['contact_company'],
                'notes' => 'Lead generado automáticamente para pruebas',
                'lead_status_id' => $statuses->random()->id,
                'assigned_to' => $users->random()->id,
            ]);
        }
    }
}
