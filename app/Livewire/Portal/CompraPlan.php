<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Plan;
use App\Models\Voucher;
use App\Models\Zona;
use App\Services\StripeService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Comprar acceso WiFi')]
class CompraPlan extends Component
{
    public Zona $zona;
    public Collection $planes;
    public ?int $planId = null;
    public string $nombre = '';
    public string $email = '';
    public bool $cargando = false;

    public function mount(Zona $zona): void
    {
        if (! $zona->is_active || ! $zona->venta_vouchers_activa) {
            abort(404);
        }

        $this->zona = $zona;
        $this->planes = $zona->planes()->activos()->orderBy('precio')->get();
    }

    public function seleccionarPlan(int $planId): void
    {
        $this->planId = $planId;
        $this->dispatch('plan-seleccionado');
    }

    public function iniciarPago(): void
    {
        $this->validate([
            'planId' => ['required', 'exists:planes,id'],
            'email'  => ['nullable', 'email'],
        ]);

        $plan = Plan::findOrFail($this->planId);

        $voucher = Voucher::create([
            'zona_id'          => $this->zona->id,
            'plan_id'          => $plan->id,
            'codigo'           => Voucher::generarCodigo(),
            'estado'           => 'pendiente',
            'comprador_nombre' => $this->nombre ?: null,
            'comprador_email'  => $this->email ?: null,
        ]);

        $url = (new StripeService())->crearSesionCheckout($plan, $this->zona, $voucher);

        $this->cargando = true;
        $this->redirect($url);
    }

    public function render()
    {
        return view('livewire.portal.compra-plan');
    }
}
