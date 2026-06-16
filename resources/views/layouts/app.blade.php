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

    {{-- Backdrop móvil --}}
    <div class="opacity-0 pointer-events-none fixed inset-0 z-40 md:hidden bg-gray-900/60 transition-opacity duration-300"
         :class="{ 'opacity-100 pointer-events-auto': open, 'opacity-0 pointer-events-none': !open }"
         @click="open = false">
    </div>

    {{-- Sidebar: max-w-[260px] en móvil para no ocupar toda la pantalla --}}
    <aside class="-translate-x-full fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-white shadow-xl
                  transition-transform duration-300 ease-in-out
                  md:static md:translate-x-0 md:flex md:flex-shrink-0 md:w-64 md:shadow-none md:border-r md:border-gray-200"
           :class="{ 'translate-x-0': open, '-translate-x-full': !open }">

        {{-- Logo / Brand --}}
        <div class="flex items-center justify-between px-5 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center shadow">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 leading-none">La Red Social</p>
                    <p class="text-xs text-gray-400 mt-0.5">Panel Admin</p>
                </div>
            </div>
            <button @click="open = false"
                    class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
                    aria-label="Cerrar menú">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

            @php
            $navItems = [
                ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route' => 'admin.zonas', 'label' => 'Zonas', 'icon' => 'M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0'],
                ['route' => 'admin.campanas', 'label' => 'Campañas', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                ['route' => 'admin.planes', 'label' => 'Planes', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['route' => 'admin.vouchers', 'label' => 'Vouchers', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                ['route' => 'admin.users', 'label' => 'Usuarios', 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
                ['route' => 'admin.configuracion', 'label' => 'Configuración', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'stripe.edit', 'label' => 'Stripe', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
            ];
            @endphp

            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" @click="open = false"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ $active
                              ? 'bg-blue-50 text-blue-700'
                              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="flex-shrink-0 w-5 h-5 {{ $active ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                    </span>
                    <span>{{ $item['label'] }}</span>
                    @if($active)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        {{-- Footer del sidebar: usuario --}}
        <div class="flex-shrink-0 px-4 py-4 border-t border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-bold text-blue-700">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-800 truncate">{{ auth()->user()->name ?? 'Administrador' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Columna principal --}}
    <div class="flex flex-col flex-1 w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="flex-shrink-0 flex h-14 bg-white border-b border-gray-200 items-center justify-between px-4 gap-4">
            <button @click="open = true" type="button"
                    class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                    :aria-expanded="open.toString()"
                    aria-label="Abrir menú">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Título de la página en el top bar (móvil) --}}
            <span class="md:hidden text-sm font-semibold text-gray-700 truncate">{{ $title ?? 'Dashboard' }}</span>

            <div class="flex items-center gap-3 ml-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="hidden sm:inline">Cerrar sesión</span>
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

@livewireScripts
</body>
</html>
