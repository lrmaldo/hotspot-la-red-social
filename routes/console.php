<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Muestreo periódico del throughput por zona (tráfico del dashboard).
// withoutOverlapping evita que dos corridas se pisen si un router tarda.
Schedule::command('trafico:muestrear')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();
