<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visita extends Model
{
    protected $fillable = [
        'nino_id',
        'realizado_por',
        'fecha_visita',
        'motivo',
        'observacion',
        'seguimiento',
    ];

    protected $casts = [
        'fecha_visita' => 'date',
    ];

    public function nino(): BelongsTo
    {
        return $this->belongsTo(Nino::class);
    }

    public function realizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'realizado_por');
    }
}