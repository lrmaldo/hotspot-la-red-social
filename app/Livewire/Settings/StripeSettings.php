<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;

class StripeSettings extends Component
{
    public $stripe_key;
    public $stripe_secret;

    public function mount()
    {
        $setting = Setting::first() ?? new Setting();
        $this->stripe_key = $setting->stripe_key;
        $this->stripe_secret = $setting->stripe_secret;
    }

    public function save()
    {
        $this->validate([
            'stripe_key' => 'nullable|string',
            'stripe_secret' => 'nullable|string',
        ]);

        $setting = Setting::firstOrCreate([]);
        $setting->update([
            'stripe_key' => $this->stripe_key,
            'stripe_secret' => $this->stripe_secret,
        ]);

        session()->flash('message', 'Stripe settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.settings.stripe-settings');
    }
}
