<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table): void {
            $table->string('mikrotik_sync_status', 20)->nullable()->after('mikrotik_user_id');
            $table->text('mikrotik_sync_message')->nullable()->after('mikrotik_sync_status');
            $table->timestamp('mikrotik_synced_at')->nullable()->after('mikrotik_sync_message');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table): void {
            $table->dropColumn([
                'mikrotik_sync_status',
                'mikrotik_sync_message',
                'mikrotik_synced_at',
            ]);
        });
    }
};
