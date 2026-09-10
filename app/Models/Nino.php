<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nino extends Model
{
    protected $table = 'ninos';

    protected $fillable = [
        'codigo',
        'maestro_id',
        'grupo_id',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'edad',
        'contacto',
        'curso',
        'colegio',
        'vulnerable',
        'motivo_vulnerabilidad',
        'observaciones',
        'fue_al_encuentro',
        'bautizado',
        'asiste_iglesia',
        'nombre_iglesia',
        'nombre_celula',
        'activo',
        'latitud',  // <-- Agrega esto
    'longitud', // <-- Agrega esto
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'vulnerable' => 'boolean',
        'fue_al_encuentro' => 'boolean',
        'bautizado' => 'boolean',
        'asiste_iglesia' => 'boolean',
        'activo' => 'boolean',
    ];

    public function maestro(): BelongsTo
    {
        return $this->belongsTo(User::class, 'maestro_id');
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function visitas(): HasMany
    {
        return $this->hasMany(Visita::class);
    }
    public function actividades()
{
    return $this->belongsToMany(Actividad::class, 'actividad_nino', 'nino_id', 'actividad_id');
}

    public function actividadNinos(): HasMany
    {
        return $this->hasMany(ActividadNino::class);
    }

    public function scopeCumpleanosDelMes($query)
    {
        return $query->whereNotNull('fecha_nacimiento')
            ->whereMonth('fecha_nacimiento', now()->month);
    }
}
