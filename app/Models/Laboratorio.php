<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    use HasFactory;

    protected $table = 'laboratorios';

    protected $fillable = [
        'nombre',
        'codigo_qr_lab',
        'ubicacion',
        'capacidad',
        'estado',
    ];

    /**
     * Relación: Un laboratorio tiene muchos accesos
     */
    public function accesos()
    {
        return $this->hasMany(Acceso::class, 'laboratorio_id');
    }

    /**
     * Obtener estudiantes actualmente en el laboratorio (sin salida registrada)
     */
    public function estudiantesActuales()
    {
        return $this->accesos()
            ->whereNull('hora_salida')
            ->where('marcado_ausente', false)
            ->whereDate('fecha_entrada', today())
            ->with('usuario')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Verificar si el laboratorio está lleno
     */
    public function estaLleno()
    {
        $ocupacion = $this->accesos()
            ->whereNull('hora_salida')
            ->where('marcado_ausente', false)
            ->whereDate('fecha_entrada', today())
            ->count();

        return $ocupacion >= $this->capacidad;
    }

    /**
     * Obtener ocupación actual (accessor)
     */
    public function getOcupacionActualAttribute()
    {
        return $this->accesos()
            ->whereNull('hora_salida')
            ->where('marcado_ausente', false)
            ->whereDate('fecha_entrada', today())
            ->count();
    }

    /**
     * Obtener porcentaje de ocupación
     */
    public function getPorcentajeOcupacionAttribute()
    {
        if ($this->capacidad == 0) return 0;
        return round(($this->ocupacion_actual / $this->capacidad) * 100);
    }
}
