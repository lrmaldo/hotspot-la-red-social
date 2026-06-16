<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} - La Red Social</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen bg-zinc-100 font-sans antialiased">

<flux:sidebar sticky stashable class="bg-white border-r border-zinc-200">
    <flux:sidebar.header class="px-4 py-5">
        <span class="text-xl font-bold text-blue-600">La Red Social</span>
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
    </flux:sidebar.header>

    <flux:navlist class="px-2">
        <flux:navlist.item href="{{ route('admin.dashboard') }}"
            :current="request()->routeIs('admin.dashboard')">
            Dashboard
        </flux:navlist.item>
        <flux:navlist.item href="{{ route('admin.zonas') }}"
            :current="request()->routeIs('admin.zonas')">
            Zonas
        </flux:navlist.item>
        <flux:navlist.item href="{{ route('admin.campanas') }}"
            :current="request()->routeIs('admin.campanas')">
            Campañas
        </flux:navlist.item>
        <flux:navlist.item href="{{ route('admin.vouchers') }}"
            :current="request()->routeIs('admin.vouchers')">
            Vouchers
        </flux:navlist.item>
        <flux:navlist.item href="{{ route('admin.configuracion') }}"
            :current="request()->routeIs('admin.configuracion')">
            Configuración
        </flux:navlist.item>
        <flux:navlist.item href="{{ route('stripe.edit') }}"
            :current="request()->routeIs('stripe.edit')">
            Configuración Stripe
        </flux:navlist.item>
    </flux:navlist>
</flux:sidebar>

{{-- flux:main establece data-flux-main, necesario para que el CSS de Flux active el grid en <body> --}}
<flux:main class="p-0! flex flex-col min-h-screen">
    <!-- Top bar -->
    <header class="flex-shrink-0 flex h-16 bg-white shadow items-center justify-between px-4">
        <div class="flex items-center gap-2">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-3" />
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Administrador' }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto bg-gray-50">
        <div class="py-6">
            @if (isset($title))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
                </div>
            @endif

            <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</flux:main>

</body>

</html>
