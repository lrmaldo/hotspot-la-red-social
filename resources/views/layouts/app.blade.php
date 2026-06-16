<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} - La Red Social</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 font-sans antialiased">

<div class="flex h-screen overflow-hidden"
     x-data="{ open: false }"
     @keydown.escape.window="open = false">

    {{-- Backdrop: solo móvil, usa opacity + pointer-events (no display) --}}
    <div class="fixed inset-0 z-40 md:hidden transition-opacity duration-300"
         :class="open ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
         @click="open = false">
        <div class="absolute inset-0 bg-gray-600/75"></div>
    </div>

    {{-- Sidebar: fixed en móvil (overlay), static en desktop --}}
    {{-- -translate-x-full como clase estática evita que el sidebar aparezca antes de que Alpine inicialice --}}
    <aside class="-translate-x-full fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-white border-r border-gray-200
                  transition-transform duration-300 ease-in-out
                  md:static md:translate-x-0 md:flex md:flex-shrink-0"
           :class="{ 'translate-x-0': open, '-translate-x-full': !open }">

        {{-- Header del sidebar --}}
        <div class="flex items-center justify-between flex-shrink-0 px-4 py-5">
            <span class="text-xl font-bold text-blue-600">La Red Social</span>
            <button @click="open = false" class="md:hidden p-1 rounded text-gray-500 hover:text-gray-700" aria-label="Cerrar menú">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 overflow-y-auto px-2 space-y-1">
            <a href="{{ route('admin.dashboard') }}" @click="open = false"
               class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                Dashboard
            </a>
            <a href="{{ route('admin.zonas') }}" @click="open = false"
               class="{{ request()->routeIs('admin.zonas') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                Zonas
            </a>
            <a href="{{ route('admin.campanas') }}" @click="open = false"
               class="{{ request()->routeIs('admin.campanas') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                Campañas
            </a>
            <a href="{{ route('admin.vouchers') }}" @click="open = false"
               class="{{ request()->routeIs('admin.vouchers') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                Vouchers
            </a>
            <a href="{{ route('admin.configuracion') }}" @click="open = false"
               class="{{ request()->routeIs('admin.configuracion') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                Configuración
            </a>
            <a href="{{ route('stripe.edit') }}" @click="open = false"
               class="{{ request()->routeIs('stripe.edit') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                Configuración Stripe
            </a>
        </nav>
    </aside>

    {{-- Columna principal --}}
    <div class="flex flex-col flex-1 w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="flex-shrink-0 flex h-16 bg-white shadow items-center justify-between px-4">
            <button @click="open = true" type="button"
                    class="md:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                    :aria-expanded="open.toString()"
                    aria-label="Abrir menú">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex items-center gap-4 ml-auto">
                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Administrador' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </header>

        {{-- Contenido --}}
        <main class="flex-1 overflow-y-auto bg-gray-50">
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
        </main>

    </div>
</div>

</body>
</html>
