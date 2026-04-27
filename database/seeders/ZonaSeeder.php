<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Zona;
use Illuminate\Database\Seeder;

class ZonaSeeder extends Seeder
{
    public function run(): void
    {
        $zonas = [
            [
                'nombre' => 'Sucursal Centro',
                'id_personalizado' => 'laredsocial-centro',
                'hotspot_host' => '192.168.88.1',
                'facebook_url' => 'https://facebook.com/laredsocial',
                'color_primario' => '#1a56db',
            ],
            [
                'nombre' => 'Sucursal Norte',
                'id_personalizado' => 'laredsocial-norte',
                'hotspot_host' => '192.168.88.2',
            ]
        ];

        foreach ($zonas as $zona) {
            Zona::firstOrCreate(['id_personalizado' => $zona['id_personalizado']], $zona);
        }
    }
}
