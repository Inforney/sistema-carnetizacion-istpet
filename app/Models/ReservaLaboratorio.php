<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReservaLaboratorio extends Model
{
    use HasFactory;

    protected $table = 'reservas_laboratorio';

    protected $fillable = [
        'profesor_id',
        'laboratorio_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'materia',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }

    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class);
    }

    /**
     * Scopes
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    public function scopeProximas($query)
    {
        return $query->whereDate('fecha', '>=', today())
            ->whereNotIn('estado', ['cancelada', 'completada']);
    }

    public function scopePorProfesor($query, $profesorId)
    {
        return $query->where('profesor_id', $profesorId);
    }

    /**
     * Verificar conflicto de horario en el laboratorio
     */
    public static function existeConflicto($laboratorioId, $fecha, $horaInicio, $horaFin, $excluirId = null)
    {
        $query = static::where('laboratorio_id', $laboratorioId)
            ->whereDate('fecha', $fecha)
            ->whereNotIn('estado', ['cancelada'])
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->whereBetween('hora_inicio', [$horaInicio, $horaFin])
                    ->orWhereBetween('hora_fin', [$horaInicio, $horaFin])
                    ->orWhere(function ($q2) use ($horaInicio, $horaFin) {
                        $q2->where('hora_inicio', '<=', $horaInicio)
                            ->where('hora_fin', '>=', $horaFin);
                    });
            });

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
    }

    /**
     * Atributos calculados
     */
    public function getHoraInicioFormateadaAttribute()
    {
        return Carbon::parse($this->hora_inicio)->format('H:i');
    }

    public function getHoraFinFormateadaAttribute()
    {
        return Carbon::parse($this->hora_fin)->format('H:i');
    }

    public function getDuracionMinutosAttribute()
    {
        return Carbon::parse($this->hora_inicio)->diffInMinutes(Carbon::parse($this->hora_fin));
    }

    public function getDuracionFormateadaAttribute()
    {
        $mins = $this->duracion_minutos;
        if ($mins < 60) {
            return $mins . ' min';
        }
        $horas    = intdiv($mins, 60);
        $restante = $mins % 60;
        return $restante > 0 ? $horas . 'h ' . $restante . 'm' : $horas . 'h';
    }

    public function getEstadoTextoAttribute()
    {
        return match ($this->estado) {
            'pendiente'  => 'Pendiente',
            'confirmada' => 'Confirmada',
            'cancelada'  => 'Cancelada',
            'completada' => 'Completada',
            default      => $this->estado,
        };
    }

    public function getEstadoBadgeAttribute()
    {
        return match ($this->estado) {
            'pendiente'   => 'warning',
            'confirmada'  => 'success',
            'cancelada'   => 'danger',
            'completada'  => 'secondary',
            default       => 'light',
        };
    }

    /**
     * ¿La reserva está actualmente en curso? (hoy, entre hora_inicio y hora_fin)
     * Solo para el badge/indicador visual.
     */
    public function getEstaEnCursoAttribute(): bool
    {
        if ($this->estado !== 'confirmada') {
            return false;
        }
        if (!Carbon::parse($this->fecha)->isToday()) {
            return false;
        }
        $ahora = Carbon::now()->format('H:i:s');
        return $this->hora_inicio <= $ahora && $this->hora_fin >= $ahora;
    }

    /**
     * ¿Se puede finalizar la clase manualmente?
     * Sí si: estado=confirmada, es hoy, y la hora_inicio ya pasó.
     * Permite finalizar temprano (antes de hora_fin) o durante la clase.
     */
    public function getPuedeFinalizarAttribute(): bool
    {
        if ($this->estado !== 'confirmada') {
            return false;
        }
        if (!Carbon::parse($this->fecha)->isToday()) {
            return false;
        }
        $ahora = Carbon::now()->format('H:i:s');
        return $this->hora_inicio <= $ahora; // Ya comenzó (sin importar hora_fin)
    }

    /**
     * ¿La reserva es futura? (aún no ha comenzado)
     */
    public function getEsFuturaAttribute(): bool
    {
        if ($this->estado !== 'confirmada') {
            return false;
        }
        $fechaHoraInicio = Carbon::parse($this->fecha->format('Y-m-d') . ' ' . $this->hora_inicio);
        return $fechaHoraInicio->isFuture();
    }
}
