<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Acceso extends Model
{
    use HasFactory;

    protected $table = 'accesos';

    protected $fillable = [
        'usuario_id',
        'profesor_id',
        'laboratorio_id',
        'fecha_entrada',
        'hora_entrada',
        'fecha_salida',
        'hora_salida',
        'equipo_asignado',
        'observaciones',
        'metodo_registro',
        'marcado_ausente',
        'nota_ausencia',
        'profesor_valida_id',
    ];

    protected $casts = [
        'marcado_ausente' => 'boolean',
    ];

    /**
     * Relación: Un acceso pertenece a un usuario (estudiante)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Relación: Un acceso pertenece a un profesor
     */
    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'profesor_id');
    }

    /**
     * Relación: Un acceso pertenece a un laboratorio
     */
    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class, 'laboratorio_id');
    }

    /**
     * Verificar si el estudiante está actualmente en el laboratorio
     */
    public function estaEnLaboratorio()
    {
        return is_null($this->hora_salida) && !$this->marcado_ausente;
    }

    /**
     * Calcular duración del acceso en minutos
     */
    public function calcularDuracion()
    {
        if (!$this->fecha_salida || !$this->hora_salida) {
            return null;
        }

        try {
            $entrada = Carbon::parse($this->fecha_entrada . ' ' . $this->hora_entrada);
            $salida = Carbon::parse($this->fecha_salida . ' ' . $this->hora_salida);
            return $entrada->diffInMinutes($salida);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Obtener duración formateada (accessor)
     */
    public function getDuracionFormateadaAttribute()
    {
        $minutos = $this->calcularDuracion();

        if (!$minutos) {
            return null;
        }

        $horas = floor($minutos / 60);
        $mins = $minutos % 60;

        if ($horas > 0) {
            return "{$horas}h {$mins}m";
        }

        if ($mins < 1) {
            return "< 1m";
        }

        return "{$mins}m";
    }

    /**
     * Scope: Accesos activos (sin salida registrada)
     */
    public function scopeActivos($query)
    {
        return $query->whereNull('hora_salida')
            ->where('marcado_ausente', false);
    }

    /**
     * Scope: Accesos finalizados (con salida registrada)
     */
    public function scopeFinalizados($query)
    {
        return $query->whereNotNull('hora_salida');
    }

    /**
     * Scope: Accesos de hoy
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_entrada', today());
    }

    /**
     * Scope: Accesos por laboratorio
     */
    public function scopePorLaboratorio($query, $laboratorioId)
    {
        return $query->where('laboratorio_id', $laboratorioId);
    }

    /**
     * Scope: Accesos por estudiante
     */
    public function scopePorEstudiante($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    /**
     * Scope: Accesos marcados como ausentes
     */
    public function scopeMarcadosAusentes($query)
    {
        return $query->where('marcado_ausente', true);
    }

    /**
     * Scope: Búsqueda por rango de fechas
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_entrada', [$fechaInicio, $fechaFin]);
    }
}
