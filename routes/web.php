<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Zonas\Index as ZonasIndex;
use App\Livewire\Admin\Campanas\Index as CampanasIndex;
use App\Livewire\Admin\Configuracion\Index as ConfiguracionIndex;
use App\Livewire\Portal\CarruselCampanas;
use App\Livewire\Portal\PinLogin;

Route::get('/zona-no-encontrada', function () {
    return view('zona-no-encontrada');
})->name('zona.not-found');

// Portal Hotspot - Mikrotik Redirection
Route::match(['get', 'post'], '/portal/{zona:id_personalizado}', CarruselCampanas::class)
    ->name('portal.login')
    ->missing(function () {
        return redirect()->route('zona.not-found');
    });

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de Administración
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('/admin/dashboard', 'dashboard')->name('admin.dashboard');
    
    Route::prefix('admin')->group(function() {
        Route::get('/zonas', ZonasIndex::class)->name('admin.zonas');
        Route::get('/zonas/{zona}/mikrotik', [\App\Http\Controllers\Admin\MikrotikDownloadController::class, 'download'])->name('admin.zonas.mikrotik');
        Route::get('/campanas', CampanasIndex::class)->name('admin.campanas');
        Route::get('/configuracion', ConfiguracionIndex::class)->name('admin.configuracion');
    });
});

require __DIR__.'/settings.php';
