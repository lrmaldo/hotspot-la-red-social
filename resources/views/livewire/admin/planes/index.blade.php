<div>
    <div class="mb-6 flex justify-between items-center px-4 sm:px-6 md:px-8">
        <h2 class="text-xl font-bold text-gray-800">Gestión de Planes</h2>
        <button wire:click="create" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Agregar Plan
        </button>
    </div>

    {{-- Zone filter --}}
    <div class="mb-4 px-4 sm:px-6 md:px-8">
        <select wire:model.live="zonaFiltro" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Todas las zonas</option>
            @foreach($zonas as $z)
                <option value="{{ $z->id }}">{{ $z->nombre }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white shadow rounded-lg overflow-hidden mx-4 sm:mx-6 md:mx-8 border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zona</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duración</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Vendidos</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Activo</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($planes as $plan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $plan->zona->nombre }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $plan->nombre }}</div>
                                @if($plan->descripcion)
                                    <div class="text-xs text-gray-500">{{ $plan->descripcion }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                @if($plan->duracion_minutos < 60)
                                    {{ $plan->duracion_minutos }} min
                                @elseif($plan->duracion_minutos < 1440)
                                    {{ intdiv($plan->duracion_minutos, 60) }} {{ intdiv($plan->duracion_minutos, 60) === 1 ? 'hora' : 'horas' }}
                                @elseif($plan->duracion_minutos < 10080)
                                    {{ intdiv($plan->duracion_minutos, 1440) }} {{ intdiv($plan->duracion_minutos, 1440) === 1 ? 'día' : 'días' }}
                                @else
                                    {{ intdiv($plan->duracion_minutos, 10080) }} {{ intdiv($plan->duracion_minutos, 10080) === 1 ? 'semana' : 'semanas' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                ${{ number_format($plan->precio, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $plan->vouchers_vendidos_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="toggleActive({{ $plan->id }})"
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $plan->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $plan->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button wire:click="edit({{ $plan->id }})" title="Editar" class="text-blue-600 hover:text-blue-900 inline-block p-1 bg-blue-50 rounded hover:bg-blue-100">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                                <button type="button" title="Eliminar" class="text-red-600 hover:text-red-900 inline-block p-1 bg-red-50 rounded hover:bg-red-100"
                                        x-data @click="window.Swal.fire({
                                            title: '¿Eliminar Plan?',
                                            text: 'Se eliminarán también todos sus vouchers. No se puede deshacer.',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#3085d6',
                                            confirmButtonText: 'Sí, eliminar',
                                            cancelButtonText: 'Cancelar'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                @this.delete({{ $plan->id }});
                                            }
                                        })">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No hay planes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Create/Edit --}}
    <div x-data="{ open: @entangle('showModal') }" x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        {{-- Backdrop --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="open = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-lg shadow-xl w-full max-w-lg" @click.outside="open = false">

                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $planId ? 'Editar Plan' : 'Nuevo Plan' }}
                    </h3>
                </div>

                {{-- Body --}}
                <form wire:submit="save" class="px-6 py-4 space-y-4">
                    {{-- Zona --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Zona</label>
                        <select wire:model="zona_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar zona...</option>
                            @foreach($zonas as $z)
                                <option value="{{ $z->id }}">{{ $z->nombre }}</option>
                            @endforeach
                        </select>
                        @error('zona_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nombre --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" wire:model="nombre" placeholder="Ej: 1 Hora, 1 Día..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción <span class="text-gray-400">(opcional)</span></label>
                        <input type="text" wire:model="descripcion" placeholder="Descripción breve del plan"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('descripcion') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Duración + Precio (side by side) --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duración (minutos)</label>
                            <input type="number" wire:model="duracion_minutos" min="1"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-400 mt-1">60=1h, 1440=1d, 10080=1sem</p>
                            @error('duracion_minutos') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio (MXN)</label>
                            <input type="number" wire:model="precio" step="0.01" min="0.01"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('precio') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Activo --}}
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:bg-green-500 transition-colors">
                                <div class="absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                        <span class="text-sm text-gray-700">Plan activo</span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">
                            {{ $planId ? 'Actualizar' : 'Crear Plan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
