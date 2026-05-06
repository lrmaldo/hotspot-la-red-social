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
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
        }

        .portal-wrapper {
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .portal-container {
            background-color: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .portal-content {
            padding: 1.5rem 2rem 2.5rem 2rem;
            background-color: white;
            position: relative;
        }

        .auth-title {
            font-size: 1.25rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--color-text);
        }

        .btn-primary {
            width: 100%;
            padding: 0.85rem 1.25rem;
            background-color: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: opacity var(--animation-speed) ease;
            font-size: 1rem;
            display: block;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .auth-form {
            margin-top: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .auth-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--color-text);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper svg {
            position: absolute;
            left: 1rem;
            color: #9ca3af;
            width: 20px;
            height: 20px;
        }

        .auth-form input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: 1.1rem;
            font-weight: 600;
            transition: all var(--animation-speed) ease;
            background-color: white;
            color: var(--color-text);
            outline: none;
            box-sizing: border-box;
        }

        .auth-form input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-input-focus);
        }

        .text-error {
            color: #dc2626;
            font-size: 0.875rem;
            text-align: center;
            margin-top: 1rem;
            background: #fef2f2;
            padding: 0.5rem;
            border-radius: 0.375rem;
            border: 1px solid #fecaca;
        }
        
        .footer-text {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.75rem;
            color: #9ca3af;
            line-height: 1.4;
        }
        
        .media-container {
            position: relative;
            width: 100%;
            aspect-video;
            background-color: transparent;
            overflow: hidden;
        }

        .media-title {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            text-align: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px;
            text-shadow: 0 1px 3px rgba(0,0,0,0.8);
            z-index: 10;
            background: linear-gradient(to bottom, rgba(0,0,0,0.5) 0%, transparent 100%);
        }

        [x-cloak] { display: none !important; }
    </style>

    <!-- Logo WiFi Superior -->
    <svg class="wifi-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
        <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
        <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
        <line x1="12" y1="20" x2="12.01" y2="20"></line>
    </svg>

    <div class="portal-wrapper">
        <div class="portal-container">

            @if($displayMode === 'video' && $activeVideo)
                <!-- Reproductor de Video -->
                <div x-data="{ 
                        muted: true, 
                        showSkip: {{ $activeVideo->skip_after_seconds ?? 0 }} <= 0,
                        skipSeconds: {{ $activeVideo->skip_after_seconds ?? 0 }},
                        init() {
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
                        }
                    }" 
                    class="media-container">
                    
                    <div class="media-title">{{ $activeVideo->titulo ?? 'test' }}</div>

                    @php
                        $path = str_starts_with($activeVideo->file_path, 'http') ? $activeVideo->file_path : \Illuminate\Support\Facades\Storage::url($activeVideo->file_path);
                    @endphp
                    <video x-ref="videoPlayer" src="{{ $path }}" autoplay muted loop playsinline :muted="muted" class="w-full h-full object-cover"></video>
                    
                    <!-- Botón Activar Audio -->
                    <button @click="muted = !muted; if(!muted) { $refs.videoPlayer.muted = false; } else { $refs.videoPlayer.muted = true; }" 
                            class="absolute bottom-3 right-3 bg-black/40 text-white p-2 rounded-full hover:bg-black/60 transition z-10 backdrop-blur-sm">
                        <svg x-show="muted" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" clip-rule="evenodd"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path></svg>
                        <svg x-cloak x-show="!muted" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                    </button>

                    <!-- Botón Omitir -->
                    @if($activeVideo->skip_after_seconds)
                        @php
                            $skipTextParts = explode('{s}', $activeVideo->skip_texto);
                        @endphp
                        <button type="button"
                                style="min-width: 100px;"
                                :disabled="!showSkip"
                                @click="document.getElementById('login-section').scrollIntoView({behavior: 'smooth'})"
                                class="absolute bottom-3 left-3 bg-white/20 text-white px-3 py-1.5 rounded text-xs font-semibold backdrop-blur-sm shadow transition z-10 flex items-center justify-center hover:bg-white/30">
                            
                            <span x-show="!showSkip" class="flex items-center">
                                <span>{{ trim($skipTextParts[0]) }}</span>
                                <span x-text="skipSeconds" class="mx-1 font-bold"></span>
                                <span>{{ trim($skipTextParts[1] ?? 's') }}</span>
                            </span>

                            <span x-cloak x-show="showSkip">
                                Saltarse &rarr;
                            </span>
                        </button>
                    @endif
                </div>

            @elseif($displayMode === 'carrusel' && count($campanas) > 0)
                <!-- Carrusel de Imágenes -->
                <div x-data="{
                        activeSlide: 0,
                        slides: {{ count($campanas) }},
                        timer: null,
                        init() {
                            if (this.slides > 1) {
                                this.startTimer();
                            }
                        },
                        startTimer() {
                            this.timer = setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.slides }, 6000);
                        },
                        resetTimer() {
                            clearInterval(this.timer);
                            this.startTimer();
                        }
                    }" 
                    class="media-container">
                    
                    @foreach($campanas as $index => $campana)
                        @php
                            $path = str_starts_with($campana->file_path, 'http') ? $campana->file_path : \Illuminate\Support\Facades\Storage::url($campana->file_path);
                        @endphp
                        <div x-show="activeSlide === {{ $index }}"
                             x-transition:enter="transition ease-in-out duration-700"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in-out duration-700 absolute inset-0"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="w-full h-full relative">
                            
                            <div class="media-title">{{ $campana->titulo ?? 'test' }}</div>
                            <img src="{{ $path }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach

                    @if(count($campanas) > 1)
                    <div class="absolute bottom-3 left-0 w-full flex justify-center space-x-2 z-10">
                        @foreach($campanas as $index => $campana)
                            <button @click="activeSlide = {{ $index }}; resetTimer();" 
                                    :style="activeSlide === {{ $index }} ? 'background-color: white; width: 16px;' : 'background-color: rgba(255,255,255,0.5); width: 8px;'" 
                                    class="h-2 rounded-full transition-all duration-300"></button>
                        @endforeach
                    </div>
                    @endif
                </div>
            @else
                <!-- Si no hay video ni carrusel, muestra un encabezado con el título por defecto -->
                <div class="media-container bg-gray-100 flex items-center justify-center">
                    <div class="media-title text-gray-800" style="background: none; text-shadow: none;">{{ $zona->nombre ?? 'test' }}</div>
                </div>
            @endif

            <div class="portal-content" id="login-section">
                <div class="auth-title">Acceder a Internet</div>

                @if(isset($zona) && $zona->trial_enabled)
                    <form action="{{ $link_login_only ?? ('http://'.($zona->hotspot_host ?? '').'/login') }}" method="get" class="mb-6">
                        <input type="hidden" name="dst" value="{{ $link_orig_esc ?? 'http://google.com' }}" />
                        <input type="hidden" name="username" value="T-{{ $mac_esc ?? '' }}" />
                        <button type="submit" class="btn-primary">
                            Conectarse a Internet Gratis
                        </button>
                    </form>
                @endif

                <div class="auth-form">
                    <form name="login" action="{{ $link_login_only ?? ('http://'.($zona->hotspot_host ?? '').'/login') }}" method="post" onSubmit="return doLogin()">
                        <input type="hidden" name="dst" value="{{ $link_orig ?? 'http://google.com' }}" />
                        <input type="hidden" name="popup" value="true" />

                        <div class="form-group">
                            <label for="username">Introduce tu PIN de acceso</label>
                            <div class="input-wrapper">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <input type="text" name="username" id="username" placeholder="1234567" autofocus required>
                            </div>
                        </div>
                        
                        <input type="hidden" name="password" id="password" value="">

                        <button type="submit" class="btn-primary" onclick="document.getElementById('password').value = document.getElementById('username').value;">
                            Canjear PIN
                        </button>

                        @if(!empty($error))
                            <div class="text-error">
                                {{ $error }}
                            </div>
                        @endif
                    </form>
                </div>
                
                <p class="footer-text">
                    Al conectar, aceptas nuestra política de uso justo y los términos de servicio de la red.
                </p>
            </div>
            
        </div>
        
    </div>

    <!-- Script MD5 para autenticación CHAP de Mikrotik -->
    <script src="{{ asset('js/md5.js') }}"></script>
    <script>
        function doLogin() {
            var username = document.getElementById('username');
            if (!username.value.trim()) {
                alert('Por favor ingresa tu PIN');
                return false;
            }
            return true;
        }
    </script>
</div>