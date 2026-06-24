<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZonaTraficoMuestra extends Model
{
    protected $table = 'zona_trafico_muestras';

    protected $fillable = [
        'zona_id',
        'rx_bps',
        'tx_bps',
        'capturado_at',
    ];

    protected function casts(): array
    {
        return [
            'zona_id'      => 'integer',
            'rx_bps'       => 'integer',
            'tx_bps'       => 'integer',
            'capturado_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Zona, $this>
     */
    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class);
    }
}
