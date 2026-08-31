<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActividadNino extends Model
{
    protected $table = 'actividad_nino';

    protected $fillable = [
        'actividad_id',
        'nino_id',
    ];

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    public function nino(): BelongsTo
    {
        return $this->belongsTo(Nino::class);
    }
}
