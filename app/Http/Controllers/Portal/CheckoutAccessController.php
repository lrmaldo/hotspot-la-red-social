<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use App\Services\MikrotikService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutAccessController extends Controller
{
    public function __invoke(Request $request, Zona $zona): JsonResponse
    {
        if (! $zona->is_active || ! $zona->venta_vouchers_activa) {
            abort(404);
        }

        $data = $request->validate([
            'hotspot_ip' => ['nullable', 'ip'],
            'hotspot_mac' => ['nullable', 'string', 'max:64'],
        ]);

        $hotspotIp = $data['hotspot_ip'] ?? null;
        $hotspotMac = $data['hotspot_mac'] ?? null;

        if (! $hotspotIp) {
            $hotspotIp = (string) $request->session()->get('hotspot.ip', '');
            $hotspotIp = $hotspotIp !== '' ? $hotspotIp : null;
        }

        if (! $hotspotMac) {
            $hotspotMac = (string) $request->session()->get('hotspot.mac', '');
            $hotspotMac = $hotspotMac !== '' ? $hotspotMac : null;
        }

        if (! $hotspotIp && ! $hotspotMac) {
            return response()->json([
                'ok' => false,
                'message' => 'No se detectó IP ni MAC del cliente para habilitar acceso temporal.',
            ], 422);
        }

        $mikrotik = new MikrotikService($zona);
        $resolvedIp = $mikrotik->resolverIpParaPagoTemporal($hotspotIp, $hotspotMac);

        if (! $resolvedIp) {
            return response()->json([
                'ok' => false,
                'message' => 'No se pudo resolver la IP del cliente en el hotspot.',
            ], 422);
        }

        try {
            $ok = $mikrotik->habilitarAccesoPagoTemporal($resolvedIp, $hotspotMac, 10);

            if (! $ok) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No se pudo habilitar el acceso temporal para pago.',
                ], 422);
            }

            return response()->json([
                'ok' => true,
                'hotspot_ip' => $resolvedIp,
                'stripe_key' => (string) config('services.stripe.key'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Checkout access temporal: error al habilitar acceso en MikroTik', [
                'zona_id' => $zona->id,
                'hotspot_ip' => $resolvedIp,
                'hotspot_mac' => $hotspotMac,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Error inesperado al habilitar acceso temporal.',
            ], 500);
        }
    }
}