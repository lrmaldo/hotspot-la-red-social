<div>
    <div class="mb-6 flex justify-between items-center px-4 sm:px-6 md:px-8">
        <h2 class="text-xl font-bold text-gray-800">Gestión de Zonas</h2>
        <button wire:click="create" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Agregar Zona
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden mx-4 sm:mx-6 md:mx-8 border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hotspot / Autenticación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Planes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Campañas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($zonas as $z)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 overflow-hidden bg-gray-100 rounded-full flex items-center justify-center border" style="border-color: {{ $z->color_primario }}; background-color: {{ $z->color_secundario }}">
                                        @if($z->logo_path)
                                            <img class="h-8 w-8 object-contain" src="{{ \Illuminate\Support\Facades\Storage::url($z->logo_path) }}" alt="">
                                        @else
                                            <span class="text-sm font-bold" style="color: {{ $z->color_primario }}">{{ substr($z->nombre, 0, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $z->nombre }}</div>
                                        <div class="text-sm text-gray-500" title="URL: /portal/{{ $z->id_personalizado }}">{{ $z->id_personalizado }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $z->hotspot_host }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide">{{ str_replace('_', ' ', $z->tipo_autenticacion) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-teal-100 text-teal-800">
                                    {{ $z->planes_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $z->campanas_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="toggleActive({{ $z->id }})" 
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $z->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $z->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                @livewire('admin.zonas.plan-manager', ['zona' => $z], key('plan-manager-' . $z->id))
                                <a href="{{ route('admin.zonas.mikrotik', $z->id) }}"
                                   title="Descargar Archivos MikroTik"
                                   class="text-green-600 hover:text-green-900 inline-block p-1 bg-green-50 rounded hover:bg-green-100">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </a>
                                <a href="{{ route('admin.zonas.vpn', $z->id) }}"
                                   title="VPN / Conexión MikroTik"
                                   class="text-teal-600 hover:text-teal-900 inline-block p-1 bg-teal-50 rounded hover:bg-teal-100">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" /></svg>
                                </a>
                                <a href="{{ route('portal.login', $z->id_personalizado) }}" target="_blank"
                                   title="Ver Portal"
                                   class="text-indigo-600 hover:text-indigo-900 inline-block p-1 bg-indigo-50 rounded hover:bg-indigo-100">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ route('admin.campanas') }}?zona={{ $z->id }}" 
                                   title="Ver Campañas"
                                   class="text-purple-600 hover:text-purple-900 inline-block p-1 bg-purple-50 rounded hover:bg-purple-100">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                </a>
                                <button wire:click="edit({{ $z->id }})" title="Editar" class="text-blue-600 hover:text-blue-900 inline-block p-1 bg-blue-50 rounded hover:bg-blue-100">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                                <button type="button" title="Eliminar" class="text-red-600 hover:text-red-900 inline-block p-1 bg-red-50 rounded hover:bg-red-100"
                                        x-data @click="window.Swal.fire({
                                            title: '¿Eliminar Zona?',
                                            text: 'Esta acción borrará todas sus campañas y planes. No se puede deshacer.',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#3085d6',
                                            confirmButtonText: 'Sí, eliminar',
                                            cancelButtonText: 'Cancelar'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                @this.delete({{ $z->id }});
                                            }
                                        })">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No hay zonas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-data="{ show: @entangle('showModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex flex-col justify-end sm:justify-center sm:items-center sm:p-4"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <!-- Backdrop: -z-10 asegura que el panel siempre quede por encima -->
        <div x-show="show" @click="show = false"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/60 -z-10" aria-hidden="true"></div>

        <!-- Modal Panel: bottom-sheet en móvil, centrado en desktop -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
             class="relative w-full bg-white rounded-t-2xl sm:rounded-xl shadow-xl overflow-hidden sm:max-w-2xl max-h-[92vh] flex flex-col">
                <form wire:submit="save" class="flex flex-col overflow-hidden">
                    <div class="overflow-y-auto bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            {{ $zonaId ? 'Editar Zona' : 'Nueva Zona' }}
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" wire:model.blur="nombre" class="mt-1 flex-1 block w-full rounded-md sm:text-sm border-gray-300 border py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                                @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- ID Personalizado (Slug) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID Personalizado (URL)</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">/portal/</span>
                                    <input type="text" wire:model="id_personalizado" class="flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 border py-2 px-3 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                @error('id_personalizado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Hotspot Host -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hotspot Host (IP MikroTik)</label>
                                <input type="text" wire:model="hotspot_host" class="mt-1 block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="ej. 192.168.88.1" required>
                                @error('hotspot_host') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tipo Autenticación -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo de Autenticación</label>
                                <select wire:model="tipo_autenticacion" class="mt-1 block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                    <option value="pin">Vía PIN</option>
                                    <option value="sin_autenticacion">Sin Autenticación (Paso Directo)</option>
                                </select>
                                @error('tipo_autenticacion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Logo Upload -->
                            <div class="col-span-1 md:col-span-2 flex items-center gap-4">
                                <div class="flex-1"
                                     x-data="{ uploading: false }"
                                     x-on:livewire-upload-start="uploading = true"
                                     x-on:livewire-upload-finish="uploading = false"
                                     x-on:livewire-upload-error="uploading = false">
                                    <label class="block text-sm font-medium text-gray-700">Logo (Opcional)</label>
                                    <input type="file" wire:model="logo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <div x-show="uploading" x-cloak class="text-sm text-blue-600 mt-1">Subiendo...</div>
                                    @error('logo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-16 h-16 border rounded bg-gray-100 flex items-center justify-center overflow-hidden">
                                    @if ($logo)
                                        <img src="{{ $logo->temporaryUrl() }}" class="object-cover relative h-full">
                                    @elseif($logo_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($logo_path) }}" class="object-cover relative h-full">
                                    @else
                                        <span class="text-gray-400 text-xs text-center leading-tight">Sin logo</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Colores -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Color Primario</label>
                                <div class="mt-1 flex items-center">
                                    <input type="color" wire:model.live="color_primario" class="h-8 w-8 rounded border border-gray-300 cursor-pointer">
                                    <input type="text" wire:model="color_primario" class="ml-2 block w-full rounded-md border-gray-300 border py-1.5 px-3 sm:text-sm shadow-sm">
                                </div>
                                @error('color_primario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Color Secundario (Fondo)</label>
                                <div class="mt-1 flex items-center">
                                    <input type="color" wire:model.live="color_secundario" class="h-8 w-8 rounded border border-gray-300 cursor-pointer">
                                    <input type="text" wire:model="color_secundario" class="ml-2 block w-full rounded-md border-gray-300 border py-1.5 px-3 sm:text-sm shadow-sm">
                                </div>
                                @error('color_secundario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Facebook URL -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">URL Facebook (Botón Flotante)</label>
                                <input type="url" wire:model="facebook_url" class="mt-1 block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="https://facebook.com/pagina">
                                @error('facebook_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Toggles en una fila -->
                            <div class="col-span-1 md:col-span-2 flex justify-between bg-gray-50 p-4 rounded-md border border-gray-200">
                                
                                <label class="flex items-center cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" wire:model.live="is_active" class="sr-only">
                                        <div class="block w-10 h-6 rounded-full {{ $is_active ? 'bg-blue-600' : 'bg-gray-300' }} transition-colors"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform {{ $is_active ? 'transform translate-x-4' : '' }}"></div>
                                    </div>
                                    <div class="ml-3 text-sm font-medium text-gray-700">Zona Activa</div>
                                </label>

                                <label class="flex items-center cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" wire:model.live="venta_vouchers_activa" class="sr-only">
                                        <div class="block w-10 h-6 rounded-full {{ $venta_vouchers_activa ? 'bg-blue-600' : 'bg-gray-300' }} transition-colors"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform {{ $venta_vouchers_activa ? 'transform translate-x-4' : '' }}"></div>
                                    </div>
                                    <div class="ml-3 text-sm font-medium text-gray-700">Venta Vouchers (Fase 2)</div>
                                </label>

                            </div>

                            <!-- Configuración MikroTik API (visible cuando venta vouchers activa) -->
                            <div class="col-span-1 md:col-span-2 bg-green-50 p-4 rounded-md border border-green-200" x-show="$wire.venta_vouchers_activa" x-transition>
                                <h4 class="text-sm font-bold text-green-900 mb-3">Credenciales API MikroTik</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-green-800">Usuario API</label>
                                        <input type="text" wire:model="mikrotik_user" placeholder="api_portal" class="mt-1 block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-green-500 focus:border-green-500 shadow-sm">
                                        @error('mikrotik_user') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-green-800">Contraseña API</label>
                                        <input type="password" wire:model="mikrotik_password" placeholder="{{ $zonaId ? 'Dejar vacío para no cambiar' : 'Contraseña' }}" class="mt-1 block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-green-500 focus:border-green-500 shadow-sm">
                                        @error('mikrotik_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-green-800">Puerto API</label>
                                        <input type="number" wire:model="mikrotik_port" min="1" max="65535" placeholder="8728" class="mt-1 block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-green-500 focus:border-green-500 shadow-sm">
                                        @error('mikrotik_port') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-green-800">Perfil Hotspot</label>
                                        <input type="text" wire:model="mikrotik_hotspot_profile" placeholder="default" class="mt-1 block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-green-500 focus:border-green-500 shadow-sm">
                                        @error('mikrotik_hotspot_profile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-green-800">Interfaz para tráfico (Dashboard)</label>
                                        <input type="text" wire:model="mikrotik_interface" placeholder="ej. vlan40-hs" class="mt-1 block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-green-500 focus:border-green-500 shadow-sm">
                                        <p class="mt-1 text-xs text-green-900/70">Nombre exacto de la interfaz/VLAN del router cuyo throughput se mide para esta zona. Déjalo vacío para no medir tráfico.</p>
                                        @error('mikrotik_interface') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-green-900/80">
                                    Estas credenciales quedan asociadas a esta zona. Si activas venta de vouchers, Usuario API es obligatorio.
                                </p>
                                @if($zonaId)
                                    <div class="mt-3">
                                        <button type="button" wire:click="probarConexion({{ $zonaId }})"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-green-800 bg-green-200 rounded-md hover:bg-green-300 transition">
                                            <svg wire:loading.remove wire:target="probarConexion" class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            <svg wire:loading wire:target="probarConexion" class="animate-spin h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Probar conexión
                                        </button>
                                        @if(session('success'))
                                            <span class="ml-2 text-sm text-green-700 font-medium">{{ session('success') }}</span>
                                        @endif
                                        @if(session('error'))
                                            <span class="ml-2 text-sm text-red-600 font-medium">{{ session('error') }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Configuración Trial -->
                            <div class="col-span-1 md:col-span-2 bg-blue-50 p-4 rounded-md border border-blue-200">
                                <div class="flex items-center justify-between mb-4">
                                    <label class="flex items-center cursor-pointer">
                                        <div class="relative">
                                            <input type="checkbox" wire:model.live="trial_enabled" class="sr-only">
                                            <div class="block w-10 h-6 rounded-full {{ $trial_enabled ? 'bg-green-600' : 'bg-gray-300' }} transition-colors"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform {{ $trial_enabled ? 'transform translate-x-4' : '' }}"></div>
                                        </div>
                                        <div class="ml-3 text-sm font-bold text-blue-900">Habilitar Botón de Prueba (Trial)</div>
                                    </label>
                                </div>

                                <div x-show="$wire.trial_enabled" x-transition>
                                    <label class="block text-sm font-medium text-blue-800">Segundos de Cuenta Regresiva</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <input type="number" wire:model="trial_duration_seconds" min="0" max="60" class="block w-24 rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                        <span class="text-sm text-blue-600">segundos de espera antes de permitir la conexión gratuita.</span>
                                    </div>
                                    @error('trial_duration_seconds') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Notas / Descripción (Interna)</label>
                                <textarea wire:model="descripcion" rows="2" class="mt-1 block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0 bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Guardar Zona
                            <svg wire:loading wire:target="save" style="display:none" class="animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                        <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>
