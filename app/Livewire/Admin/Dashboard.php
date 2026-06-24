<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Campana;
use App\Models\Voucher;
use App\Models\Zona;
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
        ]);
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
