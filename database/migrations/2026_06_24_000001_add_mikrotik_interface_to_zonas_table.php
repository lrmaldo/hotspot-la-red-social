<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zonas', function (Blueprint $table) {
            // Nombre de la interfaz/VLAN del router cuyo throughput representa
            // el tráfico de esta zona (ej. "vlan40-hs"). Necesario porque un
            // mismo router puede servir varias zonas en interfaces distintas.
            $table->string('mikrotik_interface', 100)->nullable()->after('mikrotik_hotspot_profile');
        });
    }

    public function down(): void
    {
        Schema::table('zonas', function (Blueprint $table) {
            $table->dropColumn('mikrotik_interface');
        });
    }
};
