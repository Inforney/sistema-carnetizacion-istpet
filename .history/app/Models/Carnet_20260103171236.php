<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Carnet extends Model
{
    use HasFactory;

    protected $table = 'carnets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario_id',
        'codigo_qr',
        'fecha_emision',
        'fecha_vencimiento',
        'estado',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_emision' => 'date',
            'fecha_vencimiento' => 'date',
        ];
    }

    /**
     * Relación: Un carnet pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Verificar si el carnet está activo
     */
    public function estaActivo()
    {
        return $this->estado === 'activo';
    }

    /**
     * Verificar si el carnet está vencido
     */
    public function estaVencido()
    {
        if ($this->estado === 'vencido') {
            return true;
        }

        if ($this->fecha_vencimiento) {
            return Carbon::parse($this->fecha_vencimiento)->isPast();
        }

        return false;
    }

    /**
     * Verificar si el carnet está bloqueado
     */
    public function estaBloqueado()
    {
        return $this->estado === 'bloqueado';
    }

    /**
     * Activar carnet
     */
    public function activar()
    {
        $this->estado = 'activo';
        $this->save();
    }

    /**
     * Bloquear carnet
     */
    public function bloquear()
    {
        $this->estado = 'bloqueado';
        $this->save();
    }

    /**
     * Scope para carnets activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Obtener URL del QR
     */
    public function getQrUrlAttribute()
    {
        return asset('storage/qr-codes/' . $this->usuario_id . '_qr.png');
    }

    /**
     * Obtener URL del carnet completo
     */
    public function getCarnetUrlAttribute()
    {
        return asset('storage/carnets/' . $this->usuario_id . '_carnet.png');
    }
}
