<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Voucher;
use App\Models\Zona;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Pago exitoso')]
class PagoExitoso extends Component
{
    public Zona $zona;
    public ?Voucher $voucher = null;
    public string $sessionId = '';
    public int $intentos = 0;
    public bool $procesando = true;

    public function mount(Zona $zona): void
    {
        $this->zona = $zona;
        $this->sessionId = request()->get('session_id', '');
        $this->cargarVoucher();
    }

    public function cargarVoucher(): void
    {
        $this->voucher = Voucher::with('plan')
            ->where('stripe_session_id', $this->sessionId)
            ->where('zona_id', $this->zona->id)
            ->first();

        if ($this->voucher && $this->voucher->estado === 'vendido') {
            $this->procesando = false;
        } else {
            $this->intentos++;
            if ($this->intentos >= 10) {
                $this->procesando = false;
            }
        }
    }

    public function render()
    {
        return view('livewire.portal.pago-exitoso');
    }
}
