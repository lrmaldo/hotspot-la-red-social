<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Zona;
use Livewire\Component;

class PinLogin extends Component
{
    public Zona $zona;
    public string $pin = '';
    public string $dst = '';

    public function mount(Zona $zona)
    {
        $this->zona = $zona;
        $this->dst = request()->get('dst', 'http://google.com'); // Fallback if no dst is provided in testing
    }

    public function login()
    {
        $this->validate([
            'pin' => ['required', 'string', 'min:4'],
        ]);

        $url = sprintf(
            'http://%s/login?username=%s&password=%s&dst=%s',
            $this->zona->hotspot_host,
            urlencode($this->pin),
            urlencode($this->pin),
            urlencode($this->dst)
        );

        return redirect()->away($url);
    }

    public function render()
    {
        return view('livewire.portal.pin-login');
    }
}
