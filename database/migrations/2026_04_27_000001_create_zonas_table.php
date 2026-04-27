<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zonas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('id_personalizado', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->string('hotspot_host', 100);
            $table->enum('tipo_autenticacion', ['pin', 'sin_autenticacion'])->default('pin');
            $table->boolean('venta_vouchers_activa')->default(false);
            $table->string('logo_path')->nullable();
            $table->string('color_primario', 7)->default('#1a56db');
            $table->string('color_secundario', 7)->default('#ffffff');
            $table->string('facebook_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zonas');
    }
};
