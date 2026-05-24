<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Zona;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $zona = Zona::where('id_personalizado', 'laredsocial-centro')->first();

        if (! $zona) {
            return;
        }

        $planes = [
            [
                'nombre'           => '1 Hora',
                'duracion_minutos' => 60,
                'precio'           => 15.00,
            ],
            [
                'nombre'           => '1 Día',
                'duracion_minutos' => 1440,
                'precio'           => 40.00,
            ],
            [
                'nombre'           => '1 Semana',
                'duracion_minutos' => 10080,
                'precio'           => 99.00,
            ],
        ];

        foreach ($planes as $plan) {
            Plan::firstOrCreate(
                [
                    'zona_id' => $zona->id,
                    'nombre'  => $plan['nombre'],
                ],
                array_merge($plan, ['zona_id' => $zona->id]),
            );
        }
    }
}
