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
}
