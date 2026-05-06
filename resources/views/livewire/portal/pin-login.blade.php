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
        /* Variables CSS personalizables */
        :root {
            --color-background: #f9fafb;
            --color-primary: #ff5e2c;
            --color-primary-light: rgba(255, 94, 44, 0.1);
            --color-secondary: #ff8159;
            --color-secondary-light: rgba(255, 129, 89, 0.15);
            --color-secondary-dark: #e64a1c;
            --color-text: #1f2937;
            --color-text-light: #6b7280;
            --color-border: #e5e7eb;
            --color-input-focus: #ffeee8;
            --color-button-hover: #e64a1c;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --animation-speed: 0.3s;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background-color: var(--color-background);
            color: var(--color-text);
            line-height: 1.6;
        }

        .portal-container {
            max-width: 500px;
            margin: 20px auto;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transition: transform var(--animation-speed) ease, box-shadow var(--animation-speed) ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .portal-header {
            background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 18px;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .portal-content {
            padding: 2.5rem;
            background-color: white;
            position: relative;
        }

        .portal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 12px;
            background: linear-gradient(135deg, var(--color-secondary-light) 25%, transparent 25%) -10px 0,
                        linear-gradient(225deg, var(--color-secondary-light) 25%, transparent 25%) -10px 0,
                        linear-gradient(315deg, var(--color-secondary-light) 25%, transparent 25%),
                        linear-gradient(45deg, var(--color-secondary-light) 25%, transparent 25%);
            background-size: 20px 20px;
            opacity: 0.5;
        }

        /* Estilos para formularios (Autenticación PIN) */
        .auth-form {
            margin-top: 1.5rem;
            padding: 1.5rem;
            background-color: #f9fafb;
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border);
        }

        .auth-form h3 {
            color: var(--color-primary);
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            text-align: center;
        }

        .auth-form .form-field {
            margin-bottom: 1rem;
        }

        .auth-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            color: var(--color-text);
        }

        .auth-form input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: 1rem;
            transition: border-color var(--animation-speed) ease, box-shadow var(--animation-speed) ease;
            background-color: white;
        }

        .auth-form input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-input-focus);
        }

        .auth-form button {
            width: 100%;
            padding: 0.75rem 1.25rem;
            background-color: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: background-color var(--animation-speed) ease;
            font-size: 1.05rem;
            margin-top: 0.5rem;
        }

        .auth-form button:hover {
            background-color: var(--color-button-hover);
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
            background-color: #10b981;
            color: white;
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background-color var(--animation-speed) ease;
        }

        .btn-trial:hover {
            background-color: #059669;
        }

        @media (max-width: 640px) {
            .portal-container {
                margin: 10px;
                width: calc(100% - 20px);
            }
            .portal-content {
                padding: 1.5rem;
            }
        }
    </style>

</head>
<body>

    <div class="portal-container">
        <div class="portal-header">
            {{ $zona->nombre ?? 'Portal Cautivo' }}
        </div>

        <div class="portal-content">
            <h1 class="text-2xl font-bold mb-4 text-center">Accede a nuestra WiFi</h1>
            <p class="text-gray-600 mb-6 text-center">
                Ingresa tu PIN de acceso para navegar por internet.
            </p>

            <div class="auth-form" id="pin-form">
                <form name="login" action="{{ $link_login_only ?? ('http://'.($zona->hotspot_host ?? '').'/login') }}" method="post" onSubmit="return doLogin()">
                    <input type="hidden" name="dst" value="{{ $link_orig ?? 'http://google.com' }}" />
                    <input type="hidden" name="popup" value="true" />

                    <div class="form-field">
                        <label for="username">PIN de acceso</label>
                        <input type="text" name="username" id="username" placeholder="••••" autofocus required
                               class="tracking-widest font-bold text-center text-lg">
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
                    <p class="text-sm text-gray-500 mb-3">¿No tienes un PIN?</p>
                    <form action="{{ $link_login_only ?? ('http://'.($zona->hotspot_host ?? '').'/login') }}" method="get">
                        <input type="hidden" name="dst" value="{{ $link_orig_esc ?? 'http://google.com' }}" />
                        <input type="hidden" name="username" value="T-{{ $mac_esc ?? '' }}" />
                        <button type="submit" class="btn-trial">
                            Conectarse Gratis
                        </button>
                    </form>
                </div>
            @endif
            
            <p class="mt-6 text-center text-xs text-gray-400">
                Al conectar, aceptas nuestra política de uso justo y los términos de servicio de la red.
            </p>
        </div>
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