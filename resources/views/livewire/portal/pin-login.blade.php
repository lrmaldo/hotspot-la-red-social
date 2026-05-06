<!DOCTYPE html>
<html lang="es">
<head>
    <title>Portal Cautivo - {{ $zona->nombre ?? 'WiFi' }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS del sistema -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Variables CSS personalizables dinámicamente según la Zona */
        :root {
            --color-background: #f3f4f6;
            --color-primary: {{ $zona->color_primario ?? '#2563eb' }}; /* Azul por defecto */
            --color-secondary: {{ $zona->color_secundario ?? '#ff5e2c' }}; /* Naranja por defecto */
            --color-text: #1f2937;
            --color-text-light: #6b7280;
            --color-border: #e5e7eb;
            --color-input-focus: {{ $zona->color_secundario ?? '#ff5e2c' }}33; /* 20% opacidad */
            --radius-md: 0.5rem;
            --radius-lg: 1rem;
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --animation-speed: 0.3s;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background-color: var(--color-background);
            color: var(--color-text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .portal-wrapper {
            width: 100%;
            max-width: 450px;
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

        .portal-main-header {
            background-color: var(--color-primary);
            color: white;
            text-align: center;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .portal-main-header-subtitle {
            font-size: 0.85rem;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .portal-main-header-title {
            font-size: 2.25rem;
            font-weight: 700;
        }

        .portal-zone-banner {
            background-color: var(--color-secondary);
            color: white;
            text-align: center;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            position: relative;
        }
        
        /* Zigzag separator effect mimicking the screenshot */
        .portal-zone-banner::after {
            content: "";
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 5px;
            background-size: 10px 10px;
            background-image: linear-gradient(135deg, var(--color-secondary) 25%, transparent 25%), 
                              linear-gradient(225deg, var(--color-secondary) 25%, transparent 25%);
            background-position: 0 0;
        }

        .portal-content {
            padding: 2.5rem 2rem;
            background-color: white;
        }

        /* Estilos para formularios (Autenticación PIN) */
        .auth-form {
            margin-top: 1.5rem;
            padding: 1.5rem;
            background-color: var(--color-background);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border);
        }

        .auth-form h3 {
            color: var(--color-text);
            margin-bottom: 1rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .auth-form .form-field {
            margin-bottom: 1rem;
        }

        .auth-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--color-text);
        }

        .auth-form input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: 1.25rem;
            text-align: center;
            letter-spacing: 0.2em;
            transition: border-color var(--animation-speed) ease, box-shadow var(--animation-speed) ease;
            background-color: white;
            color: var(--color-text);
            outline: none;
        }

        .auth-form input:focus {
            border-color: var(--color-secondary);
            box-shadow: 0 0 0 3px var(--color-input-focus);
        }

        .auth-form button {
            width: 100%;
            padding: 0.85rem 1.25rem;
            background-color: var(--color-secondary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: background-color var(--animation-speed) ease, opacity var(--animation-speed) ease;
            font-size: 1rem;
            margin-top: 1rem;
        }

        .auth-form button:hover {
            opacity: 0.9;
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
        
        .trial-container {
            margin-top: 1.5rem;
            text-align: center;
            border-top: 1px solid var(--color-border);
            padding-top: 1.5rem;
        }

        .btn-trial {
            background-color: var(--color-text-light);
            color: white;
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: opacity var(--animation-speed) ease;
        }

        .btn-trial:hover {
            opacity: 0.9;
        }
        
        .facebook-btn-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }
        
        .btn-facebook {
            background-color: #1877f2;
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            box-shadow: var(--shadow-md);
            transition: opacity var(--animation-speed) ease;
        }
        
        .btn-facebook:hover {
            opacity: 0.9;
        }
        
        .btn-facebook svg {
            width: 16px;
            height: 16px;
            margin-right: 6px;
            fill: currentColor;
        }

        @media (max-width: 640px) {
            .portal-container {
                border-radius: var(--radius-md);
            }
            .portal-content {
                padding: 1.5rem;
            }
        }
    </style>

</head>
<body>

    <div class="portal-wrapper">
        <div class="portal-container">
            <div class="portal-main-header">
                <div class="portal-main-header-subtitle">Bienvenido a {{ $zona->nombre ?? 'WiFi' }}</div>
                <div class="portal-main-header-title">Bienvenido 1</div>
                
                <!-- Dots simulados (para diseño) -->
                <div style="display: flex; gap: 6px; margin-top: 20px;">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background-color: rgba(255,255,255,0.5);"></div>
                    <div style="width: 16px; height: 8px; border-radius: 4px; background-color: var(--color-secondary);"></div>
                </div>
            </div>

            <div class="portal-zone-banner">
                {{ $zona->nombre ?? 'Sucursal Centro' }}
            </div>

            <div class="portal-content">
                <h1 class="text-2xl font-bold mb-2 text-center">Accede a nuestra WiFi</h1>
                <p class="text-gray-500 mb-6 text-center text-sm">
                    Ingresa tu PIN de acceso para navegar por internet.
                </p>

                <div class="auth-form" id="pin-form">
                    <form name="login" action="{{ $link_login_only ?? ('http://'.($zona->hotspot_host ?? '').'/login') }}" method="post" onSubmit="return doLogin()">
                        <input type="hidden" name="dst" value="{{ $link_orig ?? 'http://google.com' }}" />
                        <input type="hidden" name="popup" value="true" />

                        <div class="form-field">
                            <label for="username">PIN de acceso</label>
                            <input type="text" name="username" id="username" placeholder="· · · ·" autofocus required>
                        </div>
                        
                        <input type="hidden" name="password" id="password" value="">

                        <button type="submit" onclick="document.getElementById('password').value = document.getElementById('username').value;">
                            Conectar con PIN
                        </button>

                        @if(!empty($error))
                            <div class="text-error">
                                {{ $error }}
                            </div>
                        @endif
                    </form>
                </div>
                
                @if(isset($zona) && $zona->trial_enabled)
                    <div class="trial-container">
                        <form action="{{ $link_login_only ?? ('http://'.($zona->hotspot_host ?? '').'/login') }}" method="get">
                            <input type="hidden" name="dst" value="{{ $link_orig_esc ?? 'http://google.com' }}" />
                            <input type="hidden" name="username" value="T-{{ $mac_esc ?? '' }}" />
                            <button type="submit" class="btn-trial">
                                O Conectarse Gratis
                            </button>
                        </form>
                    </div>
                @endif
                
                <p class="mt-8 text-center text-xs text-gray-400">
                    Al conectar, aceptas nuestra política de uso justo y los términos de servicio de la red.
                </p>
            </div>
        </div>
        
        @if(isset($zona) && $zona->facebook_url)
        <div class="facebook-btn-container">
            <a href="{{ $zona->facebook_url }}" target="_blank" class="btn-facebook">
                <svg viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Visítanos en Facebook &rarr;
            </a>
        </div>
        @endif
    </div>

    <!-- Script MD5 para autenticación CHAP de Mikrotik si se llegara a necesitar a futuro -->
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

</body>
</html>