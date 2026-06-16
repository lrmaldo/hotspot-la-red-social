<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Zonas;

use App\Models\Zona;
use App\Services\VpnL2tpService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('VPN MikroTik')]
class Vpn extends Component
{
    public Zona $zona;

    // Config del servidor VPN (editable)
    public string $vpn_public_ip = '';
    public string $vpn_psk = '';
    public string $vpn_server_ip = '';
    public string $vpn_pool_start = '';
    public string $vpn_pool_end = '';

    public ?string $mikrotikScript = null;
    public ?string $errorMsg = null;

    public function mount(Zona $zona, VpnL2tpService $service): void
    {
        $this->zona = $zona;
        $cfg = $service->serverConfig();
        $this->vpn_public_ip = $cfg['public_ip'];
        $this->vpn_psk = $cfg['psk'];
        $this->vpn_server_ip = $cfg['server_ip'];
        $this->vpn_pool_start = $cfg['pool_start'];
        $this->vpn_pool_end = $cfg['pool_end'];

        if ($this->zona->vpn_provisioned_at) {
            $this->mikrotikScript = $service->mikrotikScript($this->zona);
        }
    }

    public function guardarConfig(VpnL2tpService $service): void
    {
        $this->validate([
            'vpn_public_ip' => 'required|ip',
            'vpn_psk' => 'required|string|min:8|max:128',
            'vpn_server_ip' => 'required|ip',
            'vpn_pool_start' => 'required|ip',
            'vpn_pool_end' => 'required|ip',
        ]);

        $service->saveServerConfig(
            $this->vpn_public_ip,
            $this->vpn_psk,
            $this->vpn_server_ip,
            $this->vpn_pool_start,
            $this->vpn_pool_end,
        );

        $this->dispatch('vpn-config-saved');
    }

    public function provisionar(VpnL2tpService $service): void
    {
        $this->errorMsg = null;

        try {
            $service->provision($this->zona);
            $this->zona->refresh();
            $this->mikrotikScript = $service->mikrotikScript($this->zona);
            $this->dispatch('vpn-provisioned');
        } catch (\Throwable $e) {
            $this->errorMsg = $e->getMessage();
        }
    }

    public function desprovisionar(VpnL2tpService $service): void
    {
        $this->errorMsg = null;

        try {
            $service->deprovision($this->zona);
            $this->zona->refresh();
            $this->mikrotikScript = null;
            $this->dispatch('vpn-deprovisioned');
        } catch (\Throwable $e) {
            $this->errorMsg = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.admin.zonas.vpn');
    }
}
