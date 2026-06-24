<div>
    <div class="mx-4 sm:mx-6 md:mx-8 space-y-6">

        @can('dashboard.vouchers')
        {{-- Fila de métricas de vouchers --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Vouchers totales --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Vouchers generados</p>
                    <span class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-bold text-gray-900">{{ number_format($vouchersTotales) }}</p>
                <p class="mt-1 text-xs text-gray-400">Total histórico (pagados)</p>
            </div>

            {{-- Vouchers del mes --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Vouchers del mes</p>
                    <span class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-bold text-gray-900">{{ number_format($vouchersMes) }}</p>
                <p class="mt-1 text-xs text-gray-400">Vendidos este mes</p>
            </div>

            {{-- Vouchers activos --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Vouchers activos</p>
                    <span class="p-2 rounded-lg bg-green-50 text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-bold text-gray-900">{{ number_format($vouchersActivos) }}</p>
                <p class="mt-1 text-xs text-gray-400">Con sesión vigente</p>
            </div>

            {{-- Ingresos del mes --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Ingresos del mes</p>
                    <span class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-bold text-gray-900">${{ number_format($ingresosMes, 2) }}</p>
                <p class="mt-1 text-xs text-gray-400">Total histórico: ${{ number_format($ingresosTotales, 2) }}</p>
            </div>
        </div>

        @endcan

        @can('dashboard.ganancias')
        {{-- Gráfica de ganancias mes a mes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-semibold text-gray-800">Ganancias mes a mes</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Últimos {{ $meses }} meses · ingresos por vouchers pagados</p>
                </div>
            </div>

            @php
                $maxTotal = collect($ganancias)->max('total') ?: 1;
                $maxBarPx = 170; // altura máxima de barra en px (alturas absolutas evitan el colapso de height:% en flex items)
            @endphp

            <div class="flex items-end justify-between gap-2 sm:gap-4">
                @foreach($ganancias as $g)
                    @php $barPx = $g['total'] > 0 ? max(6, (int) round(($g['total'] / $maxTotal) * $maxBarPx)) : 2; @endphp
                    <div class="flex-1 flex flex-col items-center justify-end group">
                        {{-- Monto encima de la barra --}}
                        <span class="text-[11px] font-semibold text-gray-700 mb-1 whitespace-nowrap">
                            ${{ $g['total'] >= 1000 ? number_format($g['total'] / 1000, 1) . 'k' : number_format($g['total'], 0) }}
                        </span>
                        {{-- Barra --}}
                        <div class="w-full max-w-[44px] rounded-t-lg bg-blue-500 hover:bg-blue-600 transition-all duration-300"
                             style="height: {{ $barPx }}px"
                             title="{{ $g['cantidad'] }} vouchers · ${{ number_format($g['total'], 2) }}">
                        </div>
                        {{-- Etiqueta del mes --}}
                        <span class="mt-2 text-[11px] font-medium text-gray-500 whitespace-nowrap">{{ $g['label'] }}</span>
                        <span class="text-[10px] text-gray-400">{{ $g['cantidad'] }} vch</span>
                    </div>
                @endforeach
            </div>
        </div>

        @endcan

        @can('dashboard.trafico')
        {{-- Tráfico promedio por zona (vía API MikroTik, muestreo cada 5 min) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="mb-4">
                <h3 class="text-base font-semibold text-gray-800">Tráfico promedio por zona</h3>
                <p class="text-xs text-gray-400 mt-0.5">Promedio de las últimas 24 h · throughput de la interfaz del router</p>
            </div>

            @if(empty($traficoZonas))
                <div class="rounded-lg bg-gray-50 border border-gray-200 px-4 py-6 text-center">
                    <p class="text-sm text-gray-600">Ninguna zona tiene interfaz configurada todavía.</p>
                    <p class="text-xs text-gray-400 mt-1">Defínela en cada zona (campo «Interfaz para tráfico») para empezar a medir.</p>
                </div>
            @else
                <div class="overflow-x-auto -mx-2">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                <th class="px-2 py-2">Zona</th>
                                <th class="px-2 py-2 text-right">↓ Bajada (prom.)</th>
                                <th class="px-2 py-2 text-right">↑ Subida (prom.)</th>
                                <th class="px-2 py-2 text-right hidden sm:table-cell">Última medición</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($traficoZonas as $t)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-2.5 font-medium text-gray-800">{{ $t['nombre'] }}</td>
                                    <td class="px-2 py-2.5 text-right tabular-nums {{ $t['muestras'] ? 'text-blue-700' : 'text-gray-400' }}">
                                        {{ $t['muestras'] ? number_format($t['rx_mbps'], 2) . ' Mbps' : '—' }}
                                    </td>
                                    <td class="px-2 py-2.5 text-right tabular-nums {{ $t['muestras'] ? 'text-emerald-700' : 'text-gray-400' }}">
                                        {{ $t['muestras'] ? number_format($t['tx_mbps'], 2) . ' Mbps' : '—' }}
                                    </td>
                                    <td class="px-2 py-2.5 text-right text-gray-400 text-xs hidden sm:table-cell">
                                        {{ $t['ultima'] ?? 'sin datos' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @endcan

        @can('dashboard.resumen')
        {{-- Resumen zonas / campañas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('admin.zonas') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center hover:border-blue-200 hover:shadow transition">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $zonasCount }}</h3>
                    <p class="text-sm text-gray-500">Zonas registradas</p>
                </div>
            </a>
            <a href="{{ route('admin.campanas') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center hover:border-indigo-200 hover:shadow transition">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $campanasCount }}</h3>
                    <p class="text-sm text-gray-500">Campañas registradas</p>
                </div>
            </a>
        </div>
        @endcan
    </div>
</div>
