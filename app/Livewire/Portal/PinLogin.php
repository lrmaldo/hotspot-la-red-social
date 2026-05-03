<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Zona;
use Livewire\Component;

class PinLogin extends Component
{
    public Zona $zona;
    public string $pin = '';
    public int $countdown = 0;
    public bool $canTrial = false;

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

    // Flag para mostrar el formulario final
    public bool $readyToConnect = false;
    public string $mikrotikUsername = '';
    public string $mikrotikPassword = '';

    public function mount(Zona $zona)
    {
        $this->zona = $zona;
        
        if ($this->zona->trial_enabled) {
            $this->countdown = $this->zona->trial_duration_seconds;
        }
    }

    public function login()
    {
        $this->validate([
            'pin' => ['required', 'string', 'min:4'],
        ]);

        $this->prepareHotspotLogin($this->pin, $this->pin); // Usamos el PIN como usuario y contraseña
    }

    public function loginTrial()
    {
        if (!$this->zona->trial_enabled || $this->countdown > 0) {
            return;
        }

        // El trial de MikroTik habitualmente es MAC Address o "T-"
        $trialUser = 'T-' . ($this->mac_esc ?? '');
        $this->prepareHotspotLogin($trialUser, '');
    }

    protected function prepareHotspotLogin(string $user, string $pass)
    {
        $this->mikrotikUsername = $user;
        
        // Si hay CHAP-ID y CHAP-CHALLENGE, preparamos el password tipo CHAP.
        // Si no, mandamos el texto plano (requiere http-pap o mac-cookie)
        if (!empty($this->chap_id) && !empty($this->chap_challenge)) {
             // El chap-challenge que envía MikroTik viene en hexadecimal. Hay que pasarlo a binario.
             // El hash MD5 se calcula sobre: \0 + password + binary(chap_challenge)
             // Nota: En PHP, pack('H*', $hex) lo pasa a binario.
             // Sin embargo, para evitar problemas de codificación de PHP a MikroTik, 
             // lo más seguro es preparar las variables y hacer que un formulario oculto en la vista
             // ejecute el JS hexMD5() original de MikroTik como se solicitó.
             $this->mikrotikPassword = $pass;
             $this->readyToConnect = true;
        } else {
             $this->mikrotikPassword = $pass;
             $this->readyToConnect = true;
        }
    }

    public function render()
    {
        return view('livewire.portal.pin-login');
    }
}
