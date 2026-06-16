<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} - La Red Social</title>

    <!-- Assets compilados (Tailwind vía Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="h-screen flex overflow-hidden bg-gray-100 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" class="fixed inset-0 flex z-40 md:hidden" style="display: none;"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
        <div class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-white">
            <div class="flex items-center flex-shrink-0 px-4">
                <span class="text-xl font-bold text-blue-600">La Red Social</span>
            </div>
            <div class="mt-5 flex-1 h-0 overflow-y-auto">
                <nav class="px-2 space-y-1 text-gray-900">
                    <a href="{{ route('admin.dashboard') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md hover:bg-gray-50 hover:text-blue-600">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.zonas') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md hover:bg-gray-50 hover:text-blue-600">
                        Zonas
                    </a>
                    <a href="{{ route('admin.campanas') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md hover:bg-gray-50 hover:text-blue-600">
                        Campañas
                    </a>
                    <a href="{{ route('admin.vouchers') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md hover:bg-gray-50 hover:text-blue-600">
                        Vouchers
                    </a>
                    <a href="{{ route('admin.configuracion') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md hover:bg-gray-50 hover:text-blue-600">
                        Configuración
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden md:flex md:flex-shrink-0 w-64 bg-white border-r border-gray-200">
        <div class="flex flex-col w-full px-2 pt-5 pb-4 overflow-y-auto">
            <div class="flex items-center flex-shrink-0 px-4 mb-5">
                <span class="text-2xl font-bold text-blue-600">La Red Social</span>
            </div>
            <nav class="flex-1 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    Dashboard
                </a>
                <a href="{{ route('admin.zonas') }}"
                    class="{{ request()->routeIs('admin.zonas') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    Zonas
                </a>
                <a href="{{ route('admin.campanas') }}"
                    class="{{ request()->routeIs('admin.campanas') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    Campañas
                </a>
                <a href="{{ route('admin.vouchers') }}"
                    class="{{ request()->routeIs('admin.vouchers') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    Vouchers
                </a>
                <a href="{{ route('admin.configuracion') }}"
                    class="{{ request()->routeIs('admin.configuracion') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    Configuración
                </a>
{{-- route('stripe.edit')" wire:navigate>{{ __('Stripe') }} --}}
                <a href="{{ route('stripe.edit') }}"
                    class="{{ request()->routeIs('stripe.edit') ? 'bg-gray-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    Configuración Stripe
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Column -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
        <!-- Top bar -->
        <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow justify-between px-4">
            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button @click="sidebarOpen = true" type="button"
                    class="text-gray-500 focus:outline-none hover:text-gray-700">
                    <span class="sr-only">Abrir menú</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <div class="flex flex-1 items-center justify-end">
                <div class="ml-4 flex items-center gap-4">
                    <span
                        class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Administrador' }}</span>
                    <!-- Logout form -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <main class="flex-1 relative overflow-y-auto focus:outline-none bg-gray-50">
            <div class="py-6">
                <!-- Page Title -->
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

</body>

</html>
