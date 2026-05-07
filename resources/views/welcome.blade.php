<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'WiFi Portal') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 min-h-screen flex flex-col">
    
    <!-- Header -->
    <nav class="w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center animate-fade-in">
        <div class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-violet-500 bg-clip-text text-transparent">
            {{ config('app.name', 'WiFi Portal') }}
        </div>
        <div class="flex gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('dashboard') }}" class="font-medium hover:text-indigo-600 transition-colors">Panel</a>
                @else
                    <a href="{{ route('login') }}" class="font-medium hover:text-indigo-600 transition-colors">Entrar</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg">Empezar</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-grow flex items-center justify-center px-6">
        <div class="max-w-3xl text-center space-y-8">
            <div class="inline-block px-4 py-1.5 mb-4 text-sm font-medium tracking-wide text-indigo-600 uppercase bg-indigo-50 rounded-full dark:bg-indigo-900/30 dark:text-indigo-400 animate-fade-in">
                Gestión Inteligente de Redes
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight animate-slide-up" style="animation-delay: 100ms">
                Conecta tu mundo <br/>
                <span class="text-indigo-600">sin límites.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto leading-relaxed animate-slide-up" style="animation-delay: 200ms">
                La plataforma definitiva para gestionar tus zonas WiFi, campañas publicitarias y usuarios desde un solo lugar. Simple, rápido y escalable.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4 animate-slide-up" style="animation-delay: 300ms">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-gray-900 text-white dark:bg-white dark:text-gray-900 rounded-xl font-semibold shadow-xl hover:scale-105 transition-transform duration-300">
                        Ir al Panel de Control
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-indigo-600 text-white rounded-xl font-semibold shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 hover:scale-105 transition-all duration-300">
                        Comenzar ahora
                    </a>
                    <a href="#features" class="px-8 py-4 bg-white text-gray-900 border border-gray-200 rounded-xl font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                        Saber más
                    </a>
                @endauth
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-7xl mx-auto px-6 py-12 border-t border-gray-100 dark:border-gray-900 mt-20">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
            </div>
            <div class="flex gap-8 text-sm font-medium text-gray-500">
                <a href="#" class="hover:text-indigo-600 transition-colors">Privacidad</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Términos</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Soporte</a>
            </div>
        </div>
    </footer>

    <style>
        /* Estilos de emergencia para asegurar colores si Tailwind falla */
        :root {
            --indigo-600: #4f46e5;
            --indigo-700: #4338ca;
            --gray-50: #f9fafb;
            --gray-600: #4b5563;
            --gray-900: #111827;
        }
        .bg-gray-50 { background-color: var(--gray-50); }
        .bg-indigo-600 { background-color: var(--indigo-600); }
        .text-indigo-600 { color: var(--indigo-600); }
        .text-gray-600 { color: var(--gray-600); }
        .text-gray-900 { color: var(--gray-900); }
        .border-gray-100 { border-color: #f3f4f6; }
        .shadow-xl { box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); }
        
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }
        .animate-slide-up {
            opacity: 0;
            animation: slide-up 0.8s ease-out forwards;
        }
        .hover\:scale-105:hover { transform: scale(1.05); }
        .transition-all { transition-property: all; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
    </style>
</body>
</html>

