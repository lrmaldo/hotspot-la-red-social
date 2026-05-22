<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoLog extends Model
{
    protected $table = 'pago_logs';

    protected $fillable = [
        'voucher_id',
        'evento',
        'monto',
        'pasarela',
        'referencia_externa',
        'respuesta_json',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'voucher_id' => 'integer',
            'monto' => 'decimal:2',
            'respuesta_json' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Voucher, $this>
     */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }
}
