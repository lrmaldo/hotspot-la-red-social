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

            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
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

        $this->finalizarVoucherPagado(
            $voucher,
            (float) (($session->amount_total ?? 0) / 100),
            $session->id,
            (string) ($session->payment_intent ?? ''),
            'checkout.session.completed',
        );
    }

    private function handlePaymentIntentSucceeded(object $paymentIntent): void
    {
        $voucherId = $paymentIntent->metadata->voucher_id ?? null;

        if (! $voucherId) {
            Log::warning('Stripe webhook: payment_intent.succeeded sin voucher_id en metadata');
            return;
        }

        $voucher = Voucher::with('plan', 'zona')->find($voucherId);

        if (! $voucher) {
            Log::warning('Stripe webhook: voucher no encontrado en payment_intent.succeeded', ['voucher_id' => $voucherId]);
            return;
        }

        $this->finalizarVoucherPagado(
            $voucher,
            (float) (($paymentIntent->amount_received ?: $paymentIntent->amount) / 100),
            null,
            (string) $paymentIntent->id,
            'payment_intent.succeeded',
        );
    }

    private function handlePaymentIntentFailed(object $paymentIntent): void
    {
        $voucherId = $paymentIntent->metadata->voucher_id ?? null;

        if (! $voucherId) {
            return;
        }

        $voucher = Voucher::find($voucherId);

        if (! $voucher) {
            return;
        }

        $voucher->update([
            'estado' => 'pendiente',
            'stripe_payment_id' => (string) $paymentIntent->id,
        ]);

        PagoLog::create([
            'voucher_id' => $voucher->id,
            'evento' => 'payment_intent.payment_failed',
            'monto' => ($paymentIntent->amount ?? 0) / 100,
            'pasarela' => 'stripe',
            'referencia_externa' => (string) $paymentIntent->id,
            'respuesta_json' => (array) $paymentIntent,
            'estado' => 'rechazado',
        ]);
    }

    private function finalizarVoucherPagado(
        Voucher $voucher,
        float $monto,
        ?string $sessionId,
        string $paymentId,
        string $evento,
    ): void {
        if ($voucher->estado === 'vendido' && $voucher->stripe_payment_id === $paymentId) {
            return;
        }

        DB::transaction(function () use ($voucher, $monto, $sessionId, $paymentId, $evento): void {
            $voucherData = [
                'estado'            => 'vendido',
                'stripe_payment_id' => $paymentId,
                'monto_pagado'      => $monto,
                'fecha_venta'       => now(),
                'fecha_expiracion'  => now()->addMinutes($voucher->plan->duracion_minutos),
            ];

            if ($sessionId) {
                $voucherData['stripe_session_id'] = $sessionId;
            }

            $voucher->update([
                ...$voucherData,
            ]);

            PagoLog::create([
                'voucher_id'         => $voucher->id,
                'evento'             => $evento,
                'monto'              => $monto,
                'pasarela'           => 'stripe',
                'referencia_externa' => $paymentId,
                'respuesta_json'     => [
                    'payment_id' => $paymentId,
                    'session_id' => $sessionId,
                ],
                'estado'             => 'aprobado',
            ]);

            // Evita reintentar creacion de usuario si ya fue sincronizado en un webhook previo.
            if ($voucher->mikrotik_sync_status === 'ok') {
                PagoLog::create([
                    'voucher_id' => $voucher->id,
                    'evento' => 'mikrotik.sync.skipped',
                    'monto' => null,
                    'pasarela' => 'mikrotik',
                    'referencia_externa' => (string) $voucher->id,
                    'respuesta_json' => ['reason' => 'already_synced'],
                    'estado' => 'aprobado',
                ]);
            } else {
                $mikrotik = new MikrotikService($voucher->zona);
                $syncOk = $mikrotik->crearUsuarioHotspot($voucher);

                $voucher->update([
                    'mikrotik_sync_status' => $syncOk ? 'ok' : 'error',
                    'mikrotik_sync_message' => $syncOk
                        ? 'Usuario hotspot creado correctamente.'
                        : 'No se pudo crear el usuario en MikroTik. Revisar conectividad/credenciales.',
                    'mikrotik_synced_at' => $syncOk ? now() : null,
                ]);

                PagoLog::create([
                    'voucher_id' => $voucher->id,
                    'evento' => 'mikrotik.sync',
                    'monto' => null,
                    'pasarela' => 'mikrotik',
                    'referencia_externa' => (string) $voucher->id,
                    'respuesta_json' => ['result' => $syncOk ? 'ok' : 'error'],
                    'estado' => $syncOk ? 'aprobado' : 'rechazado',
                ]);
            }

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
