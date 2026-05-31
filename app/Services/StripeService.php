<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Plan;
use App\Models\Voucher;
use App\Models\Zona;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\StripeClient;

class StripeService
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function crearSesionCheckout(Plan $plan, Zona $zona, Voucher $voucher): string
    {
        $checkoutData = [
            'mode'        => 'payment',
            'line_items'  => [[
                'price_data' => [
                    'currency'     => 'mxn',
                    'product_data' => [
                        'name'        => $plan->nombre,
                        'description' => $plan->descripcion ?? $zona->nombre,
                    ],
                    'unit_amount' => (int) ($plan->precio * 100),
                ],
                'quantity' => 1,
            ]],
            'success_url'    => route('portal.pago-exitoso', ['zona' => $zona->id_personalizado]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'     => route('portal.zona', ['zona' => $zona->id_personalizado]) . '?checkout=cancelado',
            'metadata'       => [
                'voucher_id' => (string) $voucher->id,
                'zona_id'    => (string) $zona->id,
                'plan_id'    => (string) $plan->id,
            ]
        ];

        if (!empty($voucher->comprador_email)) {
            $checkoutData['customer_email'] = $voucher->comprador_email;
        }

        $session = $this->stripe->checkout->sessions->create($checkoutData);

        return $session->url;
    }

    public function obtenerSesion(string $sessionId): Session
    {
        return $this->stripe->checkout->sessions->retrieve($sessionId);
    }

    public function crearPaymentIntent(
        Plan $plan,
        Zona $zona,
        Voucher $voucher,
        ?string $hotspotIp = null,
        ?string $hotspotMac = null,
    ): PaymentIntent
    {
        $intentData = [
            'amount' => (int) ($plan->precio * 100),
            'currency' => 'mxn',
            'payment_method_types' => ['card'],
            'description' => sprintf('Voucher %s - %s', $plan->nombre, $zona->nombre),
            'metadata' => [
                'voucher_id' => (string) $voucher->id,
                'zona_id' => (string) $zona->id,
                'plan_id' => (string) $plan->id,
                'hotspot_ip' => (string) ($hotspotIp ?? ''),
                'hotspot_mac' => (string) ($hotspotMac ?? ''),
            ],
        ];

        if (! empty($voucher->comprador_email)) {
            $intentData['receipt_email'] = $voucher->comprador_email;
        }

        return $this->stripe->paymentIntents->create($intentData);
    }

    public function obtenerPaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return $this->stripe->paymentIntents->retrieve($paymentIntentId, []);
    }
}
