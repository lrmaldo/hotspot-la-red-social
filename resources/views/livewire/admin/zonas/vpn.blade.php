<div class="px-4 sm:px-6 md:px-8 pb-10">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">VPN MikroTik — {{ $zona->nombre }}</h2>
            <p class="text-sm text-gray-500">Conecta el router de esta zona al VPS por L2TP/IPsec para habilitar el API.</p>
        </div>
        <a href="{{ route('admin.zonas') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Volver a Zonas</a>
    </div>

    @if($errorMsg)
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-700">
            {{ $errorMsg }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Configuración del servidor VPN --}}
        <div class="lg:col-span-1 bg-white shadow rounded-lg border border-gray-100 p-5">
            <h3 class="text-base font-semibold text-gray-800 mb-1">Servidor VPN</h3>
            <p class="text-xs text-gray-500 mb-4">Datos del VPS. El PSK lo obtienes en el servidor (<code>/root/.lrswifi-vpn-credentials</code>).</p>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">IP pública del VPS</label>
                    <input type="text" wire:model="vpn_public_ip" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('vpn_public_ip') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">PSK (IPsec)</label>
                    <input type="text" wire:model="vpn_psk" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('vpn_psk') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">IP servidor (túnel)</label>
                        <input type="text" wire:model="vpn_server_ip" class="w-full rounded-md border-gray-300 text-sm shadow-sm">
                        @error('vpn_server_ip') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Pool inicio</label>
                        <input type="text" wire:model="vpn_pool_start" class="w-full rounded-md border-gray-300 text-sm shadow-sm">
                        @error('vpn_pool_start') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Pool fin</label>
                    <input type="text" wire:model="vpn_pool_end" class="w-full rounded-md border-gray-300 text-sm shadow-sm">
                    @error('vpn_pool_end') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <button wire:click="guardarConfig" wire:loading.attr="disabled"
                    class="w-full bg-gray-800 text-white py-2 rounded-md text-sm hover:bg-gray-900 transition">
                    Guardar configuración
                </button>
            </div>
        </div>

        {{-- Estado y provisión de la zona --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold text-gray-800">Cuenta L2TP de la zona</h3>
                    @if($zona->vpn_provisioned_at)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Provisionada</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">Sin provisionar</span>
                    @endif
                </div>

                @if($zona->vpn_provisioned_at)
                    <dl class="grid grid-cols-2 gap-3 text-sm mb-4">
                        <div><dt class="text-gray-500">Usuario L2TP</dt><dd class="font-mono text-gray-900">{{ $zona->vpn_l2tp_user }}</dd></div>
                        <div><dt class="text-gray-500">Contraseña L2TP</dt><dd class="font-mono text-gray-900">{{ $zona->vpn_l2tp_password }}</dd></div>
                        <div><dt class="text-gray-500">IP del túnel (API)</dt><dd class="font-mono text-gray-900">{{ $zona->vpn_tunnel_ip }}</dd></div>
                        <div><dt class="text-gray-500">Provisionada</dt><dd class="text-gray-900">{{ $zona->vpn_provisioned_at->format('d/m/Y H:i') }}</dd></div>
                    </dl>
                    <div class="flex gap-2">
                        <button wire:click="provisionar" wire:loading.attr="disabled"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition">
                            Regenerar credenciales
                        </button>
                        <button wire:click="desprovisionar" wire:loading.attr="disabled"
                            wire:confirm="¿Eliminar la cuenta VPN de esta zona? El router dejará de conectarse."
                            class="bg-red-50 text-red-700 border border-red-200 px-4 py-2 rounded-md text-sm hover:bg-red-100 transition">
                            Eliminar cuenta VPN
                        </button>
                    </div>
                @else
                    <p class="text-sm text-gray-600 mb-4">Genera una cuenta L2TP con IP fija para esta zona. Se registrará en el VPS y el <code>hotspot_host</code> se ajustará automáticamente a la IP del túnel.</p>
                    <button wire:click="provisionar" wire:loading.attr="disabled"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition flex items-center">
                        <span wire:loading.remove wire:target="provisionar">Provisionar VPN para esta zona</span>
                        <span wire:loading wire:target="provisionar">Provisionando…</span>
                    </button>
                @endif
            </div>

            {{-- Script para el MikroTik --}}
            @if($mikrotikScript)
                <div class="bg-white shadow rounded-lg border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-800">Comandos para el MikroTik</h3>
                        <button onclick="copiarScriptVpn()"
                            class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-md transition">
                            Copiar
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mb-3">Pega este bloque en una terminal del MikroTik (Winbox → New Terminal, o SSH).</p>
                    <pre id="vpn-script" class="bg-gray-900 text-gray-100 text-xs rounded-md p-4 overflow-x-auto whitespace-pre">{{ $mikrotikScript }}</pre>
                </div>
            @endif
        </div>
    </div>

    @script
    <script>
        window.copiarScriptVpn = function () {
            const el = document.getElementById('vpn-script');
            if (!el) return;
            navigator.clipboard.writeText(el.innerText).then(() => {
                window.dispatchEvent(new CustomEvent('vpn-toast', { detail: 'Comandos copiados al portapapeles' }));
            });
        };
        $wire.on('vpn-provisioned', () => alert('VPN provisionada para la zona.'));
        $wire.on('vpn-deprovisioned', () => alert('Cuenta VPN eliminada.'));
        $wire.on('vpn-config-saved', () => alert('Configuración del servidor VPN guardada.'));
        window.addEventListener('vpn-toast', (e) => console.log(e.detail));
    </script>
    @endscript
</div>
