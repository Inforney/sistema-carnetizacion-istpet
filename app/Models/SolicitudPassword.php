<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudPassword extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_password';

    protected $fillable = [
        'usuario_id',
        'tipo_usuario',
        'documento',
        'correo',
        'estado',
        'atendida_por_admin_id',
        'notas_admin',
    ];

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Scope para solicitudes pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para solicitudes atendidas
     */
    public function scopeAtendidas($query)
    {
        return $query->where('estado', 'atendida');
    }
}
