<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Mail\VoucherComprado;
use App\Models\PagoLog;
use App\Models\Voucher;
use App\Models\Zona;
use App\Services\MikrotikService;
use App\Services\StripeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
    public string $paymentIntentId = '';
    public int $intentos = 0;
    public bool $procesando = true;

    public function mount(Zona $zona): void
    {
        $this->zona = $zona;
        $this->sessionId = request()->get('session_id', '');
        $this->paymentIntentId = request()->get('payment_intent', '');
        $this->cargarVoucher();
    }

    public function cargarVoucher(): void
    {
        $query = Voucher::with('plan')
            ->where('zona_id', $this->zona->id);

        if ($this->paymentIntentId !== '') {
            $query->where('stripe_payment_id', $this->paymentIntentId);
        } else {
            $query->where('stripe_session_id', $this->sessionId);
        }

        $this->voucher = $query->first();

        if ($this->voucher && $this->paymentIntentId !== '' && $this->voucher->estado !== 'vendido') {
            $this->intentarConfirmarPagoManual();
            $this->voucher = $query->first();
        }

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

    private function intentarConfirmarPagoManual(): void
    {
        if (! $this->voucher || $this->paymentIntentId === '') {
            return;
        }

        try {
            $paymentIntent = (new StripeService())->obtenerPaymentIntent($this->paymentIntentId);

            if (($paymentIntent->status ?? '') !== 'succeeded') {
                return;
            }

            DB::transaction(function (): void {
                $voucher = Voucher::with('plan', 'zona')
                    ->lockForUpdate()
                    ->find($this->voucher->id);

                if (! $voucher) {
                    return;
                }

                if ($voucher->estado === 'vendido') {
                    return;
                }

                $monto = (float) ($voucher->plan->precio);

                $voucher->update([
                    'estado' => 'vendido',
                    'stripe_payment_id' => $this->paymentIntentId,
                    'monto_pagado' => $monto,
                    'fecha_venta' => now(),
                    'fecha_expiracion' => now()->addMinutes($voucher->plan->duracion_minutos),
                ]);

                PagoLog::create([
                    'voucher_id' => $voucher->id,
                    'evento' => 'payment_intent.manual_confirmed',
                    'monto' => $monto,
                    'pasarela' => 'stripe',
                    'referencia_externa' => $this->paymentIntentId,
                    'respuesta_json' => ['source' => 'pago_exitoso_fallback'],
                    'estado' => 'aprobado',
                ]);

                $mikrotik = new MikrotikService($voucher->zona);
                $syncOk = $mikrotik->crearUsuarioHotspot($voucher);

                $voucher->update([
                    'mikrotik_sync_status' => $syncOk ? 'ok' : 'error',
                    'mikrotik_sync_message' => $syncOk
                        ? 'Usuario hotspot creado correctamente (fallback pago).'
                        : 'No se pudo crear el usuario en MikroTik desde fallback pago.',
                    'mikrotik_synced_at' => $syncOk ? now() : null,
                ]);

                if ($voucher->comprador_email) {
                    Mail::to($voucher->comprador_email)->send(new VoucherComprado($voucher));
                }
            });
        } catch (\Throwable $e) {
            Log::warning('PagoExitoso fallback: no se pudo confirmar/sincronizar pago', [
                'zona_id' => $this->zona->id,
                'payment_intent' => $this->paymentIntentId,
                'voucher_id' => $this->voucher->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
