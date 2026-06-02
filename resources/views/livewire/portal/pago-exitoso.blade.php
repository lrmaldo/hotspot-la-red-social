<div>
    <style>
        :root {
            --color-primary: {{ $zona->color_primario ?? '#2563eb' }};
            --color-secondary: {{ $zona->color_secundario ?? '#ff5e2c' }};
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

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

        /* Contenedor principal */
        .pg-wrapper { width: 100%; max-width: 28rem; margin: 0 auto; }

        /* Header */
        .pg-header {
            border-radius: 1rem 1rem 0 0;
            color: white;
            text-align: center;
            padding: 1.5rem;
            background-color: var(--color-primary);
        }
        .pg-header img { height: 3rem; display: block; margin: 0 auto 0.5rem auto; object-fit: contain; max-width: 180px; }
        .pg-header h1 { font-size: 1.25rem; font-weight: 700; }

        /* Cuerpo */
        .pg-body {
            background: white;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,.10), 0 8px 10px -6px rgba(0,0,0,.06);
            overflow: hidden;
        }

        /* Estados */
        .pg-state { padding: 2rem 1.5rem; text-align: center; }

        /* Spinner */
        .pg-spinner { width: 3rem; height: 3rem; color: var(--color-primary); animation: spin 1s linear infinite; margin: 0 auto 1.5rem auto; display: block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Ícono éxito */
        .pg-icon-ok {
            width: 5rem; height: 5rem; border-radius: 9999px;
            display: flex; align-items: center; justify-content: center;
            background: #dcfce7; margin: 0 auto 1rem auto;
        }
        .pg-icon-ok svg { width: 2.5rem; height: 2.5rem; color: #22c55e; }

        /* Tipografía */
        .pg-title { font-size: 1.5rem; font-weight: 800; color: #111827; margin-bottom: 0.25rem; }
        .pg-subtitle { font-size: 0.875rem; color: #6b7280; margin-bottom: 1.5rem; }

        /* Caja de código */
        .pg-code-box {
            background: #f9fafb; border-radius: 0.75rem;
            padding: 1.25rem; margin-bottom: 1rem;
        }
        .pg-code-label { font-size: 0.7rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 600; margin-bottom: 0.5rem; }
        .pg-code { font-size: 2.25rem; font-weight: 800; letter-spacing: 0.2em; color: #111827; }

        /* Botón copiar */
        .pg-btn-copy {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1rem; border-radius: 0.5rem;
            border: 1.5px solid #d1d5db; background: white;
            font-size: 0.875rem; font-weight: 500; color: #374151;
            cursor: pointer; margin-bottom: 1.5rem;
        }
        .pg-btn-copy svg { width: 1rem; height: 1rem; }
        .pg-copied { color: #16a34a; }

        /* Info plan */
        .pg-plan-box {
            background: #f9fafb; border-radius: 0.75rem;
            padding: 1rem; margin-bottom: 1.5rem;
        }
        .pg-plan-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.25rem 0; font-size: 0.875rem;
        }
        .pg-plan-row .lbl { color: #6b7280; }
        .pg-plan-row .val { font-weight: 600; color: #111827; }
        .pg-plan-divider { border: none; border-top: 1px solid #e5e7eb; margin: 0.5rem 0; }
        .pg-plan-total .lbl { font-weight: 800; color: #111827; font-size: 0.9rem; }
        .pg-plan-total .val { font-weight: 800; color: var(--color-primary); font-size: 1rem; }

        .pg-email-note { font-size: 0.75rem; color: #9ca3af; margin-bottom: 1rem; }

        /* Botón conectar */
        .pg-btn-connect {
            display: block; width: 100%; padding: 0.875rem 1.5rem;
            border-radius: 0.75rem; color: white; text-align: center;
            font-weight: 700; font-size: 1rem; text-decoration: none;
            background-color: var(--color-primary);
            margin-bottom: 0.5rem;
        }
        .pg-hint { font-size: 0.75rem; color: #9ca3af; margin-bottom: 0.5rem; }

        /* Error */
        .pg-icon-err {
            width: 5rem; height: 5rem; border-radius: 9999px;
            display: flex; align-items: center; justify-content: center;
            background: #fee2e2; margin: 0 auto 1rem auto;
        }
        .pg-icon-err svg { width: 2.5rem; height: 2.5rem; color: #ef4444; }

        /* Footer */
        .pg-footer { text-align: center; margin-top: 1rem; }
        .pg-footer a { font-size: 0.875rem; color: #9ca3af; text-decoration: none; }

        @keyframes pop-in {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes fade-in-up {
            0% { transform: translateY(20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        .animate-pop-in { animation: pop-in 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) both; }
        .animate-fade-in-up { animation: fade-in-up 0.6s cubic-bezier(0.39, 0.575, 0.565, 1) both; }
    </style>

    <div class="pg-wrapper">
        {{-- Header --}}
        <div class="pg-header">
            @if($zona->logo_path)
                <img src="{{ asset('storage/' . $zona->logo_path) }}" alt="{{ $zona->nombre }}">
            @else
                <h1>{{ $zona->nombre }}</h1>
            @endif
        </div>

        <div class="pg-body">

            {{-- Processing state --}}
            @if($procesando)
                <div class="pg-state" wire:poll.3s="cargarVoucher">
                    <svg class="pg-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <h2 style="font-size:1.1rem; font-weight:600; color:#1f2937; margin-bottom:.5rem;">Confirmando tu pago...</h2>
                    <p style="font-size:.875rem; color:#6b7280;">Esto puede tardar unos segundos, no cierres esta ventana.</p>
                </div>

            {{-- Success state --}}
            @elseif($voucher && $voucher->estado === 'vendido')
                <div class="pg-state" x-data="{ copiado: false }"
                     x-init="setTimeout(() => { window.location.href = '{{ route('portal.zona', ['zona' => $zona->id_personalizado]) . '?checkout=ok&prefill_pin=' . urlencode($voucher->codigo) . '&auto_submit_pin=1' }}' }, 4000)">

                    <div class="pg-icon-ok animate-pop-in">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <h2 class="pg-title animate-fade-in-up" style="animation-delay:100ms;">¡Pago exitoso!</h2>
                    <p class="pg-subtitle animate-fade-in-up" style="animation-delay:200ms;">Tu acceso WiFi está listo para usarse.</p>

                    {{-- PIN code --}}
                    <div class="pg-code-box animate-fade-in-up" style="animation-delay:300ms;">
                        <p class="pg-code-label">Tu código de acceso</p>
                        <div class="pg-code">{{ $voucher->codigo }}</div>
                    </div>

                    {{-- Copy button --}}
                    <div class="animate-fade-in-up" style="animation-delay:400ms;">
                        <button class="pg-btn-copy"
                                @click="navigator.clipboard.writeText('{{ $voucher->codigo }}'); copiado = true; setTimeout(() => copiado = false, 2000)">
                            <span x-show="!copiado" style="display:inline-flex;align-items:center;gap:.5rem;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                Copiar código
                            </span>
                            <span x-show="copiado" x-cloak class="pg-copied" style="display:inline-flex;align-items:center;gap:.5rem;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                ¡Copiado!
                            </span>
                        </button>
                    </div>

                    {{-- Plan info --}}
                    <div class="pg-plan-box animate-fade-in-up" style="animation-delay:500ms;">
                        <div class="pg-plan-row">
                            <span class="lbl">Plan</span>
                            <span class="val">{{ $voucher->plan->nombre }}</span>
                        </div>
                        <div class="pg-plan-row">
                            <span class="lbl">Duración</span>
                            <span class="val">
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
                            <div class="pg-plan-row">
                                <span class="lbl">Expira</span>
                                <span class="val">{{ $voucher->fecha_expiracion->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                        @if($voucher->monto_pagado)
                            <hr class="pg-plan-divider">
                            <div class="pg-plan-row pg-plan-total">
                                <span class="lbl">Total pagado</span>
                                <span class="val">${{ number_format($voucher->monto_pagado, 2) }} MXN</span>
                            </div>
                        @endif
                    </div>

                    @if($voucher->comprador_email)
                        <p class="pg-email-note animate-fade-in-up" style="animation-delay:600ms;">
                            También enviamos tu código a <strong>{{ $voucher->comprador_email }}</strong>
                        </p>
                    @endif

                    <div class="animate-fade-in-up" style="animation-delay:700ms;">
                        <a href="{{ route('portal.zona', ['zona' => $zona->id_personalizado]) . '?checkout=ok&prefill_pin=' . urlencode($voucher->codigo) . '&auto_submit_pin=1' }}"
                           class="pg-btn-connect">
                            Conectarme ahora
                        </a>
                        <p class="pg-hint">Te redirigiremos automáticamente para conectar.</p>
                    </div>
                </div>

            {{-- Error state --}}
            @else
                <div class="pg-state">
                    <div class="pg-icon-err">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h2 style="font-size:1.1rem; font-weight:600; color:#1f2937; margin-bottom:.5rem;">No pudimos confirmar tu pago</h2>
                    <p style="font-size:.875rem; color:#6b7280; margin-bottom:1.5rem;">
                        Si realizaste el pago, espera unos minutos e intenta recargar esta página.
                        Si el problema persiste, contacta a soporte.
                    </p>
                    <a href="{{ route('portal.zona', ['zona' => $zona->id_personalizado]) }}" class="pg-btn-connect">
                        Volver al portal
                    </a>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="pg-footer">
            <a href="{{ route('portal.zona', ['zona' => $zona->id_personalizado]) }}">Volver al inicio</a>
        </div>
    </div>
</div>
