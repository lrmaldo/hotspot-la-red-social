<div>
    <style>
        :root {
            --color-primary: {{ $zona->color_primario ?? '#2563eb' }};
            --color-secondary: {{ $zona->color_secundario ?? '#ff5e2c' }};
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background-color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
    </style>

    <div class="w-full max-w-lg mx-auto" x-data="{
        paso: 1,
        init() {
            Livewire.on('plan-seleccionado', () => { this.paso = 2 })
        }
    }">
        {{-- Header --}}
        <div class="rounded-t-2xl text-white text-center py-8 px-6" style="background-color: var(--color-primary);">
            @if($zona->logo_path)
                <img src="{{ asset('storage/' . $zona->logo_path) }}" alt="{{ $zona->nombre }}" class="h-14 mx-auto mb-3">
            @endif
            <h1 class="text-2xl font-bold">{{ $zona->nombre }}</h1>
            <p class="text-sm opacity-90 mt-1">Selecciona tu plan de acceso WiFi</p>
        </div>

        <div class="bg-white rounded-b-2xl shadow-lg overflow-hidden">
            {{-- Progress steps --}}
            <div class="flex items-center justify-center gap-2 py-4 px-6 border-b border-gray-100">
                <template x-for="s in 3" :key="s">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-colors duration-300"
                             :class="paso >= s ? 'text-white' : 'bg-gray-200 text-gray-500'"
                             :style="paso >= s ? 'background-color: var(--color-primary)' : ''">
                            <span x-text="s"></span>
                        </div>
                        <span class="text-xs text-gray-500 hidden sm:inline"
                              x-text="s === 1 ? 'Plan' : (s === 2 ? 'Datos' : 'Pagar')"></span>
                        <div x-show="s < 3" class="w-6 h-px bg-gray-300"></div>
                    </div>
                </template>
            </div>

            {{-- Step 1: Plan selection --}}
            <div x-show="paso === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Elige tu plan</h2>

                @forelse($planes as $plan)
                    <button wire:click="seleccionarPlan({{ $plan->id }})"
                            class="w-full text-left border-2 rounded-xl p-4 mb-3 transition-all duration-200 hover:shadow-md focus:outline-none
                                   {{ $planId === $plan->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}"
                            style="{{ $planId === $plan->id ? 'border-color: var(--color-primary); background-color: color-mix(in srgb, var(--color-primary) 8%, white)' : '' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $plan->nombre }}</h3>
                                <p class="text-sm text-gray-500 mt-0.5">
                                    @if($plan->duracion_minutos < 60)
                                        {{ $plan->duracion_minutos }} minutos
                                    @elseif($plan->duracion_minutos < 1440)
                                        {{ intdiv($plan->duracion_minutos, 60) }} {{ intdiv($plan->duracion_minutos, 60) === 1 ? 'hora' : 'horas' }}
                                    @elseif($plan->duracion_minutos < 10080)
                                        {{ intdiv($plan->duracion_minutos, 1440) }} {{ intdiv($plan->duracion_minutos, 1440) === 1 ? 'día' : 'días' }}
                                    @else
                                        {{ intdiv($plan->duracion_minutos, 10080) }} {{ intdiv($plan->duracion_minutos, 10080) === 1 ? 'semana' : 'semanas' }}
                                    @endif
                                </p>
                                @if($plan->descripcion)
                                    <p class="text-xs text-gray-400 mt-1">{{ $plan->descripcion }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold" style="color: var(--color-primary);">${{ number_format($plan->precio, 2) }}</span>
                                <span class="text-xs text-gray-400 block">MXN</span>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>No hay planes disponibles en este momento</p>
                    </div>
                @endforelse
            </div>

            {{-- Step 2: Customer data --}}
            <div x-show="paso === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Tus datos <span class="text-sm font-normal text-gray-400">(opcional)</span></h2>

                <div class="space-y-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-600 mb-1">Nombre</label>
                        <input type="text" id="nombre" wire:model.live="nombre" placeholder="Tu nombre"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent transition-shadow text-sm"
                               style="focus:ring-color: var(--color-primary);">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Correo electrónico</label>
                        <input type="email" id="email" wire:model.live="email" placeholder="tu@correo.com"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent transition-shadow text-sm"
                               style="focus:ring-color: var(--color-primary);">
                        <p class="text-xs text-gray-400 mt-1">Te enviaremos tu código de acceso por correo</p>
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button @click="paso = 1"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">
                        Volver
                    </button>
                    <button @click="paso = 3"
                            class="flex-1 px-4 py-3 rounded-lg text-white text-sm font-medium hover:opacity-90 transition-opacity"
                            style="background-color: var(--color-primary);">
                        Continuar
                    </button>
                </div>
            </div>

            {{-- Step 3: Confirm & pay --}}
            <div x-show="paso === 3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirmar compra</h2>

                @if($planId)
                    @php $planSeleccionado = $planes->firstWhere('id', $planId); @endphp
                    @if($planSeleccionado)
                        <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Plan</span>
                                <span class="font-semibold text-gray-800">{{ $planSeleccionado->nombre }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Duración</span>
                                <span class="text-gray-700">
                                    @if($planSeleccionado->duracion_minutos < 60)
                                        {{ $planSeleccionado->duracion_minutos }} minutos
                                    @elseif($planSeleccionado->duracion_minutos < 1440)
                                        {{ intdiv($planSeleccionado->duracion_minutos, 60) }} {{ intdiv($planSeleccionado->duracion_minutos, 60) === 1 ? 'hora' : 'horas' }}
                                    @elseif($planSeleccionado->duracion_minutos < 10080)
                                        {{ intdiv($planSeleccionado->duracion_minutos, 1440) }} {{ intdiv($planSeleccionado->duracion_minutos, 1440) === 1 ? 'día' : 'días' }}
                                    @else
                                        {{ intdiv($planSeleccionado->duracion_minutos, 10080) }} {{ intdiv($planSeleccionado->duracion_minutos, 10080) === 1 ? 'semana' : 'semanas' }}
                                    @endif
                                </span>
                            </div>
                            @if($nombre)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Nombre</span>
                                    <span class="text-gray-700">{{ $nombre }}</span>
                                </div>
                            @endif
                            @if($email)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Correo</span>
                                    <span class="text-gray-700">{{ $email }}</span>
                                </div>
                            @endif
                            <div class="border-t border-gray-200 pt-3 flex justify-between items-center">
                                <span class="font-semibold text-gray-800">Total</span>
                                <span class="text-2xl font-bold" style="color: var(--color-primary);">${{ number_format($planSeleccionado->precio, 2) }} MXN</span>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="flex gap-3 mt-6">
                    <button @click="paso = 2"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">
                        Volver
                    </button>
                    <button wire:click="iniciarPago"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-60 cursor-not-allowed"
                            class="flex-1 px-4 py-3 rounded-lg text-white text-sm font-bold hover:opacity-90 transition-opacity flex items-center justify-center gap-2"
                            style="background-color: var(--color-primary);">
                        <span wire:loading.remove>
                            Pagar ${{ $planId ? number_format($planes->firstWhere('id', $planId)?->precio ?? 0, 2) : '0.00' }} MXN
                        </span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Redirigiendo...
                        </span>
                    </button>
                </div>

                <p class="text-xs text-gray-400 text-center mt-4">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Pago seguro procesado por Stripe
                </p>
            </div>
        </div>

        {{-- Back to portal link --}}
        <div class="text-center mt-4">
            <a href="{{ route('portal.zona', ['zona' => $zona->id_personalizado]) }}" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                Volver al portal
            </a>
        </div>
    </div>
</div>
