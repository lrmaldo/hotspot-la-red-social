<div>
    <button wire:click="openPlanModal" 
            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-teal-100 text-teal-800 hover:bg-teal-200 transition-colors border border-teal-200" 
            title="Gestionar Planes de {{ $zona->nombre }}">
        <svg class="h-4 w-4 mr-1 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
        {{ count($planes) }} {{ count($planes) === 1 ? 'Plan' : 'Planes' }}
    </button>

    @if($showPlanModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showPlanModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal panel --}}
                <div @click.away="$set('showPlanModal', false)" 
                     class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
                    
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            Planes de Vouchers: {{ $zona->nombre }}
                        </h3>
                        <button wire:click="$set('showPlanModal', false)" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6">
                        {{-- Formulario para crear/editar --}}
                        <div class="bg-blue-50 rounded-xl p-5 mb-8 border border-blue-100">
                            <h4 class="text-sm font-bold text-blue-900 mb-4 uppercase tracking-wider">{{ $planId ? 'Editar información del plan' : 'Crear nuevo plan de conexión' }}</h4>
                            
                            <form wire:submit.prevent="savePlan" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="col-span-1 md:col-span-2">
                                        <label class="block text-xs font-bold text-blue-800 uppercase mb-1">Nombre Comercial</label>
                                        <input type="text" wire:model.defer="nombre" placeholder="Ej: 1 Hora WiFi" class="w-full rounded-lg border-gray-200 text-sm focus:ring-teal-500 focus:border-teal-500">
                                        @error('nombre') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-blue-800 uppercase mb-1">Precio (MXN)</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2 text-gray-400 text-sm">$</span>
                                            <input type="number" step="0.01" wire:model.defer="precio" placeholder="0.00" class="w-full pl-7 rounded-lg border-gray-200 text-sm focus:ring-teal-500 focus:border-teal-500">
                                        </div>
                                        @error('precio') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-blue-800 uppercase mb-1">Duración (minutos)</label>
                                        <input type="number" wire:model.defer="duracion_minutos" placeholder="60" class="w-full rounded-lg border-gray-200 text-sm focus:ring-teal-500 focus:border-teal-500">
                                        @error('duracion_minutos') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-span-1 md:col-span-2 flex items-center justify-between pt-2">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model.defer="is_active" class="rounded text-teal-600 focus:ring-teal-500 mr-2">
                                            <span class="text-sm font-medium text-gray-700">Plan disponible para venta</span>
                                        </label>

                                        <div class="flex gap-2">
                                            @if($planId)
                                                <button type="button" wire:click="resetForm" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 uppercase">Cancelar</button>
                                            @endif
                                            <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg text-xs font-bold hover:bg-teal-700 transition shadow-sm uppercase tracking-widest">
                                                {{ $planId ? 'Actualizar Plan' : 'Guardar Plan' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <h4 class="text-xs font-bold text-gray-400 mb-4 uppercase tracking-widest">Planes configurados actualmente</h4>
                        
                        <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                            @forelse($planes as $plan)
                                <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:shadow-md transition bg-white group {{ !$plan->is_active ? 'opacity-60 grayscale' : '' }}">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 mr-4">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $plan->nombre }}</p>
                                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                                <span>{{ $plan->duracion_minutos }} min</span>
                                                <span class="text-gray-300">•</span>
                                                <span class="font-bold text-teal-600">${{ number_format($plan->precio, 2) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button wire:click="editPlan({{ $plan->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <button wire:click="deletePlan({{ $plan->id }})" 
                                                wire:confirm="¿Estás seguro de eliminar el plan '{{ $plan->nombre }}'? Esto no afectará a los vouchers ya vendidos."
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                    <svg class="h-10 w-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    <p class="text-sm text-gray-500 font-medium">No hay planes definidos para esta zona</p>
                                    <p class="text-xs text-gray-400 mt-1">Usa el formulario superior para agregar el primero.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

