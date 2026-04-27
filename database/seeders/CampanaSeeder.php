<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Campana;
use App\Models\Zona;
use Illuminate\Database\Seeder;

class CampanaSeeder extends Seeder
{
    public function run(): void
    {
        $zona1 = Zona::where('id_personalizado', 'laredsocial-centro')->first();

        if ($zona1) {
            $campanas = [
                [
                    'zona_id' => $zona1->id,
                    'titulo' => 'Bienvenido a Sucursal Centro',
                    'tipo' => 'imagen',
                    'file_path' => 'https://placehold.co/800x450/1a56db/white?text=Bienvenido+1',
                    'duracion' => 8,
                    'prioridad' => 1,
                    'is_active' => true,
                ],
                [
                    'zona_id' => $zona1->id,
                    'titulo' => 'Promoción de la semana',
                    'tipo' => 'imagen',
                    'file_path' => 'https://placehold.co/800x450/1a56db/white?text=Bienvenido+2',
                    'duracion' => 8,
                    'prioridad' => 2,
                    'is_active' => true,
                ]
            ];

            foreach ($campanas as $campana) {
                Campana::firstOrCreate(['titulo' => $campana['titulo']], $campana);
            }
        }
    }
}
