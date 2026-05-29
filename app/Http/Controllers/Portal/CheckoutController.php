<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Voucher;
use App\Models\Zona;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __invoke(Request $request, Zona $zona): RedirectResponse
    {
        if (! $zona->is_active || ! $zona->venta_vouchers_activa) {
            abort(404);
        }

        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:planes,id'],
            'compra_email' => ['nullable', 'email'],
            'compra_nombre' => ['nullable', 'string', 'max:120'],
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

        try {
            $url = (new StripeService())->crearSesionCheckout($plan, $zona, $voucher);
        } catch (\Throwable $e) {
            Log::error('Checkout Stripe: no se pudo crear sesion', [
                'zona_id' => $zona->id,
                'plan_id' => $plan->id,
                'voucher_id' => $voucher->id,
                'error' => $e->getMessage(),
            ]);

            $voucher->update(['estado' => 'pendiente']);

            return redirect()->to(route('portal.zona', $zona) . '?checkout=error');
        }

        return redirect()->away($url);
    }
}
