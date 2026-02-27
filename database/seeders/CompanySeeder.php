<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-workspace')->first();
        if (! $tenant) {
            return;
        }

        $companies = [
            [
                'business_name' => 'Tecnología Innovadora SpA',
                'rut' => '761234567',
                'fantasy_name' => 'TechInno',
                'giro' => 'Desarrollo de software y consultoría tecnológica',
                'email' => 'contacto@techinno.cl',
                'phone' => '+56 2 2345 6789',
                'website' => 'https://techinno.cl',
                'address' => 'Av. Apoquindo 4800, Piso 12',
                'commune' => 'Las Condes',
                'city' => 'Santiago',
                'region' => 'Región Metropolitana',
                'size' => 'medium',
                'industry' => 'Tecnología',
            ],
            [
                'business_name' => 'Comercial del Sur Limitada',
                'rut' => '789876543',
                'fantasy_name' => 'ComSur',
                'giro' => 'Comercio al por mayor y menor',
                'email' => 'ventas@comsur.cl',
                'phone' => '+56 9 8765 4321',
                'website' => 'https://comsur.cl',
                'address' => 'Calle Principal 123',
                'commune' => 'Temuco',
                'city' => 'Temuco',
                'region' => 'Región de La Araucanía',
                'size' => 'small',
                'industry' => 'Retail',
            ],
            [
                'business_name' => 'Constructora Edificar SA',
                'rut' => '765432109',
                'fantasy_name' => 'Edificar',
                'giro' => 'Construcción de edificios y obras civiles',
                'email' => 'info@edificar.cl',
                'phone' => '+56 2 3456 7890',
                'website' => 'https://edificar.cl',
                'address' => 'Av. Providencia 2594',
                'commune' => 'Providencia',
                'city' => 'Santiago',
                'region' => 'Región Metropolitana',
                'size' => 'large',
                'industry' => 'Construcción',
            ],
            [
                'business_name' => 'Servicios Profesionales Consultores Ltda',
                'rut' => '771234568',
                'fantasy_name' => null,
                'giro' => 'Consultoría empresarial y asesoría',
                'email' => 'contacto@spconsultores.cl',
                'phone' => '+56 9 7654 3210',
                'website' => null,
                'address' => 'Av. Libertador Bernardo O\'Higgins 1234',
                'commune' => 'Santiago Centro',
                'city' => 'Santiago',
                'region' => 'Región Metropolitana',
                'size' => 'micro',
                'industry' => 'Consultoría',
            ],
            [
                'business_name' => 'Alimentos del Valle SpA',
                'rut' => '782345679',
                'fantasy_name' => 'Valle Foods',
                'giro' => 'Producción y distribución de alimentos',
                'email' => 'ventas@vallefoods.cl',
                'phone' => '+56 2 4567 8901',
                'website' => 'https://vallefoods.cl',
                'address' => 'Camino Industrial 567',
                'commune' => 'Rancagua',
                'city' => 'Rancagua',
                'region' => 'Región del Libertador General Bernardo O\'Higgins',
                'size' => 'medium',
                'industry' => 'Alimentos',
            ],
        ];

        foreach ($companies as $company) {
            Company::create([
                'tenant_id' => $tenant->id,
                ...$company,
            ]);
        }
    }
}
