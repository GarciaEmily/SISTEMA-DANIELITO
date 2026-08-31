<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $fillable = [
        'actividad_id',
        'nino_id',
        'fecha',
        'estado',
        'motivo_justificacion',
        'observacion',
        'registrado_por',
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }

    public function nino()
    {
        return $this->belongsTo(Nino::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}