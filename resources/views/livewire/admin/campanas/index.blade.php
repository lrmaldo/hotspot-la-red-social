<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center px-4 sm:px-6 md:px-8 gap-4">
        <h2 class="text-xl font-bold text-gray-800">Gestión de Campañas</h2>
        
        <div class="flex items-center gap-4">
            <!-- Filter by Zona -->
            <select wire:model.live="filterZona" class="rounded-md border-gray-300 border py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm">
                <option value="">Todas las Zonas</option>
                @foreach($zonas as $zona)
                    <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                @endforeach
            </select>

            <button wire:click="create" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center transition text-sm">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden mx-4 sm:mx-6 md:mx-8 border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Media</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detalles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zona</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Orden</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($campanas as $c)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Preview Preview -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-16 w-24 bg-gray-100 rounded overflow-hidden flex items-center justify-center relative shadow-sm border border-gray-200">
                                    @if($c->tipo === 'imagen')
                                        <img src="{{ str_starts_with($c->file_path, 'http') ? $c->file_path : \Illuminate\Support\Facades\Storage::url($c->file_path) }}" class="object-cover h-full w-full">
                                    @else
                                        <!-- Video icon -->
                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    @endif
                                    
                                    <span class="absolute top-1 left-1 bg-black/60 text-white text-[10px] px-1 rounded-sm backdrop-blur uppercase">
                                        {{ $c->tipo }}
                                    </span>
                                </div>
                            </td>
                            
                            <!-- Details -->
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $c->titulo ?: '(Sin título)' }}</div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-3">
                                    <span class="flex items-center" title="Duración">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $c->duracion }}s
                                    </span>
                                    @if($c->tipo === 'video' && !is_null($c->skip_after_seconds))
                                        <span class="flex items-center text-blue-600" title="Botón Omitir">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                            Skips: {{ $c->skip_after_seconds }}s
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Zona -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $c->zona->nombre }}</div>
                                <div class="text-xs text-gray-500">{{ $c->zona->id_personalizado }}</div>
                            </td>

                            <!-- Prioridad / Orden -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center justify-center space-y-1">
                                    <button wire:click="moveUp({{ $c->id }})" class="text-gray-400 hover:text-blue-600 transition" title="Subir / Mayor prioridad" {{ $c->prioridad === 0 ? 'disabled' : '' }}>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                    </button>
                                    <span class="text-xs font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded shadow-sm">{{ $c->prioridad }}</span>
                                    <button wire:click="moveDown({{ $c->id }})" class="text-gray-400 hover:text-blue-600 transition" title="Bajar / Menor prioridad">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                </div>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="toggleActive({{ $c->id }})" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $c->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $c->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </td>

                            <!-- Extra actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button wire:click="edit({{ $c->id }})" title="Editar" class="text-blue-600 hover:text-blue-900 inline-block p-1 bg-blue-50 rounded hover:bg-blue-100">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                                <button type="button" title="Eliminar" class="text-red-600 hover:text-red-900 inline-block p-1 bg-red-50 rounded hover:bg-red-100"
                                        x-data @click="window.Swal.fire({
                                            title: '¿Eliminar Campaña?',
                                            text: 'Esta acción no se puede deshacer y borrará el archivo multimedia.',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#3085d6',
                                            confirmButtonText: 'Sí, eliminar',
                                            cancelButtonText: 'Cancelar'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                @this.delete({{ $c->id }});
                                            }
                                        })">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                @if($filterZona)
                                    No hay campañas en esta zona.
                                @else
                                    No hay campañas registradas. Selecciona una zona y crea la primera.
                                @endif
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

        <!-- Backdrop -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/60 -z-10"
             @click="show = false"></div>

            <!-- Modal Panel: bottom-sheet en móvil, centrado en desktop -->
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                 class="relative w-full bg-white rounded-t-2xl sm:rounded-xl shadow-xl overflow-hidden sm:max-w-3xl max-h-[92vh] flex flex-col">
                <form wire:submit="save" class="flex flex-col overflow-hidden">
                    <div class="overflow-y-auto px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            {{ $campanaId ? 'Editar Campaña' : 'Nueva Campaña' }}
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Zona -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Zona Destino</label>
                                <select wire:model="zona_id" class="mt-1 block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                                    <option value="" disabled>Selecciona una zona...</option>
                                    @foreach($zonas as $zona)
                                        <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('zona_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Título -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Título (Uso interno)</label>
                                <input type="text" wire:model.blur="titulo" class="mt-1 flex-1 block w-full rounded-md sm:text-sm border-gray-300 border py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                @error('titulo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Upload Media -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Archivo (Imagen o Video)</label>
                                {{-- x-on:livewire-upload-* es la forma correcta en Livewire v3 para detectar uploads --}}
                                {{-- wire:loading wire:target="file" se dispara incorrectamente al hacer reset() --}}
                                <div class="mt-1 border-2 border-gray-300 border-dashed rounded-md bg-gray-50 hover:bg-gray-100 transition"
                                     x-data="{ uploading: false, progress: 0 }"
                                     x-on:livewire-upload-start="uploading = true"
                                     x-on:livewire-upload-finish="uploading = false; progress = 0"
                                     x-on:livewire-upload-error="uploading = false; progress = 0"
                                     x-on:livewire-upload-progress="progress = $event.detail.progress">

                                    {{-- Estado normal: selector de archivo --}}
                                    <div x-show="!uploading" class="flex items-center justify-center px-6 pt-5 pb-6">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 border border-gray-300 px-3 py-1 shadow-sm">
                                                    <span>Seleccionar archivo</span>
                                                    <input id="file-upload" type="file" wire:model="file" accept="image/jpeg,image/png,image/webp,video/mp4" class="sr-only">
                                                </label>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, WEBP, MP4 hasta 50MB</p>
                                        </div>
                                    </div>

                                    {{-- Estado subiendo: spinner + barra de progreso --}}
                                    <div x-show="uploading" class="w-full text-center py-6 px-4">
                                        <svg class="animate-spin mx-auto h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-blue-600 font-semibold">Subiendo archivo... <span x-text="progress + '%'"></span></p>
                                        <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                                        </div>
                                    </div>
                                </div>
                                @error('file') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror

                                <!-- Preview current / temp file -->
                                @if($file || $file_path)
                                <div class="mt-4 flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-12 w-16 bg-black rounded overflow-hidden flex items-center justify-center shadow-sm">
                                            @if($file)
                                                @if(str_starts_with($file->getMimeType() ?? '', 'video'))
                                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                                                @else
                                                    <img src="{{ $file->temporaryUrl() }}" class="object-cover h-full w-full">
                                                @endif
                                            @elseif($file_path)
                                                @if($tipo === 'video')
                                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                                                @else
                                                    <img src="{{ str_starts_with($file_path, 'http') ? $file_path : \Illuminate\Support\Facades\Storage::url($file_path) }}" class="object-cover h-full w-full">
                                                @endif
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-blue-900 border px-2 py-0.5 rounded-md bg-white inline-block">Tipo: {{ strtoupper($tipo) }} detectado</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Duración -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duración base (Segundos)</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" wire:model="duracion" class="flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300 border py-2 px-3 focus:ring-blue-500 focus:border-blue-500" required>
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">segs</span>
                                </div>
                                @error('duracion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Omitir después de -->
                            @if($tipo === 'video')
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Botón "Omitir" después de (opcional)</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" wire:model="skip_after_seconds" placeholder="Dejar vacío si no se omite" class="flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300 border py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">segs</span>
                                </div>
                                @error('skip_after_seconds') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Texto del botón Omitir (use {s} para contador)</label>
                                <input type="text" wire:model="skip_texto" class="mt-1 block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                                @error('skip_texto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            @endif

                            <!-- Countdown config -->
                            <div class="col-span-1 md:col-span-2 border-t pt-4 mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center justify-between col-span-1 bg-gray-50 p-3 rounded border border-gray-200">
                                    <span class="text-sm font-medium text-gray-700">Mostrar indicador de tiempo</span>
                                    <label class="flex items-center cursor-pointer relative">
                                        <input type="checkbox" wire:model="countdown_visible" class="sr-only">
                                        <div class="block bg-gray-300 w-10 h-6 rounded-full {{ $countdown_visible ? 'bg-blue-600' : '' }} transition-colors"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform {{ $countdown_visible ? 'transform translate-x-4' : '' }}"></div>
                                    </label>
                                </div>

                                @if($countdown_visible)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Estilo de temporizador</label>
                                    <select wire:model="countdown_style" class="block w-full rounded-md border-gray-300 border py-2 px-3 sm:text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                        <option value="barra">Barra superior horizontal</option>
                                        <option value="circular">Contador numérico (Badge)</option>
                                    </select>
                                </div>
                                @endif
                            </div>

                            <!-- Prioridad y Activación -->
                            <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Prioridad (Orden visual)</label>
                                    <input type="number" wire:model="prioridad" class="mt-1 flex-1 block w-full rounded-md sm:text-sm border-gray-300 border py-2 px-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                                    <span class="text-xs text-gray-500">Los valores más bajos (0) aparecen primero.</span>
                                </div>

                                <div class="flex items-center justify-end h-full">
                                    <label class="flex items-center cursor-pointer border p-3 rounded-md {{ $is_active ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }} w-full justify-between mt-6 md:mt-0 transition-colors">
                                        <span class="text-sm font-bold {{ $is_active ? 'text-green-700' : 'text-gray-500' }}">Campaña Activa</span>
                                        <div class="relative">
                                            <input type="checkbox" wire:model="is_active" class="sr-only">
                                            <div class="block bg-gray-300 w-10 h-6 rounded-full {{ $is_active ? 'bg-green-500' : '' }} transition-colors"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform {{ $is_active ? 'transform translate-x-4' : '' }}"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition">
                            Guardar Campaña
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
