<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campanas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zona_id')->constrained('zonas')->cascadeOnDelete();
            $table->string('titulo', 150)->nullable();
            $table->enum('tipo', ['imagen', 'video'])->default('imagen');
            $table->string('file_path');
            $table->unsignedTinyInteger('duracion')->default(8);
            $table->unsignedTinyInteger('skip_after_seconds')->nullable();
            $table->string('skip_texto', 50)->default('Omitir en {s}s');
            $table->boolean('countdown_visible')->default(true);
            $table->enum('countdown_style', ['barra', 'circular'])->default('barra');
            $table->unsignedTinyInteger('prioridad')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campanas');
    }
};
