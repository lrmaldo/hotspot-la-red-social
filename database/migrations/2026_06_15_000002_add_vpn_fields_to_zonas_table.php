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
            $table->string('vpn_l2tp_user', 64)->nullable()->after('mikrotik_hotspot_profile');
            $table->string('vpn_l2tp_password', 255)->nullable()->after('vpn_l2tp_user');
            $table->string('vpn_tunnel_ip', 45)->nullable()->after('vpn_l2tp_password');
            $table->timestamp('vpn_provisioned_at')->nullable()->after('vpn_tunnel_ip');
        });
    }

    public function down(): void
    {
        Schema::table('zonas', function (Blueprint $table): void {
            $table->dropColumn([
                'vpn_l2tp_user',
                'vpn_l2tp_password',
                'vpn_tunnel_ip',
                'vpn_provisioned_at',
            ]);
        });
    }
};
