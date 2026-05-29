<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Vouchers;

use App\Models\Voucher;
use App\Models\Zona;
use App\Services\MikrotikService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Gestión de Vouchers')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'zona')]
    public string $filtroZona = '';

    #[Url(as: 'estado')]
    public string $filtroEstado = '';

    #[Url(as: 'q')]
    public string $filtroBusqueda = '';

    public bool $showDetalle = false;
    public ?Voucher $voucherDetalle = null;

    public function updatedFiltroZona(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroEstado(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroBusqueda(): void
    {
        $this->resetPage();
    }

    public function verDetalle(int $id): void
    {
        $this->voucherDetalle = Voucher::with(['plan', 'zona', 'pagoLogs'])->findOrFail($id);
        $this->showDetalle = true;
    }

    public function anular(int $id): void
    {
        $voucher = Voucher::with('zona')->findOrFail($id);

        $voucher->update(['estado' => 'expirado']);

        $mikrotik = new MikrotikService($voucher->zona);
        $mikrotik->eliminarUsuarioHotspot($voucher->codigo);
    }

    public function exportar(): StreamedResponse
    {
        $vouchers = $this->buildQuery()->get();

        return response()->streamDownload(function () use ($vouchers): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Código', 'Zona', 'Plan', 'Estado',
                'Comprador', 'Email', 'Monto Pagado',
                'Fecha Venta', 'Fecha Expiración',
                'MikroTik Sync', 'MikroTik Sync Fecha', 'MikroTik Sync Mensaje',
            ]);

            foreach ($vouchers as $v) {
                fputcsv($handle, [
                    $v->codigo,
                    $v->zona->nombre,
                    $v->plan->nombre,
                    $v->estado,
                    $v->comprador_nombre,
                    $v->comprador_email,
                    $v->monto_pagado,
                    $v->fecha_venta?->format('Y-m-d H:i:s'),
                    $v->fecha_expiracion?->format('Y-m-d H:i:s'),
                    $v->mikrotik_sync_status,
                    $v->mikrotik_synced_at?->format('Y-m-d H:i:s'),
                    $v->mikrotik_sync_message,
                ]);
            }

            fclose($handle);
        }, 'vouchers-' . now()->format('Y-m-d') . '.csv');
    }

    private function buildQuery()
    {
        $query = Voucher::with(['zona', 'plan'])
            ->orderBy('id', 'desc');

        if ($this->filtroZona) {
            $query->where('zona_id', $this->filtroZona);
        }

        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        if ($this->filtroBusqueda) {
            $search = $this->filtroBusqueda;
            $query->where(function ($q) use ($search) {
                $q->where('codigo', 'like', "%{$search}%")
                  ->orWhere('comprador_email', 'like', "%{$search}%")
                  ->orWhere('comprador_nombre', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function render()
    {
        $zonas = Zona::orderBy('nombre')->get();
        $vouchers = $this->buildQuery()->paginate(20);

        return view('livewire.admin.vouchers.index', compact('zonas', 'vouchers'));
    }
}
