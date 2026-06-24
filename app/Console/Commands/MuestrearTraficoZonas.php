<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Zona;
use App\Models\ZonaTraficoMuestra;
use App\Services\MikrotikService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MuestrearTraficoZonas extends Command
{
    protected $signature = 'trafico:muestrear {--dias-retencion=7 : Días de muestras a conservar}';

    protected $description = 'Toma una muestra del throughput (bps) de la interfaz de cada zona vía API MikroTik';

    public function handle(): int
    {
        // Solo zonas que tienen interfaz definida y credenciales API.
        $zonas = Zona::query()
            ->whereNotNull('mikrotik_interface')
            ->where('mikrotik_interface', '!=', '')
            ->whereNotNull('hotspot_host')
            ->get();

        if ($zonas->isEmpty()) {
            $this->info('No hay zonas con interfaz configurada para muestrear.');

            return self::SUCCESS;
        }

        $ahora = Carbon::now();
        $ok = 0;
        $fallidas = 0;

        foreach ($zonas as $zona) {
            $lectura = (new MikrotikService($zona))
                ->medirThroughputInterfaz((string) $zona->mikrotik_interface);

            if ($lectura === null) {
                $fallidas++;
                $this->warn("Zona #{$zona->id} ({$zona->nombre}): sin lectura.");

                continue;
            }

            ZonaTraficoMuestra::create([
                'zona_id'      => $zona->id,
                'rx_bps'       => $lectura['rx_bps'],
                'tx_bps'       => $lectura['tx_bps'],
                'capturado_at' => $ahora,
            ]);

            $ok++;
        }

        // Purga de muestras antiguas para no llenar la BD (VPS 512 MB).
        $retencion = (int) $this->option('dias-retencion');
        if ($retencion > 0) {
            ZonaTraficoMuestra::where('capturado_at', '<', $ahora->copy()->subDays($retencion))->delete();
        }

        $this->info("Muestreo terminado: {$ok} ok, {$fallidas} fallidas.");

        return self::SUCCESS;
    }
}
