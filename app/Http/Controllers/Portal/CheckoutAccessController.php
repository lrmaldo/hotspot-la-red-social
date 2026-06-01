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
            'skip_temp_access' => ['nullable', 'boolean'],
        ]);

        $hotspotIp = $data['hotspot_ip'] ?? null;
        $hotspotMac = $data['hotspot_mac'] ?? null;
        
        // El bypass temporal ya no se usa, confiamos en Walled Garden
        return response()->json([
            'ok' => true,
            'hotspot_ip' => $hotspotIp,
            'stripe_key' => (string) config('services.stripe.key'),
            'temp_access_skipped' => true,
        ]);
    }
}