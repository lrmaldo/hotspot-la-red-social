<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zonas', function (Blueprint $table): void {
            $table->unsignedSmallInteger('mikrotik_port')->nullable()->after('mikrotik_password');
            $table->string('mikrotik_hotspot_profile', 100)->nullable()->after('mikrotik_port');
        });
    }

    public function down(): void
    {
        Schema::table('zonas', function (Blueprint $table): void {
            $table->dropColumn([
                'mikrotik_port',
                'mikrotik_hotspot_profile',
            ]);
        });
    }
};
