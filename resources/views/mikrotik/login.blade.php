<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirigiendo...</title>
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="-1">
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            color: #374151;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .spinner {
            border: 4px solid #e5e7eb;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            animation: spin 1s linear infinite;
            margin-bottom: 24px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        h2 {
            font-size: 1.5rem;
            margin: 0 0 10px 0;
            color: #111827;
        }
        p {
            color: #6b7280;
            margin-bottom: 24px;
        }
        .btn {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn:hover {
            background-color: #2563eb;
        }
        .noscript-alert {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    $(if chap-id)
        <noscript>
            <div class="noscript-alert">
                JavaScript es requerido. Habilite JavaScript para continuar.
            </div>
        </noscript>
    $(endif)

    <div class="spinner"></div>
    <h2>Conectando a {{ $zona->nombre }}</h2>
    <p>Si no se redirecciona en unos segundos haga clic en continuar...</p>

    <form name="redirect" action="{{ route('portal.login', $zona->id_personalizado) }}" method="post">
        <input type="hidden" name="mac" value="$(mac)">
        <input type="hidden" name="ip" value="$(ip)">
        <input type="hidden" name="username" value="$(username)">
        <input type="hidden" name="link-login" value="$(link-login)">
        <input type="hidden" name="link-orig" value="$(link-orig)">
        <input type="hidden" name="error" value="$(error)">
        <input type="hidden" name="chap-id" value="$(chap-id)">
        <input type="hidden" name="chap-challenge" value="$(chap-challenge)">
        <input type="hidden" name="link-login-only" value="$(link-login-only)">
        <input type="hidden" name="link-orig-esc" value="$(link-orig-esc)">
        <input type="hidden" name="mac-esc" value="$(mac-esc)">
        <input type="submit" value="Continuar" class="btn">
    </form>

    <script language="JavaScript">
    <!--
        document.redirect.submit();
    //-->
    </script>
</body>
</html>