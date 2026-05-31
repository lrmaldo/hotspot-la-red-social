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

    <div class="w-full max-w-lg mx-auto">
        {{-- Header --}}
        <div class="rounded-t-2xl text-white text-center py-8 px-6" style="background-color: var(--color-primary);">
            @if($zona->logo_path)
                <img src="{{ asset('storage/' . $zona->logo_path) }}" alt="{{ $zona->nombre }}" class="h-14 mx-auto mb-3">
            @endif
            <h1 class="text-2xl font-bold">{{ $zona->nombre }}</h1>
        </div>

        <div class="bg-white rounded-b-2xl shadow-lg overflow-hidden">

            {{-- Processing state --}}
            @if($procesando)
                <div class="p-10 text-center" wire:poll.3s="cargarVoucher">
                    <div class="flex justify-center mb-6">
                        <svg class="animate-spin h-12 w-12" style="color: var(--color-primary);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Confirmando tu pago...</h2>
                    <p class="text-gray-500 text-sm">Esto tarda solo unos segundos</p>
                </div>

            {{-- Success state --}}
            @elseif($voucher && $voucher->estado === 'vendido')
                <div class="p-8 text-center" x-data="{ copiado: false }">
                    {{-- Success icon --}}
                    <div class="flex justify-center mb-5">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center bg-green-100">
                            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-1">¡Pago exitoso!</h2>
                    <p class="text-gray-500 text-sm mb-6">Tu acceso WiFi está listo</p>

                    {{-- PIN code --}}
                    <div class="bg-gray-50 rounded-xl p-6 mb-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-2">Tu código de acceso</p>
                        <div class="text-4xl font-bold tracking-widest text-gray-800">
                            {{ $voucher->codigo }}
                        </div>
                    </div>

                    {{-- Copy button --}}
                    <button @click="navigator.clipboard.writeText('{{ $voucher->codigo }}'); copiado = true; setTimeout(() => copiado = false, 2000)"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors mb-6">
                        <template x-if="!copiado">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Copiar código
                            </span>
                        </template>
                        <template x-if="copiado">
                            <span class="flex items-center gap-2 text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                ¡Copiado!
                            </span>
                        </template>
                    </button>

                    {{-- Plan info --}}
                    <div class="bg-gray-50 rounded-xl p-4 space-y-2 text-sm mb-6">
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
                        <p class="text-xs text-gray-400 mb-4">
                            También te lo enviamos a <strong>{{ $voucher->comprador_email }}</strong>
                        </p>
                    @endif

                    {{-- Return to portal with PIN prefilled --}}
                    <a href="{{ route('portal.zona', ['zona' => $zona->id_personalizado]) . '?checkout=ok&prefill_pin=' . urlencode($voucher->codigo) }}"
                       class="block w-full px-6 py-4 rounded-xl text-white text-center font-bold text-lg hover:opacity-90 transition-opacity"
                       style="background-color: var(--color-primary);">
                        Volver y canjear mi PIN
                    </a>
                </div>

            {{-- Error state --}}
            @else
                <div class="p-10 text-center">
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
                Volver al portal
            </a>
        </div>
    </div>
</div>
