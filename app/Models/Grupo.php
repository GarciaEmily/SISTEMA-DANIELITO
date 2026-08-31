<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function ninos(): HasMany
    {
        return $this->hasMany(Nino::class);
    }
public function actividades()
{
    return $this->belongsToMany(Actividad::class, 'actividad_grupo', 'grupo_id', 'actividad_id');
}
}
