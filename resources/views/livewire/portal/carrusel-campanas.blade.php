<div x-data="{ showCompraModal: false }">
    <style>
        :root {
            --color-background: #ffffff;
            --color-primary: {{ $zona->color_primario ?? '#22c55e' }};
            --color-secondary: {{ $zona->color_secundario ?? '#16a34a' }};
            --color-text: #1f2937;
            --color-text-light: #6b7280;
            --color-border: #e5e7eb;
            --color-input-focus: {{ $zona->color_primario ?? '#22c55e' }}33;
            --radius-md: 0.5rem;
            --radius-lg: 1rem;
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            --animation-speed: 0.3s;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background-color: var(--color-background);
            color: var(--color-text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            margin: 0;
        }

        .wifi-icon {
            color: var(--color-primary);
            width: 50px;
            height: 50px;
            margin: 0 auto 1.5rem auto;
            display: block;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
        }

        .portal-wrapper {
            width: 100%;
            max-width: 480px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin: 0 auto;
        }

        .portal-container {
            background-color: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .portal-content {
            padding: 3rem 2.5rem;
            background-color: white;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 2rem;
            color: var(--color-text);
            letter-spacing: -0.025em;
        }

        .btn-trial {
            width: 100%;
            padding: 1rem 1.25rem;
            background-color: var(--color-primary);
            color: white !important;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 700;
            text-align: center;
            cursor: pointer;
            transition: all var(--animation-speed) ease;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px var(--color-input-focus);
            text-decoration: none !important;
            outline: none !important;
        }

        .btn-trial:hover, .btn-trial:focus, .btn-trial:active {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px var(--color-input-focus);
            text-decoration: none !important;
            color: white !important;
        }

        .btn-trial span, .btn-trial strong {
            text-decoration: none !important;
        }

        .btn-pin {
            width: 100%;
            padding: 0.9rem 1.25rem;
            background-color: transparent;
            color: var(--color-text);
            border: 2px solid #e5e7eb;
            border-radius: var(--radius-md);
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all var(--animation-speed) ease;
            font-size: 1rem;
            display: block;
        }

        .btn-pin:hover {
            border-color: var(--color-primary);
            color: var(--color-primary);
            background-color: #f9fafb;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 2rem 0;
            color: #9ca3af;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--color-border);
        }

        .divider span {
            padding: 0 1rem;
        }

        .auth-form {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .auth-form input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: 1.25rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 0.1em;
            transition: all var(--animation-speed) ease;
            background-color: #f9fafb;
            color: var(--color-text);
            outline: none;
            box-sizing: border-box;
        }

        .auth-form input:focus {
            border-color: var(--color-primary);
            background-color: white;
            box-shadow: 0 0 0 4px var(--color-input-focus);
        }

        .text-error {
            color: #dc2626;
            font-size: 0.875rem;
            text-align: center;
            margin-top: 1rem;
            background: #fef2f2;
            padding: 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #fecaca;
            font-weight: 500;
        }
        
        .footer-text {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.75rem;
            color: #9ca3af;
            line-height: 1.5;
        }
        
        .media-container {
            position: relative;
            width: 100%;
            min-height: 300px; /* Evita que desaparezca en mÃ³viles al tener contenido absoluto */
            background-color: #0f172a;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Contenedor difuminado de fondo para rellenar los espacios en contain */
        .media-blur-background {
            position: absolute;
            top: -10%; left: -10%; right: -10%; bottom: -10%;
            width: 120%; height: 120%;
            background-size: cover;
            background-position: center;
            filter: blur(25px) brightness(0.4);
            z-index: 1;
        }

        .media-content {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Estilos generales (MÃ³vil y Escritorio) para que no se estiren y se adapten siempre */
        .media-content video, .media-content img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Esta es la clave para que nada se estire, siempre mantiene su proporciÃ³n */
            object-position: center;
        }

        .media-title {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 8px 16px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            z-index: 10;
            background: rgba(0,0,0,0.4);
            border-radius: 9999px;
            backdrop-filter: blur(4px);
            letter-spacing: 0.025em;
        }

        /* Controles de Video */
        .video-controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: auto;
            max-width: 90%;
            padding: 0 20px;
            display: flex;
            gap: 16px;
            justify-content: center;
            align-items: center;
            z-index: 20;
            pointer-events: none;
        }

        .btn-video-control {
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 700;
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            pointer-events: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            text-decoration: none;
        }

        .btn-video-control:hover {
            background-color: rgba(0, 0, 0, 0.8);
            transform: scale(1.05);
        }

        .btn-video-mute {
            padding: 12px;
            border-radius: 50%;
        }

        .btn-video-icon {
            width: 20px;
            height: 20px;
        }

        /* Controles de Carrusel (Puntos) */
        .carousel-dot {
            height: 8px;
            border-radius: 9999px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
            cursor: pointer;
            margin: 0 4px;
            padding: 0;
            outline: none;
        }

        [x-cloak] { display: none !important; }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ===== MODAL COMPRA VOUCHER ===== */
        .compra-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 100000;
            background-color: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .compra-modal {
            background: white;
            border-radius: 1.5rem;
            width: 100%;
            max-width: 450px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 100001;
        }

        .compra-header {
            background-color: var(--color-primary);
            color: white;
            padding: 2rem 1.5rem 1.5rem;
            border-radius: 1.5rem 1.5rem 0 0;
            text-align: center;
            position: relative;
            pointer-events: none;
        }

        .compra-header h2 {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.025em;
            line-height: 1.2;
            pointer-events: none;
        }

        .compra-header p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 6px;
            font-weight: 500;
            pointer-events: none;
        }

        .compra-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            pointer-events: auto;
        }

        .compra-close:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }

        .compra-body {
            padding: 2rem;
        }

        .compra-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 2rem;
            padding: 0 10%;
        }

        .compra-step-item {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .compra-step-item:last-child {
            flex: 0;
        }

        .compra-step-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            flex-shrink: 0;
        }

        .compra-step-dot.active {
            background-color: var(--color-primary);
            color: white;
            box-shadow: 0 0 0 4px var(--color-input-focus);
            transform: scale(1.1);
        }

        .compra-step-dot.inactive {
            background-color: #f1f5f9;
            color: #94a3b8;
            border-color: #e2e8f0;
        }

        .compra-step-dot.done {
            background-color: #10b981;
            color: white;
        }

        .compra-step-line {
            flex-grow: 1;
            height: 3px;
            background: #e2e8f0;
            margin: 0 8px;
            border-radius: 999px;
            transition: background 0.4s;
        }

        .compra-step-line.active {
            background: var(--color-primary);
        }

        .plan-card {
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            background: white;
        }

        .plan-card:hover {
            border-color: var(--color-primary);
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .plan-card.selected {
            border-color: var(--color-primary);
            background: color-mix(in srgb, var(--color-primary) 5%, white);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .plan-card.selected::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            bottom: -2px;
            left: -2px;
            border: 2px solid var(--color-primary);
            border-radius: 1rem;
            pointer-events: none;
        }

        .plan-card .plan-info h4 {
            font-weight: 800;
            font-size: 1.1rem;
            color: #0f172a;
            margin: 0 0 2px 0;
        }

        .plan-card .plan-info .plan-duration {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .plan-card .plan-price {
            text-align: right;
        }

        .plan-card .plan-price .amount {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--color-primary);
            line-height: 1;
        }

        .plan-card .plan-price .currency {
            font-size: 0.75rem;
            font-weight: 700;
            color: #94a3b8;
            margin-top: 2px;
        }

        .compra-input-group {
            margin-bottom: 1.5rem;
        }

        .compra-input-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .compra-input-wrapper {
            position: relative;
        }

        .compra-input {
            width: 100%;
            padding: 0.875rem 1.125rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.2s;
            outline: none;
            background: #f8fafc;
            box-sizing: border-box;
            color: #0f172a;
        }

        .compra-input:focus {
            border-color: var(--color-primary);
            background: white;
            box-shadow: 0 0 0 4px var(--color-input-focus);
        }

        .compra-btn-primary {
            width: 100%;
            padding: 1.125rem;
            background-color: var(--color-primary);
            color: white;
            border: none;
            border-radius: 0.875rem;
            font-weight: 800;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 15px -3px var(--color-input-focus);
        }

        .compra-btn-primary:hover {
            filter: brightness(0.9);
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px var(--color-input-focus);
        }

        .compra-btn-primary:active {
            transform: translateY(0);
        }

        .compra-btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .compra-btn-secondary {
            width: 100%;
            padding: 1rem;
            background: #f8fafc;
            color: #475569;
            border: 2px solid #e2e8f0;
            border-radius: 0.875rem;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .compra-btn-secondary:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .compra-resumen {
            background: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .compra-resumen-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            font-size: 0.95rem;
        }

        .compra-resumen-row .label {
            color: #64748b;
            font-weight: 500;
        }

        .compra-resumen-row .value {
            color: #0f172a;
            font-weight: 700;
        }

        .compra-resumen-total {
            border-top: 2px solid #e2e8f0;
            margin-top: 0.75rem;
            padding-top: 1rem;
        }

        .compra-resumen-total .value {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--color-primary);
        }

        .compra-stripe-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: #94a3b8;
            font-weight: 600;
        }

        .compra-stripe-badge svg {
            color: #635bff; /* Color Stripe */
        }

        /* Media Queries para Modal Responsive */
        @media (max-width: 640px) {
            .compra-modal {
                max-width: 95% !important;
                max-height: 95vh !important;
            }
            .compra-body {
                padding: 1.5rem !important;
            }
            .compra-steps {
                padding: 0 5% !important;
            }
            .compra-step-dot {
                width: 28px !important;
                height: 28px !important;
                font-size: 0.8rem !important;
            }
        }

        @media (min-width: 641px) and (max-width: 768px) {
            .compra-modal {
                max-width: 550px !important;
            }
        }


        /* Media Queries para Responsive Design (Desktop) */
        @media (min-width: 768px) {
            body {
                padding: 2rem;
                background-color: #f1f5f9;
            }
            .portal-wrapper {
                max-width: 1100px;
                width: 100%;
            }
            .portal-container {
                flex-direction: row;
                min-height: 600px;
                width: 100%;
            }
            .media-container {
                flex: 0 0 55%;
                width: 55%;
                min-width: 55%;
                max-width: 55%;
            }
            .portal-content {
                flex: 0 0 45%;
                width: 45%;
                min-width: 45%;
                max-width: 45%;
                padding: 4rem 3rem;
            }
            .wifi-icon {
                margin: 0 auto 2rem auto;
                width: 60px;
                height: 60px;
            }
        }
    </style>

    <!-- Logo o WiFi Superior -->
    @if(isset($zona) && !empty($zona->logo_path))
        <img src="{{ str_starts_with($zona->logo_path, 'http') ? $zona->logo_path : \Illuminate\Support\Facades\Storage::url($zona->logo_path) }}" class="wifi-icon" style="object-fit: contain; width: auto; max-width: 200px;" alt="Logo {{ $zona->nombre }}">
    @else
        <svg class="wifi-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
            <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
            <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
            <line x1="12" y1="20" x2="12.01" y2="20"></line>
        </svg>
    @endif

    <div class="portal-wrapper" x-data="{ showAd: false }">
        <div class="portal-container">

            <div class="media-container" style="position: relative;">
                
                <!-- ESTADO POR DEFECTO: CARRUSEL DE BANNERS -->
                <div x-show="!showAd" class="w-full h-full" style="position: absolute; top:0; left:0; width:100%; height:100%;">
                    @if(count($campanas) > 0)
                        <div x-data="{
                                activeSlide: 0,
                                slides: {{ count($campanas) }},
                                timer: null,
                                init() {
                                    if (this.slides > 1) {
                                        this.startTimer();
                                    }
                                    
                                    this.$watch('showAd', value => {
                                        if (value) clearInterval(this.timer);
                                        else if (this.slides > 1) this.startTimer();
                                    });
                                },
                                startTimer() {
                                    this.timer = setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.slides }, 6000);
                                },
                                resetTimer() {
                                    clearInterval(this.timer);
                                    this.startTimer();
                                }
                            }" 
                            class="w-full h-full relative" style="width: 100%; height: 100%;">
                            
                            @foreach($campanas as $index => $campana)
                                @php
                                    $path = str_starts_with($campana->file_path, 'http') ? $campana->file_path : \Illuminate\Support\Facades\Storage::url($campana->file_path);
                                @endphp
                                
                                <div x-show="activeSlide === {{ $index }}"
                                     x-transition.opacity.duration.700ms
                                     style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%;">
                                    
                                    <div class="media-blur-background" style="background-image: url('{{ $path }}');"></div>

                                    <div class="media-content">
                                        @if($campana->titulo)
                                            <div class="media-title">{{ $campana->titulo }}</div>
                                        @endif
                                        <img src="{{ $path }}">
                                    </div>
                                </div>
                            @endforeach

                            @if(count($campanas) > 1)
                            <div style="position: absolute; bottom: 20px; left: 0; width: 100%; display: flex; justify-content: center; z-index: 10;">
                                @foreach($campanas as $index => $campana)
                                    <button @click="activeSlide = {{ $index }}; resetTimer();" 
                                            :style="activeSlide === {{ $index }} ? 'background-color: white; width: 24px;' : 'background-color: rgba(255,255,255,0.5); width: 8px;'" 
                                            class="carousel-dot"></button>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    @else
                        <!-- Si no hay carrusel, muestra un encabezado con el tÃ­tulo por defecto -->
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center" style="width: 100%; height: 100%; display: flex; align-items:center; justify-content:center;">
                            <div class="media-title text-gray-800" style="position:relative; top:auto; left:auto; background: none; text-shadow: none; font-size: 1.5rem;">{{ $zona->nombre ?? 'Bienvenidos' }}</div>
                        </div>
                    @endif
                </div>

                <!-- ESTADO ACTIVO: REPRODUCTOR DE VIDEO PUBLICITARIO (MODAL FULLSCREEN) -->
                @if($activeVideo)
                <div x-show="showAd" x-cloak 
                     style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; background: rgba(15, 23, 42, 0.98); display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);"
                     x-data="{ 
                        muted: true, 
                        showSkip: {{ $activeVideo->skip_after_seconds ?? 0 }} <= 0,
                        skipSeconds: {{ $activeVideo->skip_after_seconds ?? 0 }},
                        init() {
                            this.$watch('showAd', value => {
                                if (value) {
                                    this.$nextTick(() => {
                                        if (this.$refs.videoPlayer) {
                                            this.$refs.videoPlayer.play().catch(e => console.warn('Autoplay bloqueado', e));
                                        }
                                    });

                                    if (this.skipSeconds > 0) {
                                        let interval = setInterval(() => {
                                            this.skipSeconds--;
                                            if (this.skipSeconds <= 0) {
                                                this.showSkip = true;
                                                clearInterval(interval);
                                            }
                                        }, 1000);
                                    }
                                } else {
                                    if (this.$refs.videoPlayer) this.$refs.videoPlayer.pause();
                                }
                            });
                        }
                    }">
                    
                    <!-- Botón Cerrar Modal -->
                    <button type="button" @click="showAd = false; $dispatch('cancel-ad')" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 100000; font-size: 1rem; transition: all 0.3s ease;">
                        ✕
                    </button>

                    @php
                        $path = str_starts_with($activeVideo->file_path, 'http') ? $activeVideo->file_path : \Illuminate\Support\Facades\Storage::url($activeVideo->file_path);
                    @endphp

                    <div style="position: relative; width: 90%; max-width: 900px; height: 85vh; display: flex; align-items: center; justify-content: center; flex-direction: column; margin: 0 auto;">
                        @if($activeVideo->titulo)
                            <div class="media-title" style="top: -40px; left: 0; right: 0; text-align: center; background: none; text-shadow: none;">{{ $activeVideo->titulo }}</div>
                        @endif
                        
                        <video x-ref="videoPlayer" src="{{ $path }}" playsinline :muted="muted" style="width:100%; height:100%; object-fit:contain; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);"></video>
                        
                        <div class="video-controls">
                            @if($activeVideo->skip_after_seconds)
                                @php
                                    $skipTextParts = explode('{s}', $activeVideo->skip_texto);
                                @endphp
                                <a :href="showSkip ? '{!! $link_login_only !!}?dst={!! $link_orig_esc ?? '' !!}&username=T-{!! $mac_esc ?? '' !!}' : '#'"
                                   @click="if(!showSkip) { $event.preventDefault(); } else { $el.style.pointerEvents='none'; $el.style.opacity='0.6'; }"
                                   class="btn-video-control"
                                   :style="showSkip ? 'background-color: var(--color-primary); color: white; border-color: var(--color-primary); font-size: 1.1rem; padding: 12px 24px; text-decoration: none;' : 'text-decoration: none; cursor: default;'">
                                    
                                    <span x-show="!showSkip" class="flex items-center" style="display: flex; align-items: center;">
                                        <span>{{ trim($skipTextParts[0] ?? 'Internet en') }}</span>
                                        <span x-text="skipSeconds" style="margin: 0 6px; font-size: 1.1rem;"></span>
                                        <span>{{ trim($skipTextParts[1] ?? 's') }}</span>
                                    </span>

                                    <span x-cloak x-show="showSkip" class="flex items-center" style="display: flex; align-items: center;">
                                        Conectarse a Internet Gratis
                                    </span>
                                </a>
                            @else
                                <div></div>
                            @endif

                            <button @click="muted = !muted; if(!muted) { $refs.videoPlayer.muted = false; } else { $refs.videoPlayer.muted = true; }" 
                                    class="btn-video-control btn-video-mute" style="background: rgba(255,255,255,0.2);">
                                <svg x-show="muted" class="btn-video-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" clip-rule="evenodd"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path></svg>
                                <svg x-cloak x-show="!muted" class="btn-video-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @php
                // Determinar los segundos de cuenta regresiva para el anuncio
                $globalSkipSeconds = $zona->trial_duration_seconds ?? 0;
                
                if ($globalSkipSeconds <= 0) {
                    if ($activeVideo) {
                        $globalSkipSeconds = $activeVideo->skip_after_seconds ?? 0;
                    } elseif (count($campanas) > 0) {
                        $globalSkipSeconds = $campanas->first()->skip_after_seconds ?? 0;
                    }
                }
            @endphp

            <div class="portal-content" id="login-section"
                 @cancel-ad.window="adStarted = false; skipSeconds = {{ $globalSkipSeconds }}; canAccess = false; clearInterval(intervalTimer);"
                 x-data="{
                    canAccess: {{ $globalSkipSeconds <= 0 ? 'true' : 'false' }},
                    skipSeconds: {{ $globalSkipSeconds }},
                    adStarted: false,
                    intervalTimer: null,
                    startWatching() {
                        this.adStarted = true;
                        showAd = true;
                        if (this.skipSeconds > 0) {
                            this.intervalTimer = setInterval(() => {
                                this.skipSeconds--;
                                if (this.skipSeconds <= 0) {
                                    this.canAccess = true;
                                    clearInterval(this.intervalTimer);
                                }
                            }, 1000);
                        } else {
                            this.canAccess = true;
                        }
                    }
                 }">
                <div class="auth-title">Acceder a Internet</div>

                <div class="auth-form">
                    <form name="login" action="{{ $link_login_only }}" method="post" onSubmit="return doLogin()">
                        <input type="hidden" name="dst" value="{{ $link_orig ?? '' }}" />
                        <input type="hidden" name="popup" value="true" />

                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="text" name="username" id="username" value="{{ request()->query('prefill_pin', '') }}" placeholder="Ingresa tu PIN (Ej: 1234567)" autofocus required style="font-size: 1rem;">
                            </div>
                        </div>
                        
                        <input type="hidden" name="password" id="password" value="">

                        <!-- BotÃ³n primario ahora es para canjear el PIN -->
                        <button type="submit" class="btn-trial" style="margin-bottom: 0px;" onclick="if(!document.forms['login'].username.value.trim()) return false; document.forms['login'].password.value = document.forms['login'].username.value; this.disabled=true; this.innerText='Conectando...'; document.forms['login'].submit();">
                            Canjear PIN
                        </button>

                        @if(!empty($error))
                            <div class="text-error">
                                <strong>Error:</strong> {{ $error }}
                            </div>
                        @endif
                    </form>
                </div>

                @if(isset($zona) && $zona->venta_vouchers_activa && $planes->isNotEmpty())
                    @php $checkoutEstado = request()->query('checkout'); @endphp
                    @if($checkoutEstado === 'ok' && request()->query('prefill_pin'))
                        <div style="margin-top: 1rem; background: #ecfdf5; border: 1px solid #86efac; color: #166534; border-radius: 12px; padding: 0.75rem 0.9rem; font-size: 0.9rem;">
                            Tu pago fue acreditado. Tu PIN ya está cargado, solo presiona <strong>Canjear PIN</strong>.
                        </div>
                    @elseif($checkoutEstado === 'cancelado')
                        <div style="margin-top: 1rem; background: #fff7ed; border: 1px solid #fdba74; color: #9a3412; border-radius: 12px; padding: 0.75rem 0.9rem; font-size: 0.9rem;">
                            Cancelaste el pago. Puedes intentarlo de nuevo cuando quieras.
                        </div>
                    @elseif($checkoutEstado === 'error')
                        <div style="margin-top: 1rem; background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; border-radius: 12px; padding: 0.75rem 0.9rem; font-size: 0.9rem;">
                            No pudimos iniciar la pasarela de pago. Intenta nuevamente en unos segundos.
                        </div>
                    @endif

                    <div style="margin-top: 1.5rem;">
                        <button type="button"
                            wire:click="abrirCompra"
                            @click="$dispatch('abrir-compra-modal'); showCompraModal = true"
                                style="width: 100%; height: 55px; background-color: var(--color-primary); color: white; border: none; border-radius: var(--radius-md); font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; box-shadow: var(--shadow-md); transition: opacity 0.2s;">
                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span>Comprar Acceso WiFi</span>
                        </button>
                    </div>
                @endif

                @if(isset($zona) && $zona->trial_enabled)
                    <div class="divider">
                        <span>O ve publicidad para obtener</span>
                    </div>

                    <div class="mb-4">
                        <!-- El botÃ³n secundario (borde transparente) es ahora para la publicidad/internet gratis -->
                        <div x-show="!adStarted">
                            <button type="button" class="btn-pin" @click="startWatching()" style="text-decoration: none;">
                                Ver Publicidad (Internet Gratis)
                            </button>
                        </div>

                        <div x-show="adStarted" x-cloak>
                            <a :href="canAccess ? '{!! $link_login_only !!}?dst={!! $link_orig_esc ?? '' !!}&username=T-{!! $mac_esc ?? '' !!}' : '#'"
                               class="btn-pin"
                               :style="!canAccess ? 'border-color: #e5e7eb; color: #9ca3af; cursor: not-allowed; text-decoration: none;' : 'text-decoration: none;'"
                               @click="if(!canAccess) { $event.preventDefault(); } else { $el.style.opacity='0.5'; $el.style.pointerEvents='none'; }">
                                <span x-show="canAccess" class="flex items-center justify-center text-center">
                                    <span>Conectarse Gratis Ahora</span>
                                </span>
                                <span x-cloak x-show="!canAccess" class="flex items-center justify-center text-center" style="font-size: 0.95rem;">
                                    Internet Gratis en <span x-text="skipSeconds" style="margin-left: 4px; font-weight: bold;"></span>s
                                </span>
                            </a>
                        </div>
                    </div>
                @endif
                
                @if(isset($zona) && !empty($zona->facebook_url))
                    <div style="margin-top: 1.5rem; text-align: center;">
                        <a href="{{ $zona->facebook_url }}" target="_blank" style="color: #1877f2; text-decoration: none; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem;">
                            <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Visítanos en nuestra Fanpage
                        </a>
                    </div>
                @endif

                <p class="footer-text">
                    Al conectar, aceptas nuestra política de uso justo y los términos de servicio de la red.
                </p>
            </div>
            
        </div>
        
    </div>

    {{-- ============================================= --}}
    {{-- MODAL: COMPRA DE VOUCHER                     --}}
    {{-- ============================================= --}}
    
    <div x-show="showCompraModal" x-cloak
         @click="showCompraModal = false"
         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 999999; background-color: rgba(0,0,0,0.85); backdrop-filter: blur(8px); padding: 1rem; display: flex; align-items: center; justify-content: center; box-sizing: border-box;">
        
                         <div x-data="{
                     paso: 1,
                     planSel: null,
                     compraNombre: '',
                     compraEmail: '',
                     selectPlan(planId, nombre, precio, duracion) {
                         this.planSel = {
                             id: Number(planId),
                             nombre: nombre,
                             precio: Number(precio),
                             duracion: Number(duracion)
                         };
                         this.paso = 3;
                     },
                     formatDuracion(mins) {
                         if (mins < 60) return mins + ' min';
                         if (mins < 1440) { let h = Math.floor(mins/60); return h + (h===1?' hora':' horas'); }
                         if (mins < 10080) { let d = Math.floor(mins/1440); return d + (d===1?' día':' días'); }
                         let s = Math.floor(mins/10080); return s + (s===1?' semana':' semanas');
                     }
                 }"
                     @abrir-compra-modal.window="paso = 1; planSel = null; compraNombre = ''; compraEmail = ''"
             @click.stop
             class="compra-modal" 
             style="background: white; border-radius: 1.5rem; width: 100%; max-width: 650px; position: relative; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; margin: auto;">

            {{-- Header --}}
            <div class="compra-header" style="background: var(--color-primary); flex-shrink: 0;">
                <button type="button" class="compra-close" @click="showCompraModal = false">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <svg style="width:28px;height:28px;margin:0 auto 6px;pointer-events:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <h2>Comprar Acceso WiFi</h2>
                    <p>{{ $zona->nombre }}</p>
                </div>

                {{-- Body con scroll --}}
                <div class="compra-body" style="overflow-y: auto; flex: 1; min-height: 0;">
                    
                    {{-- Steps indicator --}}
                    <div class="compra-steps">
                        <div class="compra-step-item">
                            <div class="compra-step-dot" :class="paso === 1 ? 'active' : (paso > 1 ? 'done' : 'inactive')">
                                <span x-show="paso <= 1">1</span>
                                <svg x-show="paso > 1" x-cloak style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="compra-step-line" :class="paso > 1 ? 'active' : ''"></div>
                        </div>
                        <div class="compra-step-item">
                            <div class="compra-step-dot" :class="paso === 3 ? 'active' : 'inactive'">
                                <span>2</span>
                            </div>
                        </div>
                    </div>

                    {{-- PASO 1: Seleccion de plan --}}
                    <div x-show="paso === 1">
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0;">Selecciona un plan</h3>
                            <p style="font-size: 0.875rem; color: #64748b; margin-top: 4px;">Elige el tiempo de conexión que necesites</p>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach($planes as $plan)
                                  <div class="plan-card"
                                      :class="planSel && planSel.id === {{ $plan->id }} ? 'selected' : ''"
                                      wire:key="plan-{{ $plan->id }}"
                                      data-plan-id="{{ $plan->id }}"
                                      data-plan-nombre="{{ $plan->nombre }}"
                                      data-plan-precio="{{ (float) $plan->precio }}"
                                      data-plan-duracion="{{ (int) $plan->duracion_minutos }}"
                                      @click="selectPlan($el.dataset.planId, $el.dataset.planNombre, $el.dataset.planPrecio, $el.dataset.planDuracion)">
                                    <div class="plan-info">
                                        <h4>{{ $plan->nombre }}</h4>
                                        <div class="plan-duration">
                                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @if($plan->duracion_minutos < 60)
                                                {{ $plan->duracion_minutos }} minutos
                                            @elseif($plan->duracion_minutos < 1440)
                                                {{ intdiv($plan->duracion_minutos, 60) }} {{ intdiv($plan->duracion_minutos, 60) === 1 ? 'hora' : 'horas' }}
                                            @elseif($plan->duracion_minutos < 10080)
                                                {{ intdiv($plan->duracion_minutos, 1440) }} {{ intdiv($plan->duracion_minutos, 1440) === 1 ? 'di­a' : 'di­as' }}
                                            @else
                                                {{ intdiv($plan->duracion_minutos, 10080) }} {{ intdiv($plan->duracion_minutos, 10080) === 1 ? 'semana' : 'semanas' }}
                                            @endif
                                        </div>
                                        @if($plan->descripcion)
                                            <div style="font-size:0.75rem; color:#94a3b8; margin-top:4px; line-height:1.3;">{{ $plan->descripcion }}</div>
                                        @endif
                                    </div>
                                    <div class="plan-price">
                                        <div class="amount">${{ number_format($plan->precio, 2) }}</div>
                                        <div class="currency">MXN</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>



                    {{-- PASO 3: Resumen y pago --}}
                    <div x-show="paso === 3">
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0;">Confirmar compra</h3>
                            <p style="font-size: 0.875rem; color: #64748b; margin-top: 4px;">Revisa los detalles antes de pagar</p>
                        </div>

                        <form method="POST" action="{{ route('portal.checkout', $zona) }}" style="display:flex; flex-direction:column; gap:0.75rem;">
                            @csrf
                            <input type="hidden" name="plan_id" :value="planSel ? planSel.id : ''">

                        <div class="compra-input-group">
                            <label class="compra-input-label">Correo electrónico <span style="font-weight:400; color:#94a3b8;">(opcional)</span></label>
                            <div class="compra-input-wrapper">
                                <input type="email" x-model="compraEmail" name="compra_email" class="compra-input" placeholder="ejemplo@correo.com">
                            </div>
                            @error('compra_email') <p style="color:#ef4444; font-size:0.75rem; margin-top:6px; font-weight:500;">{{ $message }}</p> @enderror
                            <p style="font-size:0.75rem; color:#64748b; margin-top:8px; display:flex; align-items:center; gap:4px;">
                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Te enviaremos tu código de acceso por este medio
                            </p>
                        </div>

                        <div class="compra-input-group">
                            <label class="compra-input-label">Nombre completo <span style="font-weight:400; color:#94a3b8;">(opcional)</span></label>
                            <div class="compra-input-wrapper">
                                <input type="text" x-model="compraNombre" name="compra_nombre" class="compra-input" placeholder="Ej: Juan Perez">
                            </div>
                        </div>

                        <template x-if="planSel">
                            <div class="compra-resumen">
                                <div class="compra-resumen-row">
                                    <span class="label">Plan seleccionado</span>
                                    <span class="value" x-text="planSel.nombre"></span>
                                </div>
                                <div class="compra-resumen-row">
                                    <span class="label">Duración</span>
                                    <span class="value" x-text="formatDuracion(planSel.duracion)"></span>
                                </div>
                                <template x-if="compraNombre">
                                    <div class="compra-resumen-row">
                                        <span class="label">Nombre</span>
                                        <span class="value" x-text="compraNombre"></span>
                                    </div>
                                </template>
                                <template x-if="compraEmail">
                                    <div class="compra-resumen-row">
                                        <span class="label">Correo</span>
                                        <span class="value" x-text="compraEmail"></span>
                                    </div>
                                </template>
                                <div class="compra-resumen-row compra-resumen-total">
                                    <span class="label" style="font-weight:800; color:#0f172a;">Total a pagar</span>
                                    <span class="value" x-text="'$' + parseFloat(planSel.precio).toFixed(2) + ' MXN'"></span>
                                </div>
                            </div>
                        </template>

                        <div style="display:flex; flex-direction:column; gap:0.75rem;">
                            @error('plan_id') <p style="color:#ef4444; font-size:0.875rem; text-align:center;">{{ $message }}</p> @enderror
                                <button type="submit" class="compra-btn-primary" :disabled="!planSel" :style="!planSel ? 'opacity:.7; cursor:not-allowed;' : ''">
                                <div style="display:flex; align-items:center; justify-content:center; gap:8px;">
                                    <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Pagar con Tarjeta
                                </div>
                            </button>
                            
                            <button type="button" class="btn-pin" style="width: 100%; border: none; background: transparent; color: #64748b; font-size: 0.9rem; font-weight: 600;" @click="paso = 1; planSel = null">
                                Volver a seleccionar plan
                            </button>
                        </div>
                        </form>

                        <div class="compra-stripe-badge">
                            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13.911 10.119c0-1.045-.806-1.492-2.148-1.492-1.343 0-2.313.433-2.313.433l-.194-1.343s1.178-.507 2.686-.507c2.402 0 3.73 1.134 3.73 3.014 0 3.208-4.401 3.536-4.401 4.387 0 .343.344.477.94.477 1.059 0 2.223-.522 2.223-.522l.224 1.358s-1.119.567-2.73.567c-2.059 0-3.238-1.104-3.238-2.671 0-3.372 4.178-3.61 4.178-4.197zM24 12c0 6.627-5.373 12-12 12S0 18.627 0 12 5.373 0 12 0s12 5.373 12 12zM6.985 9.776V8.104l-2.029.358V9.776h2.029zm0 6.12v-4.686H4.956v4.686H6.985zM11.97 15.896V8.104l-2.03.358V15.896H11.97zm8.448-6.12v-1.671l-2.029.358V15.896h2.029V11.21c0-.986.746-1.358 1.493-1.358.119 0 .224.015.224.015V8.104s-.403-.09-.94-.09c-1.164 0-1.805.626-1.805 1.762v-.015h.03z"></path>
                            </svg>
                            Pago seguro mediante Stripe
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- Script MD5 para autenticación CHAP de Mikrotik -->
    @if(!empty($chap_id))
        <script type="text/javascript" src="{{ asset('js/md5.js') }}"></script>
        <script type="text/javascript">
            function doLogin() {
                var loginForm = document.forms['login'];
                if (!loginForm.username.value.trim()) {
                    alert('Por favor ingresa tu PIN');
                    return false;
                }
                var chapPassword = hexMD5('{{ $chap_id }}' + loginForm.password.value + '{{ $chap_challenge }}');

                var sendin = document.createElement('form');
                sendin.method = 'post';
                sendin.action = @js($link_login_only);
                sendin.style.display = 'none';

                var fields = {
                    username: loginForm.username.value,
                    password: chapPassword,
                    dst: @js($link_orig ?? ''),
                    popup: 'true'
                };

                Object.keys(fields).forEach(function (key) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = fields[key];
                    sendin.appendChild(input);
                });

                document.body.appendChild(sendin);
                sendin.submit();
                return false;
            }
        </script>
    @else
        <script>
            function doLogin() {
                if (!document.forms['login'].username.value.trim()) {
                    alert('Por favor ingresa tu PIN');
                    return false;
                }
                return true;
            }
        </script>
    @endif

</div>
