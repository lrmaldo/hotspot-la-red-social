<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Campanas\Index as CampanasIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Configuracion\Index as ConfiguracionIndex;
use App\Livewire\Admin\Planes\Index as PlanesIndex;
use App\Livewire\Admin\Vouchers\Index as VouchersIndex;
use App\Livewire\Admin\Zonas\Index as ZonasIndex;
use App\Livewire\Admin\Zonas\Vpn as ZonasVpn;
use App\Livewire\Portal\CarruselCampanas;
use App\Livewire\Portal\CompraPlan;
use App\Livewire\Portal\PagoExitoso;
use App\Livewire\Portal\PinLogin;
use App\Http\Controllers\Portal\CheckoutAccessController;
use App\Http\Controllers\Portal\CheckoutController;
use App\Http\Controllers\Portal\CheckoutIntentController;

Route::get('/zona-no-encontrada', function () {
    return view('zona-no-encontrada');
})->name('zona.not-found');

// Portal Hotspot - Mikrotik Redirection
Route::match(['get', 'post'], '/portal/{zona:id_personalizado}', CarruselCampanas::class)
    ->name('portal.login')
    ->missing(function () {
        return redirect()->route('zona.not-found');
    });

// Portal Phase 2 — Voucher purchase
Route::get('/portal/{zona:id_personalizado}/comprar', CompraPlan::class)
    ->name('portal.comprar');

Route::get('/portal/{zona:id_personalizado}/pago-exitoso', PagoExitoso::class)
    ->name('portal.pago-exitoso');

Route::post('/portal/{zona:id_personalizado}/checkout', CheckoutController::class)
    ->name('portal.checkout');

Route::post('/portal/{zona:id_personalizado}/checkout-intent', CheckoutIntentController::class)
    ->name('portal.checkout-intent');

Route::post('/portal/{zona:id_personalizado}/checkout-access', CheckoutAccessController::class)
    ->name('portal.checkout-access');

// Alias for portal.zona (used in emails/views)
Route::get('/portal/{zona:id_personalizado}/zona', CarruselCampanas::class)
    ->name('portal.zona');

// Stripe Webhook
Route::post('/webhook/stripe', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])
    ->name('webhook.stripe');

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de Administración
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');
    
    Route::prefix('admin')->group(function() {
        Route::get('/zonas', ZonasIndex::class)->name('admin.zonas');
        Route::get('/zonas/{zona}/vpn', ZonasVpn::class)->name('admin.zonas.vpn');
        Route::get('/zonas/{zona}/mikrotik', [\App\Http\Controllers\Admin\MikrotikDownloadController::class, 'download'])->name('admin.zonas.mikrotik');
        Route::get('/campanas', CampanasIndex::class)->name('admin.campanas');
        Route::get('/planes', PlanesIndex::class)->name('admin.planes');
        Route::get('/vouchers', VouchersIndex::class)->name('admin.vouchers');
        Route::get('/configuracion', ConfiguracionIndex::class)->name('admin.configuracion');
        Route::get('/users', UsersIndex::class)->name('admin.users');
    });
});

require __DIR__.'/settings.php';
