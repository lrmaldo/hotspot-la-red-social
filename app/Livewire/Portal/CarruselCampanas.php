<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Plan;
use App\Models\Voucher;
use App\Models\Zona;
use App\Services\StripeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
class CarruselCampanas extends Component
{
    public Zona $zona;
    public $campanas = [];
    public int $currentIndex = 0;
    public bool $finished = false;

    public string $displayMode = 'carrusel';
    public ?\App\Models\Campana $activeVideo = null;

    // Compra de vouchers
    public Collection $planes;
    public bool $mostrarCompra = false;
    public int $pasoCompra = 1;
    public ?int $planId = null;
    public string $compraNombre = '';
    public string $compraEmail = '';
    public bool $compraCargando = false;

    // Parámetros de MikroTik
    public ?string $mac = null;
    public ?string $ip = null;
    public ?string $username = null;
    public ?string $link_login = null;
    public ?string $link_orig = null;
    public ?string $error = null;
    public ?string $chap_id = null;
    public ?string $chap_challenge = null;
    public ?string $link_login_only = null;
    public ?string $link_orig_esc = null;
    public ?string $mac_esc = null;

    public function mount(Zona $zona, \Illuminate\Http\Request $request)
    {
        if (!$zona->is_active) {
            abort(404);
        }
        #dd($request->all());
        // Si se recibe por POST (o GET), capturamos los parámetros de MikroTik.
        // Si no llegan, usamos el último estado válido guardado en sesión.
        $this->mac = $request->input('mac') ?: $request->session()->get('hotspot.mac', $this->mac);
        $this->ip = $request->input('ip') ?: $request->session()->get('hotspot.ip', $this->ip);
        $this->username = $request->input('username') ?: $request->session()->get('hotspot.username', $this->username);
        $this->link_login = $request->input('link-login') ?: $request->session()->get('hotspot.link_login', $this->link_login);
        $this->link_orig = $request->input('link-orig') ?: $request->session()->get('hotspot.link_orig', $this->link_orig);
        $this->error = $request->input('error', $this->error);
        $this->chap_id = $request->input('chap-id') ?: $request->session()->get('hotspot.chap_id', $this->chap_id);
        $this->chap_challenge = $request->input('chap-challenge') ?: $request->session()->get('hotspot.chap_challenge', $this->chap_challenge);
        $this->link_login_only = $request->input('link-login-only') ?: $request->session()->get('hotspot.link_login_only', $this->link_login_only);
        $this->link_orig_esc = $request->input('link-orig-esc') ?: $request->session()->get('hotspot.link_orig_esc', $this->link_orig_esc);
        $this->mac_esc = $request->input('mac-esc') ?: $request->session()->get('hotspot.mac_esc', $this->mac_esc);

        if ($this->ip) {
            $request->session()->put('hotspot.ip', $this->ip);
        }
        if ($this->mac) {
            $request->session()->put('hotspot.mac', $this->mac);
        }
        if ($this->username) {
            $request->session()->put('hotspot.username', $this->username);
        }
        if ($this->link_login) {
            $request->session()->put('hotspot.link_login', $this->link_login);
        }
        if ($this->link_orig) {
            $request->session()->put('hotspot.link_orig', $this->link_orig);
        }
        if ($this->chap_id) {
            $request->session()->put('hotspot.chap_id', $this->chap_id);
        }
        if ($this->chap_challenge) {
            $request->session()->put('hotspot.chap_challenge', $this->chap_challenge);
        }
        if ($this->link_login_only) {
            $request->session()->put('hotspot.link_login_only', $this->link_login_only);
        }
        if ($this->link_orig_esc) {
            $request->session()->put('hotspot.link_orig_esc', $this->link_orig_esc);
        }
        if ($this->mac_esc) {
            $request->session()->put('hotspot.mac_esc', $this->mac_esc);
        }

        $this->zona = $zona;

        // Cargar planes si venta activa
        $this->planes = $zona->venta_vouchers_activa
            ? $zona->planes()->activos()->orderBy('precio')->get()
            : collect();

        $allCampanas = $this->zona->campanas()->where('is_active', true)->get();
        $videos = $allCampanas->where('tipo', 'video');
        $imagenes = $allCampanas->where('tipo', 'imagen');

        // Por defecto preparamos ambos
        $this->activeVideo = $videos->isNotEmpty() ? $videos->random() : null;
        $this->campanas = $imagenes->isNotEmpty() ? $imagenes->shuffle() : collect();

        // El blade ahora puede tener ambos. 
        // Si hay video, la lógica de "Internet Gratis" se basará en él.
        // Mientras tanto, se muestra el carrusel de imágenes (banners por defecto).
        $this->displayMode = 'mixto';
    }

    public function abrirCompra(): void
    {
        $this->mostrarCompra = true;
        $this->pasoCompra = 1;
        $this->planId = null;
        $this->dispatch('abrir-compra');
    }

    public function cerrarCompra(): void
    {
        $this->mostrarCompra = false;
        $this->pasoCompra = 1;
        $this->planId = null;
    }

    public function seleccionarPlan(int $planId): void
    {
        Log::info('seleccionarPlan ejecutado', [
            'planId' => $planId,
        ]);

        $this->planId = $planId;
        $this->pasoCompra = 3;
    }

    public function iniciarPago(): void
    {
        Log::info("valores recibidos para iniciar pago", [
            'planId' => $this->planId,
            'compraEmail' => $this->compraEmail,
        ]);

        $this->validate([
            'planId' => ['required', 'exists:planes,id'],
            'compraEmail' => ['nullable', 'email'],
        ]);

        $plan = Plan::findOrFail($this->planId);

        $voucher = Voucher::create([
            'zona_id'          => $this->zona->id,
            'plan_id'          => $plan->id,
            'codigo'           => Voucher::generarCodigo(),
            'estado'           => 'pendiente',
            'comprador_nombre' => $this->compraNombre ?: null,
            'comprador_email'  => $this->compraEmail ?: null,
        ]);

        $url = (new StripeService())->crearSesionCheckout($plan, $this->zona, $voucher);

        $this->compraCargando = true;
        $this->redirect($url);
    }

    #[Title('Bienvenido al Portal')]
    public function render()
    {
        // Provide the variable to the layout
        view()->share('zona', $this->zona);

        return view('livewire.portal.carrusel-campanas');
    }

    public function nextSlide()
    {
        if ($this->currentIndex < count($this->campanas) - 1) {
            $this->currentIndex++;
        } else {
            $this->finished = true;
        }
    }

    public function prevSlide()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }
}
