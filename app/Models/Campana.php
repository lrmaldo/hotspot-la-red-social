<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campana extends Model
{
    protected $fillable = [
        'zona_id',
        'titulo',
        'tipo',
        'file_path',
        'duracion',
        'skip_after_seconds',
        'skip_texto',
        'countdown_visible',
        'countdown_style',
        'prioridad',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'zona_id' => 'integer',
            'duracion' => 'integer',
            'skip_after_seconds' => 'integer',
            'countdown_visible' => 'boolean',
            'prioridad' => 'integer',
            'is_active' => 'boolean',
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
