<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Profesor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'profesores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombres',
        'apellidos',
        'cedula',
        'correo',
        'celular',
        'especialidad',
        'fecha_ingreso',
        'foto_url',
        'horario',
        'departamento',
        'estado',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: Un profesor valida muchos accesos
     */
    public function accesos()
    {
        return $this->hasMany(Acceso::class);
    }

    /**
     * Obtener nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    /**
     * Verificar si el profesor está activo
     */
    public function estaActivo()
    {
        return $this->estado === 'activo';
    }

    /**
     * Scope para profesores activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}
