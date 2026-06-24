<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zona extends Model
{
    protected $fillable = [
        'nombre',
        'id_personalizado',
        'descripcion',
        'hotspot_host',
        'mikrotik_user',
        'mikrotik_password',
        'mikrotik_port',
        'mikrotik_hotspot_profile',
        'mikrotik_interface',
        'vpn_l2tp_user',
        'vpn_l2tp_password',
        'vpn_tunnel_ip',
        'vpn_provisioned_at',
        'tipo_autenticacion',
        'venta_vouchers_activa',
        'trial_enabled',
        'trial_duration_seconds',
        'logo_path',
        'color_primario',
        'color_secundario',
        'facebook_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'venta_vouchers_activa' => 'boolean',
            'mikrotik_password' => 'encrypted',
            'vpn_l2tp_password' => 'encrypted',
            'vpn_provisioned_at' => 'datetime',
            'mikrotik_port' => 'integer',
            'trial_enabled' => 'boolean',
            'trial_duration_seconds' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<Campana, $this>
     */
    public function campanas(): HasMany
    {
        return $this->hasMany(Campana::class);
    }

    /**
     * @return HasMany<Plan, $this>
     */
    public function planes(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * @return HasMany<Voucher, $this>
     */
    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    /**
     * @return HasMany<ZonaTraficoMuestra, $this>
     */
    public function traficoMuestras(): HasMany
    {
        return $this->hasMany(ZonaTraficoMuestra::class);
    }
}
