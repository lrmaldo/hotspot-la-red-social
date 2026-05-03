<!DOCTYPE html>
<html>
<head>
    <title>Conectado con éxito</title>
    <meta http-equiv="refresh" content="2; url=$(link-orig)">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="-1">
    <style>
        body { font-family: sans-serif; text-align: center; margin-top: 50px; background-color: #f3f4f6; color: #1f2937; }
        .success { color: #10b981; font-size: 48px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="success">✓</div>
    <h1>¡Conexión Exitosa!</h1>
    <p>Ya puedes navegar por Internet.</p>
    <p><small>Redirigiendo a tu destino original en unos segundos... <a href="$(link-orig)">Clic aquí si no redirige</a></small></p>
    
    <script type="text/javascript">
        setTimeout(function() {
            location.href = '$(link-orig)';
        }, 2000);
    </script>
</body>
</html>