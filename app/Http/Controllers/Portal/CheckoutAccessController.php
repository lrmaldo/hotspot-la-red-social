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
            return response()->json([
                'ok' => false,
                'message' => 'No se detectó la IP del cliente para habilitar acceso temporal.',
            ], 422);
        }

        try {
            $ok = (new MikrotikService($zona))->habilitarAccesoPagoTemporal($hotspotIp, $hotspotMac, 10);

            if (! $ok) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No se pudo habilitar el acceso temporal para pago.',
                ], 422);
            }

            return response()->json([
                'ok' => true,
                'hotspot_ip' => $hotspotIp,
            ]);
        } catch (\Throwable $e) {
            Log::error('Checkout access temporal: error al habilitar acceso en MikroTik', [
                'zona_id' => $zona->id,
                'hotspot_ip' => $hotspotIp,
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