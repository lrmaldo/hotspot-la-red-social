<div>
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
            min-height: 300px; /* Evita que desaparezca en móviles al tener contenido absoluto */
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

        /* Estilos generales (Móvil y Escritorio) para que no se estiren y se adapten siempre */
        .media-content video, .media-content img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Esta es la clave para que nada se estire, siempre mantiene su proporción */
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
            left: 0;
            right: 0;
            width: 100%;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
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

    <div class="portal-wrapper">
        <div class="portal-container" x-data="{ showAd: false }">

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
                        <!-- Si no hay carrusel, muestra un encabezado con el título por defecto -->
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
                                    // Resetear si quisiéramos, pero mantenemos simple
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

                    <div style="position: relative; width: 100%; max-width: 900px; height: 85vh; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                        @if($activeVideo->titulo)
                            <div class="media-title" style="top: -40px; left: 0; right: 0; text-align: center; background: none; text-shadow: none;">{{ $activeVideo->titulo }}</div>
                        @endif
                        <video x-ref="videoPlayer" src="{{ $path }}" playsinline :muted="muted" style="width:100%; height:100%; object-fit:contain; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);"></video>
                        
                        <div class="video-controls" style="bottom: 20px; padding: 0 40px;">
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
                                <input type="text" name="username" id="username" placeholder="Ingresa tu PIN (Ej: 1234567)" autofocus required style="font-size: 1rem;">
                            </div>
                        </div>
                        
                        <input type="hidden" name="password" id="password" value="">

                        <!-- Botón primario ahora es para canjear el PIN -->
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

                @if(isset($zona) && $zona->trial_enabled)
                    <div class="divider">
                        <span>O ve publicidad para obtener</span>
                    </div>

                    <div class="mb-4">
                        <!-- El botón secundario (borde transparente) es ahora para la publicidad/internet gratis -->
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

    <!-- Script MD5 para autenticación CHAP de Mikrotik -->
    @if(!empty($chap_id))
        <form name="sendin" action="{{ $link_login_only }}" method="post" style="display:none;">
            <input type="hidden" name="username" />
            <input type="hidden" name="password" />
            <input type="hidden" name="dst" value="{{ $link_orig ?? '' }}" />
            <input type="hidden" name="popup" value="true" />
        </form>
        <script type="text/javascript" src="{{ asset('js/md5.js') }}"></script>
        <script type="text/javascript">
            function doLogin() {
                var loginForm = document.forms['login'];
                if (!loginForm.username.value.trim()) {
                    alert('Por favor ingresa tu PIN');
                    return false;
                }
                document.sendin.username.value = loginForm.username.value;
                document.sendin.password.value = hexMD5('{{ $chap_id }}' + loginForm.password.value + '{{ $chap_challenge }}');
                document.sendin.submit();
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