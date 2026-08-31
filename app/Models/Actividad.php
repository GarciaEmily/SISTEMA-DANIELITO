<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Grupo;
use App\Models\Nino;

class Actividad extends Model
{
    protected $table = 'actividades';

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_actividad',
        'tipo',
        'grupo_id',
        'activa',
        'creado_por',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
        public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'actividad_grupo', 'actividad_id', 'grupo_id');
    }
    public function ninos()
{
    return $this->belongsToMany(Nino::class, 'actividad_nino', 'actividad_id', 'nino_id');
}
public function asistencias()
{
    return $this->hasMany(Asistencia::class);
}

}