<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zona_trafico_muestras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zona_id')->constrained('zonas')->cascadeOnDelete();
            // Tasa instantánea en bits por segundo al momento de la muestra.
            $table->unsignedBigInteger('rx_bps')->default(0); // bajada
            $table->unsignedBigInteger('tx_bps')->default(0); // subida
            $table->timestamp('capturado_at')->index();
            $table->timestamps();

            $table->index(['zona_id', 'capturado_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zona_trafico_muestras');
    }
};
