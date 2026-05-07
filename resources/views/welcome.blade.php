<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Welcome') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css','resources/js/app.js'])
    </head>

    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen">
        <header class="w-full lg:max-w-6xl mx-auto px-6 lg:px-8 py-6 flex items-center justify-between">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4 w-full">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="mx-auto w-full max-w-6xl px-6 lg:px-8">
            <section class="grid gap-8 lg:gap-12 lg:grid-cols-2 items-center py-8 lg:py-16">
                <div class="space-y-6 lg:pr-8">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-semibold leading-tight animate-fade-in will-change-transform">{{ config('app.name', 'LaredSocial') }}</h1>
                    <p class="text-gray-600 dark:text-gray-300 max-w-2xl text-base sm:text-lg animate-slide-up will-change-transform">Conecta y gestiona tus zonas y campañas con una interfaz ligera, accesible y moderna. Panel de administración, campañas y zonas optimizadas para móviles.</p>

                    <div class="flex flex-wrap gap-3 items-center mt-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-md bg-[#111827] text-white hover:bg-gray-800 transition-soft animate-fade-in">Ir al panel</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-md bg-gradient-to-r from-indigo-500 to-cyan-400 text-white shadow-lg hover:opacity-95 transition-soft animate-fade-in">Entrar</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-md border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-900 transition-soft animate-fade-in">Crear cuenta</a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="bg-white dark:bg-[#0f1724] rounded-2xl shadow-lg p-6 lg:p-10 transform-gpu will-change-transform animate-slide-up">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center text-white animate-float">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-lg text-gray-900 dark:text-gray-100">Resumen rápido</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Zonas activas: <strong class="text-gray-900 dark:text-white">12</strong> — Campañas: <strong class="text-gray-900 dark:text-white">4</strong></p>
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-900 text-center animate-fade-in">Usuarios</div>
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-900 text-center animate-fade-in">Activos</div>
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-900 text-center animate-fade-in">Conversiones</div>
                    </div>
                </div>
            </section>

            <section class="py-8 lg:py-12">
                <h2 class="text-xl font-semibold mb-6">Características</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <article class="p-6 rounded-xl bg-white dark:bg-gray-900 shadow transition-transform hover:-translate-y-1 will-change-transform animate-slide-up">
                        <h4 class="font-medium mb-2">Gestión de Zonas</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Crea, edita y organiza zonas con facilidad desde el panel.</p>
                    </article>

                    <article class="p-6 rounded-xl bg-white dark:bg-gray-900 shadow transition-transform hover:-translate-y-1 will-change-transform animate-slide-up">
                        <h4 class="font-medium mb-2">Campañas</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Lanza campañas segmentadas y analiza su rendimiento.</p>
                    </article>

                    <article class="p-6 rounded-xl bg-white dark:bg-gray-900 shadow transition-transform hover:-translate-y-1 will-change-transform animate-slide-up">
                        <h4 class="font-medium mb-2">Configuración</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Ajustes flexibles y permisos para administradores.</p>
                    </article>
                </div>
            </section>
        </main>

        <footer class="w-full border-t border-gray-100 dark:border-gray-800 bg-transparent py-6">
            <div class="max-w-6xl mx-auto px-6 lg:px-8 text-sm text-gray-600 dark:text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</div>
        </footer>

    </body>
</html>
