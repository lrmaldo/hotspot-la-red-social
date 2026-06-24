<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Campana;
use App\Models\Voucher;
use App\Models\Zona;
use App\Models\ZonaTraficoMuestra;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    /** Número de meses a mostrar en la gráfica de ganancias. */
    public int $meses = 6;

    public function render()
    {
        $ahora = Carbon::now();

        // --- Vouchers: solo cuentan los que se vendieron de verdad ---
        // fecha_venta solo se rellena al concretarse el pago, así que es
        // el indicador más fiable de "voucher generado" (excluye los
        // 'pendiente' que son checkouts iniciados que nunca se pagaron).
        $vendidosBase = Voucher::whereNotNull('fecha_venta');

        $vouchersTotales = (clone $vendidosBase)->count();

        $vouchersMes = (clone $vendidosBase)
            ->where('fecha_venta', '>=', $ahora->copy()->startOfMonth())
            ->count();

        // Activos = vendidos cuya sesión sigue vigente (no han expirado).
        $vouchersActivos = Voucher::where('estado', 'vendido')
            ->whereNotNull('fecha_expiracion')
            ->where('fecha_expiracion', '>', $ahora)
            ->count();

        $ingresosMes = (float) (clone $vendidosBase)
            ->where('fecha_venta', '>=', $ahora->copy()->startOfMonth())
            ->sum('monto_pagado');

        $ingresosTotales = (float) (clone $vendidosBase)->sum('monto_pagado');

        // --- Ganancias mes a mes (últimos N meses) ---
        $ganancias = $this->gananciasPorMes($this->meses);

        return view('livewire.admin.dashboard', [
            'zonasCount'       => Zona::count(),
            'campanasCount'    => Campana::count(),
            'vouchersTotales'  => $vouchersTotales,
            'vouchersMes'      => $vouchersMes,
            'vouchersActivos'  => $vouchersActivos,
            'ingresosMes'      => $ingresosMes,
            'ingresosTotales'  => $ingresosTotales,
            'ganancias'        => $ganancias,
            'traficoZonas'     => $this->traficoPromedioPorZona(),
        ]);
    }

    /**
     * Promedio de throughput (últimas 24 h) de cada zona con interfaz
     * configurada. AVG + GROUP BY son portables (SQLite/MariaDB).
     *
     * @return array<int, array{nombre: string, rx_mbps: float, tx_mbps: float, muestras: int, ultima: ?string}>
     */
    private function traficoPromedioPorZona(): array
    {
        $zonas = Zona::query()
            ->whereNotNull('mikrotik_interface')
            ->where('mikrotik_interface', '!=', '')
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        if ($zonas->isEmpty()) {
            return [];
        }

        $desde = Carbon::now()->subDay();

        $agg = ZonaTraficoMuestra::query()
            ->where('capturado_at', '>=', $desde)
            ->whereIn('zona_id', $zonas->pluck('id'))
            ->selectRaw('zona_id')
            ->selectRaw('AVG(rx_bps) as rx_avg')
            ->selectRaw('AVG(tx_bps) as tx_avg')
            ->selectRaw('COUNT(*) as muestras')
            ->selectRaw('MAX(capturado_at) as ultima')
            ->groupBy('zona_id')
            ->get()
            ->keyBy('zona_id');

        return $zonas->map(function (Zona $zona) use ($agg): array {
            $fila = $agg->get($zona->id);

            return [
                'nombre'   => $zona->nombre,
                'rx_mbps'  => $fila ? round(((float) $fila->rx_avg) / 1_000_000, 2) : 0.0,
                'tx_mbps'  => $fila ? round(((float) $fila->tx_avg) / 1_000_000, 2) : 0.0,
                'muestras' => $fila ? (int) $fila->muestras : 0,
                'ultima'   => $fila && $fila->ultima ? Carbon::parse($fila->ultima)->diffForHumans() : null,
            ];
        })->all();
    }

    /**
     * Devuelve una colección ordenada de los últimos $meses con etiqueta,
     * total de ingresos y cantidad de vouchers vendidos en cada mes.
     *
     * @return array<int, array{ym: string, label: string, total: float, cantidad: int}>
     */
    private function gananciasPorMes(int $meses): array
    {
        $meses_es = [1 => 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        // Una consulta agregada por mes con whereBetween. Es portable
        // (SQLite local + MariaDB producción) y usa el índice de fecha_venta,
        // a diferencia de DATE_FORMAT que solo existe en MySQL/MariaDB.
        $inicio = Carbon::now()->startOfMonth()->subMonths($meses - 1);

        $resultado = [];
        for ($i = 0; $i < $meses; $i++) {
            $desde = $inicio->copy()->addMonths($i);
            $hasta = $desde->copy()->endOfMonth();

            $fila = Voucher::query()
                ->whereNotNull('fecha_venta')
                ->whereBetween('fecha_venta', [$desde, $hasta])
                ->selectRaw('COALESCE(SUM(monto_pagado), 0) as total')
                ->selectRaw('COUNT(*) as cantidad')
                ->first();

            $resultado[] = [
                'ym'       => $desde->format('Y-m'),
                'label'    => $meses_es[(int) $desde->format('n')] . ' ' . $desde->format('y'),
                'total'    => (float) ($fila->total ?? 0),
                'cantidad' => (int) ($fila->cantidad ?? 0),
            ];
        }

        return $resultado;
    }
}
