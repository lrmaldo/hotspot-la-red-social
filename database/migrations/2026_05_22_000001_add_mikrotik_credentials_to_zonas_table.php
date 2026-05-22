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
            $table->string('mikrotik_user', 100)->nullable()->after('hotspot_host');
            $table->string('mikrotik_password', 255)->nullable()->after('mikrotik_user');
        });
    }

    public function down(): void
    {
        Schema::table('zonas', function (Blueprint $table) {
            $table->dropColumn(['mikrotik_user', 'mikrotik_password']);
        });
    }
};
