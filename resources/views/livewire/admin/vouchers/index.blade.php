<div>
    <div class="mb-6 flex justify-between items-center px-4 sm:px-6 md:px-8">
        <h2 class="text-xl font-bold text-gray-800">Gestión de Vouchers</h2>
        <button wire:click="exportar" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center transition text-sm">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Exportar CSV
        </button>
    </div>

    {{-- Filters --}}
    <div class="mb-4 px-4 sm:px-6 md:px-8 flex flex-wrap gap-3">
        <select wire:model.live="filtroZona" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Todas las zonas</option>
            @foreach($zonas as $z)
                <option value="{{ $z->id }}">{{ $z->nombre }}</option>
            @endforeach
        </select>

        <select wire:model.live="filtroEstado" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Todos los estados</option>
            <option value="pendiente">Pendiente</option>
            <option value="vendido">Vendido</option>
            <option value="usado">Usado</option>
            <option value="expirado">Expirado</option>
        </select>

        <select wire:model.live="filtroMikrotik" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">MikroTik: Todos</option>
            <option value="ok">MikroTik OK</option>
            <option value="error">MikroTik Error</option>
            <option value="pendiente">MikroTik Pendiente</option>
        </select>

        <div class="relative">
            <input type="text" wire:model.live.debounce.300ms="filtroBusqueda" placeholder="Buscar código, email, nombre..."
                   class="border border-gray-300 rounded-md pl-9 pr-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 w-64">
            <svg class="h-4 w-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white shadow rounded-lg overflow-hidden mx-4 sm:mx-6 md:mx-8 border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zona</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comprador</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">MikroTik</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha venta</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiración</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($vouchers as $v)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-mono text-sm font-semibold text-gray-900 tracking-wider">{{ $v->codigo }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $v->zona->nombre }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $v->plan->nombre }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($v->comprador_nombre || $v->comprador_email)
                                    <div class="text-sm text-gray-900">{{ $v->comprador_nombre ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $v->comprador_email ?? '' }}</div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @php
                                    $badgeClasses = match($v->estado) {
                                        'pendiente' => 'bg-gray-100 text-gray-700',
                                        'vendido'   => 'bg-green-100 text-green-800',
                                        'usado'     => 'bg-blue-100 text-blue-800',
                                        'expirado'  => 'bg-red-100 text-red-800',
                                        default     => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClasses }}">
                                    {{ ucfirst($v->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @php
                                    $mkClasses = match($v->mikrotik_sync_status) {
                                        'ok' => 'bg-green-100 text-green-800',
                                        'error' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                    $mkLabel = $v->mikrotik_sync_status ? strtoupper($v->mikrotik_sync_status) : 'PENDIENTE';
                                @endphp
                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $mkClasses }}">
                                    {{ $mkLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ $v->monto_pagado ? '$' . number_format($v->monto_pagado, 2) : '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ $v->fecha_venta?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ $v->fecha_expiracion?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button wire:click="verDetalle({{ $v->id }})" title="Ver detalle"
                                        class="text-indigo-600 hover:text-indigo-900 inline-block p-1 bg-indigo-50 rounded hover:bg-indigo-100">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                @if($v->estado === 'vendido')
                                    @if($v->mikrotik_sync_status !== 'ok')
                                        <button type="button" title="Reintentar sync MikroTik"
                                                class="text-amber-600 hover:text-amber-900 inline-block p-1 bg-amber-50 rounded hover:bg-amber-100"
                                                x-data @click="window.Swal.fire({
                                                    title: '¿Reintentar sincronización?',
                                                    text: 'Se intentará crear nuevamente el usuario en MikroTik.',
                                                    icon: 'question',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#d97706',
                                                    cancelButtonColor: '#64748b',
                                                    confirmButtonText: 'Sí, reintentar',
                                                    cancelButtonText: 'Cancelar'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        @this.reintentarMikrotik({{ $v->id }});
                                                    }
                                                })">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    @endif

                                    <button type="button" title="Anular" class="text-red-600 hover:text-red-900 inline-block p-1 bg-red-50 rounded hover:bg-red-100"
                                            x-data @click="window.Swal.fire({
                                                title: '¿Anular voucher?',
                                                text: 'El voucher será marcado como expirado y el usuario será eliminado del MikroTik.',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonColor: '#3085d6',
                                                confirmButtonText: 'Sí, anular',
                                                cancelButtonText: 'Cancelar'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    @this.anular({{ $v->id }});
                                                }
                                            })">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                No hay vouchers registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($vouchers->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $vouchers->links() }}
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    <div x-data="{ open: @entangle('showDetalle') }" x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        {{-- Backdrop --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="open = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl" @click.outside="open = false">

                @if($voucherDetalle)
                    {{-- Header --}}
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Detalle del Voucher
                            <span class="font-mono tracking-wider ml-2 text-blue-600">{{ $voucherDetalle->codigo }}</span>
                        </h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="px-6 py-4 space-y-6 max-h-[70vh] overflow-y-auto">
                        {{-- Voucher info --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Información del Voucher</h4>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-500">Zona:</span>
                                    <span class="ml-1 text-gray-900 font-medium">{{ $voucherDetalle->zona->nombre }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Plan:</span>
                                    <span class="ml-1 text-gray-900 font-medium">{{ $voucherDetalle->plan->nombre }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Estado:</span>
                                    @php
                                        $badgeClasses = match($voucherDetalle->estado) {
                                            'pendiente' => 'bg-gray-100 text-gray-700',
                                            'vendido'   => 'bg-green-100 text-green-800',
                                            'usado'     => 'bg-blue-100 text-blue-800',
                                            'expirado'  => 'bg-red-100 text-red-800',
                                            default     => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="ml-1 px-2 py-0.5 text-xs font-semibold rounded-full {{ $badgeClasses }}">
                                        {{ ucfirst($voucherDetalle->estado) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Monto pagado:</span>
                                    <span class="ml-1 text-gray-900 font-medium">
                                        {{ $voucherDetalle->monto_pagado ? '$' . number_format($voucherDetalle->monto_pagado, 2) . ' MXN' : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Comprador:</span>
                                    <span class="ml-1 text-gray-900">{{ $voucherDetalle->comprador_nombre ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Email:</span>
                                    <span class="ml-1 text-gray-900">{{ $voucherDetalle->comprador_email ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Fecha venta:</span>
                                    <span class="ml-1 text-gray-900">{{ $voucherDetalle->fecha_venta?->format('d/m/Y H:i:s') ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Expiración:</span>
                                    <span class="ml-1 text-gray-900">{{ $voucherDetalle->fecha_expiracion?->format('d/m/Y H:i:s') ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Sincronización MikroTik</h4>
                            <div class="bg-gray-50 rounded-lg p-3 text-sm space-y-2">
                                <div>
                                    <span class="text-gray-500">Estado:</span>
                                    @php
                                        $mikroBadge = match($voucherDetalle->mikrotik_sync_status) {
                                            'ok' => 'bg-green-100 text-green-800',
                                            'error' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="ml-1 px-2 py-0.5 text-xs font-semibold rounded-full {{ $mikroBadge }}">
                                        {{ $voucherDetalle->mikrotik_sync_status ? strtoupper($voucherDetalle->mikrotik_sync_status) : 'PENDIENTE' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Fecha sync:</span>
                                    <span class="ml-1 text-gray-900">{{ $voucherDetalle->mikrotik_synced_at?->format('d/m/Y H:i:s') ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Mensaje:</span>
                                    <span class="ml-1 text-gray-900">{{ $voucherDetalle->mikrotik_sync_message ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Stripe info --}}
                        @if($voucherDetalle->stripe_session_id || $voucherDetalle->stripe_payment_id)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Stripe</h4>
                                <div class="text-sm space-y-1 bg-gray-50 rounded-lg p-3">
                                    @if($voucherDetalle->stripe_session_id)
                                        <div>
                                            <span class="text-gray-500">Session ID:</span>
                                            <span class="ml-1 font-mono text-xs text-gray-700 break-all">{{ $voucherDetalle->stripe_session_id }}</span>
                                        </div>
                                    @endif
                                    @if($voucherDetalle->stripe_payment_id)
                                        <div>
                                            <span class="text-gray-500">Payment ID:</span>
                                            <span class="ml-1 font-mono text-xs text-gray-700 break-all">{{ $voucherDetalle->stripe_payment_id }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Pago logs --}}
                        @if($voucherDetalle->pagoLogs->isNotEmpty())
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Historial de Pagos</h4>
                                <div class="space-y-2">
                                    @foreach($voucherDetalle->pagoLogs as $log)
                                        <div class="bg-gray-50 rounded-lg p-3 text-sm">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="font-medium text-gray-800">{{ $log->evento }}</span>
                                                @php
                                                    $logBadge = match($log->estado) {
                                                        'aprobado'  => 'bg-green-100 text-green-800',
                                                        'rechazado' => 'bg-red-100 text-red-800',
                                                        default     => 'bg-gray-100 text-gray-700',
                                                    };
                                                @endphp
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $logBadge }}">
                                                    {{ ucfirst($log->estado) }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 space-y-0.5">
                                                <div>Pasarela: {{ $log->pasarela }} | Monto: {{ $log->monto ? '$' . number_format($log->monto, 2) : '-' }}</div>
                                                @if($log->referencia_externa)
                                                    <div>Ref: <span class="font-mono">{{ $log->referencia_externa }}</span></div>
                                                @endif
                                                <div>{{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                        <button @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                            Cerrar
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
