<!DOCTYPE html>
<html lang="es">
<head>
    <title>{{ $title ?? 'Portal Cautivo' }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS del sistema -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    {{-- agreg el js/md5.js--}}
   {{--  <script src="{{ asset('js/md5.js') }}"></script> --}}
    <!-- Stripe.js: pre-cargado para que el walled-garden lo sirva antes de abrir el modal -->
    <script src="https://js.stripe.com/v3/" async></script>
</head>
<body>
    {{ $slot }}
    @livewireScripts
</body>
</html>