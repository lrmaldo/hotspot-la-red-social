<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $fillable = [
        'zona_id',
        'plan_id',
        'codigo',
        'estado',
        'comprador_nombre',
        'comprador_email',
        'mikrotik_user_id',
        'stripe_session_id',
        'stripe_payment_id',
        'monto_pagado',
        'fecha_venta',
        'fecha_expiracion',
    ];

    protected function casts(): array
    {
        return [
            'zona_id' => 'integer',
            'plan_id' => 'integer',
            'monto_pagado' => 'decimal:2',
            'fecha_venta' => 'datetime',
            'fecha_expiracion' => 'datetime',
        ];
    }

    public static function generarCodigo(): string
    {
        $chars = str_split('ABCDEFGHJKMNPQRSTUVWXYZ23456789');

        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[array_rand($chars)];
            }
        } while (static::where('codigo', $code)->exists());

        return $code;
    }

    /**
     * @return BelongsTo<Zona, $this>
     */
    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class);
    }

    /**
     * @return BelongsTo<Plan, $this>
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * @return HasMany<PagoLog, $this>
     */
    public function pagoLogs(): HasMany
    {
        return $this->hasMany(PagoLog::class);
    }
}
