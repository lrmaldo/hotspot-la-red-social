@component('layouts.app')
    @slot('title', 'Dashboard')

    <div class="mb-6 px-4 sm:px-6 md:px-8">
        <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
        <p class="mt-1 text-sm text-gray-500">
            Bienvenido al panel de administración de La Red Social.
        </p>
    </div>

    <div class="mx-4 sm:mx-6 md:mx-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Resumen Zonas -->
            <div class="bg-white rounded-lg shadow p-6 border border-gray-100 flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ \App\Models\Zona::count() }}</h3>
                    <p class="text-sm text-gray-500">Zonas Activas</p>
                </div>
            </div>

            <!-- Resumen Campañas -->
            <div class="bg-white rounded-lg shadow p-6 border border-gray-100 flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ \App\Models\Campana::count() }}</h3>
                    <p class="text-sm text-gray-500">Campañas Registradas</p>
                </div>
            </div>

            <!-- Accesos / Leads (Placeholder) -->
            <div class="bg-white rounded-lg shadow p-6 border border-gray-100 flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">--</h3>
                    <p class="text-sm text-gray-500">Conexiones al Portal</p>
                </div>
            </div>
        </div>
        
        <div class="mt-8 bg-white shadow rounded-lg p-6 border border-gray-100">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Bienvenido a la administración</h3>
            <p class="text-gray-600">Desde este panel puedes gestionar todas las zonas hotspot, las campañas que se visualizan en el portal cautivo y la configuración general de la red.</p>
        </div>
    </div>
@endcomponent