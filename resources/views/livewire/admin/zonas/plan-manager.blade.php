<div>
    <button wire:click="openPlanModal" class="text-teal-600 hover:text-teal-900 inline-block p-1 bg-teal-50 rounded hover:bg-teal-100" title="Gestionar Planes">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
    </button>

    @if($showPlanModal)
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-lg" @click.away="$set('showPlanModal', false)">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $planId ? 'Editar Plan' : 'Crear Plan' }}</h3>
                <form wire:submit.prevent="savePlan">
                    <div class="space-y-4">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" wire:model.defer="nombre" id="nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <input type="text" wire:model.defer="descripcion" id="descripcion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="precio" class="block text-sm font-medium text-gray-700">Precio</label>
                                <input type="number" step="0.01" wire:model.defer="precio" id="precio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('precio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="duracion_minutos" class="block text-sm font-medium text-gray-700">Duración (minutos)</label>
                                <input type="number" wire:model.defer="duracion_minutos" id="duracion_minutos" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('duracion_minutos') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.defer="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Activo</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" @click="$set('showPlanModal', false)" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Cancelar</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Guardar Plan</button>
                    </div>
                </form>

                <hr class="my-6">

                <h4 class="text-md font-medium text-gray-800 mb-3">Planes Existentes</h4>
                <div class="space-y-2">
                    @forelse($planes as $plan)
                        <div class="flex justify-between items-center p-2 rounded-md {{ $plan->is_active ? 'bg-green-50' : 'bg-gray-100' }}">
                            <div>
                                <p class="font-semibold">{{ $plan->nombre }} - ${{ number_format($plan->precio, 2) }}</p>
                                <p class="text-sm text-gray-500">{{ $plan->duracion_minutos }} minutos</p>
                            </div>
                            <div class="space-x-2">
                                <button wire:click="editPlan({{ $plan->id }})" class="text-blue-500 hover:text-blue-700">Editar</button>
                                <button wire:click="deletePlan({{ $plan->id }})" wire:confirm="¿Estás seguro de que quieres eliminar este plan?" class="text-red-500 hover:text-red-700">Eliminar</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No hay planes para esta zona.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>

