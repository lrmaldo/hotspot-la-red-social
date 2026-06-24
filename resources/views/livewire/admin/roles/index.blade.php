<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">{{ $roles->count() }} {{ $roles->count() === 1 ? 'perfil' : 'perfiles' }}</p>
        <button wire:click="create" type="button"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo perfil
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($roles as $role)
            @php $esSistema = in_array($role->name, $rolesSistema, true); @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 capitalize">{{ $role->name }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $role->users_count }} {{ $role->users_count === 1 ? 'usuario' : 'usuarios' }}
                        </p>
                    </div>
                    @if($esSistema)
                        <span class="text-[11px] font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-full px-2.5 py-0.5">Sistema</span>
                    @endif
                </div>

                <div class="mt-3 flex-1">
                    @if($esSistema)
                        <p class="text-sm text-gray-500">Acceso total a todo el panel.</p>
                    @else
                        <p class="text-sm text-gray-600">{{ $role->permissions_count }} {{ $role->permissions_count === 1 ? 'permiso' : 'permisos' }} habilitados</p>
                    @endif
                </div>

                @unless($esSistema)
                    <div class="mt-4 flex gap-2">
                        <button wire:click="edit({{ $role->id }})" type="button"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-blue-700 hover:bg-blue-50 border border-gray-200 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Editar
                        </button>
                        <button wire:click="confirmDelete({{ $role->id }})" type="button"
                                class="inline-flex items-center justify-center rounded-lg px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-red-700 hover:bg-red-50 border border-gray-200 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                @endunless
            </div>
        @endforeach
    </div>

    {{-- Modal crear/editar perfil --}}
    <div x-data="{ show: @entangle('showModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex flex-col justify-end sm:justify-center sm:items-center sm:p-4"
         role="dialog" aria-modal="true">

        <div class="fixed inset-0 bg-gray-900/60 -z-10" @click="show = false"
             x-show="show"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
             class="relative w-full bg-white rounded-t-2xl sm:rounded-xl shadow-xl overflow-hidden sm:max-w-2xl max-h-[92vh] flex flex-col">

            <form wire:submit="save" class="flex flex-col overflow-hidden">
                <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b border-gray-100 flex-shrink-0">
                    <h3 class="text-base font-semibold text-gray-900">{{ $roleId ? 'Editar perfil' : 'Nuevo perfil' }}</h3>
                    <button type="button" @click="show = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="overflow-y-auto px-5 py-5 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del perfil <span class="text-red-500">*</span></label>
                        <input wire:model="nombre" type="text" placeholder="Ej. Operador, Supervisor"
                               class="block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                        @error('nombre') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    @foreach($catalogo as $grupo => $permisos)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ $grupo }}</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($permisos as $clave => $etiqueta)
                                    <label class="flex items-center gap-3 rounded-xl border border-gray-200 px-3 py-2.5 cursor-pointer hover:bg-gray-50 transition">
                                        <input type="checkbox" wire:model="permisosSeleccionados" value="{{ $clave }}"
                                               class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">{{ $etiqueta }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex-shrink-0 bg-gray-50 px-5 py-4 flex flex-row-reverse gap-3 border-t border-gray-100">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                        <svg wire:loading wire:target="save" style="display:none" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Guardar
                    </button>
                    <button type="button" @click="show = false" class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal confirmar eliminación --}}
    <div x-data="{ show: @entangle('showDeleteConfirm') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex flex-col justify-end sm:justify-center sm:items-center sm:p-4"
         role="dialog" aria-modal="true">

        <div class="fixed inset-0 bg-gray-900/60 -z-10" @click="show = false" x-show="show"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
             class="relative w-full bg-white rounded-t-2xl sm:rounded-xl shadow-xl overflow-hidden sm:max-w-sm">
            <div class="px-5 py-6 text-center">
                <div class="mx-auto w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">¿Eliminar perfil?</h3>
                <p class="text-sm text-gray-500">Los usuarios con este perfil quedarán sin permisos.</p>
            </div>
            <div class="flex gap-3 px-5 pb-5">
                <button type="button" @click="show = false" class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Cancelar</button>
                <button wire:click="delete" type="button" class="flex-1 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Eliminar</button>
            </div>
        </div>
    </div>
</div>
