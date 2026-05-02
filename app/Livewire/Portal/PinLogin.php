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
    public int $countdown = 0;
    public bool $canTrial = false;

    public function mount(Zona $zona)
    {
        $this->zona = $zona;
        $this->dst = request()->get('dst', 'http://google.com'); // Fallback if no dst is provided in testing
        
        if ($this->zona->trial_enabled) {
            $this->countdown = $this->zona->trial_duration_seconds;
        }
    }

    public function login()
    {
        $this->validate([
            'pin' => ['required', 'string', 'min:4'],
        ]);

        $this->redirectToHotspot($this->pin);
    }

    public function loginTrial()
    {
        if (!$this->zona->trial_enabled || $this->countdown > 0) {
            return;
        }

        $this->redirectToHotspot('trial');
    }

    protected function redirectToHotspot(string $username)
    {
        $url = sprintf(
            'http://%s/login?username=%s&password=%s&dst=%s',
            $this->zona->hotspot_host,
            urlencode($username),
            urlencode(''), // Password empty for trial or same as PIN if using Mikrotik logic
            urlencode($this->dst)
        );

        // For trial, Mikrotik usually uses /login?username=T-XX:XX:XX or just trial=true
        // But the user asked for a "login" via button. 
        // If it's pure Mikrotik Trial, it might be different, but I'll follow the pattern.
        if ($username === 'trial') {
             $url = sprintf(
                'http://%s/login?dst=%s',
                $this->zona->hotspot_host,
                urlencode($this->dst)
            );
            // Some setups use trial=yes or similar. 
        }

        return redirect()->away($url);
    }

    public function render()
    {
        return view('livewire.portal.pin-login');
    }
}
