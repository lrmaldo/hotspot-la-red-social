@component('mail::message')
# ¡Tu acceso WiFi está listo!

Hola {{ $voucher->comprador_nombre ?? 'cliente' }},
gracias por tu compra en **{{ $voucher->zona->nombre }}**.

## Tu código de acceso

@component('mail::panel')
# {{ $voucher->codigo }}
@endcomponent

**Plan:** {{ $voucher->plan->nombre }}
**Válido por:**
@if($voucher->plan->duracion_minutos < 60)
{{ $voucher->plan->duracion_minutos }} minutos
@elseif($voucher->plan->duracion_minutos < 1440)
{{ intdiv($voucher->plan->duracion_minutos, 60) }} {{ intdiv($voucher->plan->duracion_minutos, 60) === 1 ? 'hora' : 'horas' }}
@elseif($voucher->plan->duracion_minutos < 10080)
{{ intdiv($voucher->plan->duracion_minutos, 1440) }} {{ intdiv($voucher->plan->duracion_minutos, 1440) === 1 ? 'día' : 'días' }}
@else
{{ intdiv($voucher->plan->duracion_minutos, 10080) }} {{ intdiv($voucher->plan->duracion_minutos, 10080) === 1 ? 'semana' : 'semanas' }}
@endif
**Expira:** {{ $voucher->fecha_expiracion->format('d/m/Y H:i') }}

@component('mail::button', ['url' => route('portal.zona', ['zona' => $voucher->zona->id_personalizado])])
Ir al portal WiFi
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
