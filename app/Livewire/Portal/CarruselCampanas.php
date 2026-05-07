<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Zona;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

use Livewire\Attributes\Url;

#[Layout('layouts.portal')]
class CarruselCampanas extends Component
{
    public Zona $zona;
    public $campanas = [];
    public int $currentIndex = 0;
    public bool $finished = false;

    public string $displayMode = 'carrusel';
    public ?\App\Models\Campana $activeVideo = null;

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

        // Si se recibe por POST (o GET), capturamos los parámetros de MikroTik
        $this->mac = $request->input('mac', $this->mac);
        $this->ip = $request->input('ip', $this->ip);
        $this->username = $request->input('username', $this->username);
        $this->link_login = $request->input('link-login', $this->link_login);
        $this->link_orig = $request->input('link-orig', $this->link_orig);
        $this->error = $request->input('error', $this->error);
        $this->chap_id = $request->input('chap-id', $this->chap_id);
        $this->chap_challenge = $request->input('chap-challenge', $this->chap_challenge);
        $this->link_login_only = $request->input('link-login-only', $this->link_login_only);
        $this->link_orig_esc = $request->input('link-orig-esc', $this->link_orig_esc);
        $this->mac_esc = $request->input('mac-esc', $this->mac_esc);

        $this->zona = $zona;
        
        $allCampanas = $this->zona->campanas()->where('is_active', true)->get();
        $videos = $allCampanas->where('tipo', 'video');
        $imagenes = $allCampanas->where('tipo', 'imagen');

        if ($videos->isNotEmpty() && $imagenes->isNotEmpty()) {
            $this->displayMode = (rand(0, 1) === 1) ? 'video' : 'carrusel';
        } elseif ($videos->isNotEmpty()) {
            $this->displayMode = 'video';
        } elseif ($imagenes->isNotEmpty()) {
            $this->displayMode = 'carrusel';
        } else {
            $this->displayMode = 'none';
        }

        if ($this->displayMode === 'video') {
            $this->activeVideo = $videos->random(); // Elige 1 solo video aleatorio
            $this->campanas = collect(); // Vaciamos para no romper el array
        } elseif ($this->displayMode === 'carrusel') {
            $this->campanas = $imagenes->shuffle(); // Mezcla las imágenes aleatoriamente
        }

        if (empty($this->campanas) && !$this->activeVideo) {
            $this->finished = true;
        }
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
