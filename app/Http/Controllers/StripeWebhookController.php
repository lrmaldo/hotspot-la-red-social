<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\VoucherComprado;
use App\Models\PagoLog;
use App\Models\Voucher;
use App\Services\MikrotikService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController
{
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret'),
            );
        } catch (SignatureVerificationException) {
            return response('Unauthorized', 401);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($event->data->object);
                break;

            case 'checkout.session.expired':
                $this->handleCheckoutExpired($event->data->object);
                break;
        }

        return response('OK', 200);
    }

    private function handleCheckoutCompleted(object $session): void
    {
        $voucherId = $session->metadata->voucher_id ?? null;

        if (! $voucherId) {
            Log::warning('Stripe webhook: checkout.session.completed sin voucher_id en metadata');
            return;
        }

        $voucher = Voucher::with('plan', 'zona')->find($voucherId);

        if (! $voucher) {
            Log::warning('Stripe webhook: voucher no encontrado', ['voucher_id' => $voucherId]);
            return;
        }

        DB::transaction(function () use ($voucher, $session): void {
            $voucher->update([
                'estado'            => 'vendido',
                'stripe_session_id' => $session->id,
                'stripe_payment_id' => $session->payment_intent,
                'monto_pagado'      => $session->amount_total / 100,
                'fecha_venta'       => now(),
                'fecha_expiracion'  => now()->addMinutes($voucher->plan->duracion_minutos),
            ]);

            PagoLog::create([
                'voucher_id'         => $voucher->id,
                'evento'             => 'checkout.session.completed',
                'monto'              => $session->amount_total / 100,
                'referencia_externa' => $session->payment_intent,
                'respuesta_json'     => (array) $session,
                'estado'             => 'aprobado',
            ]);

            $mikrotik = new MikrotikService($voucher->zona);
            $mikrotik->crearUsuarioHotspot($voucher);

            if ($voucher->comprador_email) {
                Mail::to($voucher->comprador_email)
                    ->send(new VoucherComprado($voucher));
            }
        });
    }

    private function handleCheckoutExpired(object $session): void
    {
        Voucher::where('stripe_session_id', $session->id)
            ->update(['estado' => 'pendiente']);
    }
}
