<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zona_id')->constrained('zonas')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('planes')->cascadeOnDelete();
            $table->string('codigo', 20)->unique();
            $table->enum('estado', ['pendiente', 'vendido', 'usado', 'expirado'])->default('pendiente');
            $table->string('comprador_nombre', 150)->nullable();
            $table->string('comprador_email', 150)->nullable();
            $table->string('mikrotik_user_id', 100)->nullable();
            $table->string('stripe_session_id', 255)->nullable();
            $table->string('stripe_payment_id', 255)->nullable();
            $table->decimal('monto_pagado', 8, 2)->nullable();
            $table->timestamp('fecha_venta')->nullable();
            $table->timestamp('fecha_expiracion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
