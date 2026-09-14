<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'nombre',
        'apellido',
        'email',
        'password',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function ninos(): HasMany
    {
        return $this->hasMany(Nino::class, 'maestro_id');
    }

    public function gruposComoMaestro(): HasMany
    {
        return $this->hasMany(Grupo::class, 'maestro_id');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class, 'creado_por');
    }

    public function asistenciasRegistradas(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'registrado_por');
    }

    public function visitasRealizadas(): HasMany
    {
        return $this->hasMany(Visita::class, 'realizado_por');
    }
}