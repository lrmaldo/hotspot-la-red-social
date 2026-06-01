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
            background-image: linear-gradient(to top, #f3f4f6 0%, color-mix(in srgb, var(--color-primary) 8%, #f3f4f6) 100%);
        }

        @keyframes pop-in {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes fade-in-up {
            0% { transform: translateY(20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .animate-pop-in {
            animation: pop-in 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.6s cubic-bezier(0.39, 0.575, 0.565, 1) both;
        }
    </style>

    <div class="w-full max-w-md mx-auto">
        {{-- Header --}}
        <div class="rounded-t-2xl text-white text-center py-6 px-6" style="background-color: var(--color-primary);">
            @if($zona->logo_path)
                <img src="{{ asset('storage/' . $zona->logo_path) }}" alt="{{ $zona->nombre }}" class="h-12 mx-auto mb-2">
            @else
                <h1 class="text-2xl font-bold">{{ $zona->nombre }}</h1>
            @endif
        </div>

        <div class="bg-white rounded-b-2xl shadow-xl overflow-hidden">

            {{-- Processing state --}}
            @if($procesando)
                <div class="p-8 sm:p-10 text-center" wire:poll.3s="cargarVoucher">
                    <div class="flex justify-center mb-6">
                        <svg class="animate-spin h-12 w-12 text-[var(--color-primary)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Confirmando tu pago...</h2>
                    <p class="text-gray-500 text-sm">Esto puede tardar unos segundos, no cierres esta ventana.</p>
                </div>

            {{-- Success state --}}
            @elseif($voucher && $voucher->estado === 'vendido')
                <div class="p-6 sm:p-8 text-center" x-data="{ copiado: false }"
                     x-init="setTimeout(() => { window.location.href = '{{ route('portal.zona', ['zona' => $zona->id_personalizado]) . '?checkout=ok&prefill_pin=' . urlencode($voucher->codigo) . '&auto_submit_pin=1' }}' }, 4000)">
                    
                    {{-- Success icon with animation --}}
                    <div class="flex justify-center mb-4">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center bg-green-100 animate-pop-in">
                            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-1 animate-fade-in-up" style="animation-delay: 100ms;">¡Pago exitoso!</h2>
                    <p class="text-gray-500 text-sm mb-6 animate-fade-in-up" style="animation-delay: 200ms;">Tu acceso WiFi está listo para usarse.</p>

                    {{-- PIN code --}}
                    <div class="bg-gray-50 rounded-xl p-5 mb-4 animate-fade-in-up" style="animation-delay: 300ms;">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-2">Tu código de acceso</p>
                        <div class="text-4xl font-bold tracking-widest text-gray-800">
                            {{ $voucher->codigo }}
                        </div>
                    </div>

                    {{-- Copy button --}}
                    <div class="animate-fade-in-up" style="animation-delay: 400ms;">
                        <button @click="navigator.clipboard.writeText('{{ $voucher->codigo }}'); copiado = true; setTimeout(() => copiado = false, 2000)"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-600 hover:bg-gray-50 active:scale-95 transition-all mb-6">
                            <span x-show="!copiado" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                Copiar código
                            </span>
                            <span x-show="copiado" x-cloak class="flex items-center gap-2 text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                ¡Copiado!
                            </span>
                        </button>
                    </div>

                    {{-- Plan info --}}
                    <div class="bg-gray-50 rounded-xl p-4 space-y-2 text-sm mb-6 animate-fade-in-up" style="animation-delay: 500ms;">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Plan</span>
                            <span class="font-medium text-gray-800">{{ $voucher->plan->nombre }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Duración</span>
                            <span class="text-gray-700">
                                @if($voucher->plan->duracion_minutos < 60)
                                    {{ $voucher->plan->duracion_minutos }} minutos
                                @elseif($voucher->plan->duracion_minutos < 1440)
                                    {{ intdiv($voucher->plan->duracion_minutos, 60) }} {{ intdiv($voucher->plan->duracion_minutos, 60) === 1 ? 'hora' : 'horas' }}
                                @elseif($voucher->plan->duracion_minutos < 10080)
                                    {{ intdiv($voucher->plan->duracion_minutos, 1440) }} {{ intdiv($voucher->plan->duracion_minutos, 1440) === 1 ? 'día' : 'días' }}
                                @else
                                    {{ intdiv($voucher->plan->duracion_minutos, 10080) }} {{ intdiv($voucher->plan->duracion_minutos, 10080) === 1 ? 'semana' : 'semanas' }}
                                @endif
                            </span>
                        </div>
                        @if($voucher->fecha_expiracion)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Expira</span>
                                <span class="text-gray-700">{{ $voucher->fecha_expiracion->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                        @if($voucher->monto_pagado)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Pagado</span>
                                <span class="font-medium text-gray-800">${{ number_format($voucher->monto_pagado, 2) }} MXN</span>
                            </div>
                        @endif
                    </div>

                    @if($voucher->comprador_email)
                        <p class="text-xs text-gray-400 mb-4 animate-fade-in-up" style="animation-delay: 600ms;">
                            También enviamos tu código a <strong>{{ $voucher->comprador_email }}</strong>
                        </p>
                    @endif

                    {{-- Return to portal with PIN prefilled --}}
                    <div class="animate-fade-in-up" style="animation-delay: 700ms;">
                        <a href="{{ route('portal.zona', ['zona' => $zona->id_personalizado]) . '?checkout=ok&prefill_pin=' . urlencode($voucher->codigo) . '&auto_submit_pin=1' }}"
                           class="block w-full px-6 py-3 rounded-xl text-white text-center font-bold text-lg hover:opacity-90 active:scale-95 transition-all"
                           style="background-color: var(--color-primary);">
                            Conectarme ahora
                        </a>
                        <p class="text-xs text-gray-400 mt-3">Te redirigiremos automáticamente para conectar.</p>
                    </div>
                </div>

            {{-- Error state --}}
            @else
                <div class="p-8 sm:p-10 text-center">
                    <div class="flex justify-center mb-5">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center bg-red-100">
                            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-800 mb-2">No pudimos confirmar tu pago</h2>
                    <p class="text-gray-500 text-sm mb-6">
                        Si realizaste el pago, espera unos minutos e intenta recargar esta página.
                        Si el problema persiste, contacta a soporte.
                    </p>

                    <div class="flex flex-col gap-3">
                        <a href="{{ route('portal.zona', ['zona' => $zona->id_personalizado]) }}"
                           class="block w-full px-6 py-3 rounded-lg text-white text-center font-medium hover:opacity-90 transition-opacity"
                           style="background-color: var(--color-primary);">
                            Volver al portal
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="text-center mt-4">
            <a href="{{ route('portal.zona', ['zona' => $zona->id_personalizado]) }}" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                Volver al inicio
            </a>
        </div>
    </div>
</div>
