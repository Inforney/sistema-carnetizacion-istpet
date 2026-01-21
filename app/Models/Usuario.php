<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombres',
        'apellidos',
        'cedula',
        'tipo_documento',
        'correo_institucional',
        'celular',
        'ciclo_nivel',
        'carrera',  // ← AGREGAR ESTE
        'foto_url',  // ← AGREGAR ESTE
        'nacionalidad',
        'tipo_usuario',
        'estado',
        'password',
        'reset_token',
        'reset_token_expira',
        'fecha_registro',
    ];

    protected $hidden = [
        'password',
        'reset_token',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'reset_token_expira' => 'datetime',
    ];

    /**
     * Obtener nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombres . ' ' . $this->apellidos;
    }

    /**
     * Obtener documento con tipo
     */
    public function getDocumentoCompletoAttribute()
    {
        $tipos = [
            'cedula' => 'C.I.',
            'pasaporte' => 'Pasaporte',
            'ruc' => 'RUC',
        ];

        return ($tipos[$this->tipo_documento] ?? '') . ' ' . $this->cedula;
    }

    /**
     * Verificar si el usuario está bloqueado
     */
    public function estaBloqueado()
    {
        return $this->estado === 'bloqueado';
    }

    /**
     * Verificar si el usuario está activo
     */
    public function estaActivo()
    {
        return $this->estado === 'activo';
    }

    /**
     * Relación con Carnet
     */
    public function carnet()
    {
        return $this->hasOne(Carnet::class, 'usuario_id');
    }

    /**
     * Relación con Accesos
     */
    public function accesos()
    {
        return $this->hasMany(Acceso::class, 'usuario_id');
    }

    /**
     * Scope para buscar por nombre, apellido o documento
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('nombres', 'like', "%{$termino}%")
                ->orWhere('apellidos', 'like', "%{$termino}%")
                ->orWhere('cedula', 'like', "%{$termino}%");
        });
    }

    /**
     * Scope para filtrar por ciclo
     */
    public function scopePorCiclo($query, $ciclo)
    {
        return $query->where('ciclo_nivel', $ciclo);
    }

    /**
     * Scope para estudiantes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para solo estudiantes
     */
    public function scopeEstudiantes($query)
    {
        return $query->where('tipo_usuario', 'estudiante');
    }
}
