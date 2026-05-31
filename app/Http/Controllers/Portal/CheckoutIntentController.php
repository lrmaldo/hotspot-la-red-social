<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Voucher;
use App\Models\Zona;
use App\Services\MikrotikService;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutIntentController extends Controller
{
    public function __invoke(Request $request, Zona $zona): JsonResponse
    {
        if (! $zona->is_active || ! $zona->venta_vouchers_activa) {
            abort(404);
        }

        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:planes,id'],
            'compra_email' => ['nullable', 'email'],
            'compra_nombre' => ['nullable', 'string', 'max:120'],
            'hotspot_ip' => ['nullable', 'ip'],
            'hotspot_mac' => ['nullable', 'string', 'max:64'],
        ]);

        $plan = Plan::where('id', $data['plan_id'])
            ->where('zona_id', $zona->id)
            ->where('is_active', true)
            ->firstOrFail();

        $voucher = Voucher::create([
            'zona_id' => $zona->id,
            'plan_id' => $plan->id,
            'codigo' => Voucher::generarCodigo(),
            'estado' => 'pendiente',
            'comprador_nombre' => $data['compra_nombre'] ?: null,
            'comprador_email' => $data['compra_email'] ?: null,
        ]);

        $hotspotIp = $data['hotspot_ip'] ?? null;
        $hotspotMac = $data['hotspot_mac'] ?? null;

        if ($hotspotIp) {
            (new MikrotikService($zona))->habilitarAccesoPagoTemporal($hotspotIp, $hotspotMac, 10);
        }

        try {
            $intent = (new StripeService())->crearPaymentIntent($plan, $zona, $voucher, $hotspotIp, $hotspotMac);
        } catch (\Throwable $e) {
            Log::error('Checkout Stripe Elements: no se pudo crear payment_intent', [
                'zona_id' => $zona->id,
                'plan_id' => $plan->id,
                'voucher_id' => $voucher->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'No pudimos iniciar la pasarela de pago. Intenta nuevamente en unos segundos.',
            ], 422);
        }

        $voucher->update([
            'stripe_payment_id' => $intent->id,
        ]);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'payment_intent_id' => $intent->id,
        ]);
    }
}