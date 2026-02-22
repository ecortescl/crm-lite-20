<?php

namespace Database\Seeders;

use App\Models\LeadStatus;
use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Nuevo registro', 'color' => '#3b82f6', 'icon' => 'FileText', 'order' => 1, 'is_default' => true],
            ['name' => 'Contactado', 'color' => '#8b5cf6', 'icon' => 'Phone', 'order' => 2, 'is_default' => false],
            ['name' => 'Descartado', 'color' => '#ef4444', 'icon' => 'XCircle', 'order' => 3, 'is_default' => false],
            ['name' => 'Reunión', 'color' => '#f59e0b', 'icon' => 'Calendar', 'order' => 4, 'is_default' => false],
            ['name' => 'Negociación', 'color' => '#10b981', 'icon' => 'TrendingUp', 'order' => 5, 'is_default' => false],
            ['name' => 'Concretado', 'color' => '#059669', 'icon' => 'CheckCircle2', 'order' => 6, 'is_default' => false],
        ];

        foreach ($statuses as $status) {
            LeadStatus::create($status);
        }
    }
}
