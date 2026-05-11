<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Panel de Administración</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f3f4f6;
            --text-color: #1f2937;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: var(--text-color);
        }

        .login-wrapper {
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 1.75rem;
            font-weight: 800;
            color: #111827;
        }

        .login-header p {
            margin: 0;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            outline: none;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .btn-submit {
            width: 100%;
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.85rem 1rem;
            font-size: 1.05rem;
            font-weight: 700;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.4);
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(1px);
        }

        .login-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            margin-top: 0.5rem;
        }

        .checkbox-container input {
            cursor: pointer;
            width: 1.1rem;
            height: 1.1rem;
            accent-color: var(--primary);
        }

        .text-error {
            color: #dc2626;
            background: #fef2f2;
            border: 1px solid #fecaca;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
        }

        .text-success {
            color: #059669;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
        }
        
        .logo-placeholder {
            width: 60px;
            height: 60px;
            background-color: var(--primary);
            border-radius: 12px;
            margin: 0 auto 1rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.3);
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-header">
        <div class="logo-placeholder">
            <svg fill="currentColor" viewBox="0 0 24 24" style="width:32px; height:32px;"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>
        </div>
        <h1>Comenzar</h1>
        <p>Inicia sesión en tu cuenta</p>
    </div>

    @if (session('status'))
        <div class="text-success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="text-error">
            Revisa tus credenciales e intenta nuevamente.
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus placeholder="admin@ejemplo.com">
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>

        <div class="checkbox-container">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember" style="margin-bottom: 0; font-weight: normal; cursor: pointer;">Recordarme en este equipo</label>
        </div>

        <button type="submit" class="btn-submit">
            Iniciar Sesión
        </button>
    </form>

    <div class="login-footer">
        @if (Route::has('password.request'))
            <div><a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a></div>
        @endif
        
        @if (Route::has('register'))
            <div style="margin-top: 1rem; color: #6b7280;">
                ¿No tienes una cuenta? <a href="{{ route('register') }}">Regístrate gratis</a>
            </div>
        @endif
    </div>
</div>

</body>
</html>
