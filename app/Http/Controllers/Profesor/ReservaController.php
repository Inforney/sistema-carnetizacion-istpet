<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReservaLaboratorio;
use App\Models\Laboratorio;
use App\Models\Acceso;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservaController extends Controller
{
    /**
     * Listar reservas del profesor con filtros
     */
    public function index(Request $request)
    {
        $profesor = Auth::guard('profesor')->user();

        // ── Auto-completar reservas cuya hora_fin ya pasó ────────────────
        $reservasParaCompletar = ReservaLaboratorio::where('profesor_id', $profesor->id)
            ->where('estado', 'confirmada')
            ->where(function ($q) {
                $q->whereDate('fecha', '<', today())
                  ->orWhere(function ($q2) {
                      $q2->whereDate('fecha', today())
                         ->where('hora_fin', '<', Carbon::now()->format('H:i:s'));
                  });
            })
            ->get();

        foreach ($reservasParaCompletar as $res) {
            // Cerrar accesos abiertos del laboratorio en esa franja horaria
            Acceso::where('laboratorio_id', $res->laboratorio_id)
                ->whereDate('fecha_entrada', $res->fecha)
                ->whereNull('hora_salida')
                ->where('marcado_ausente', false)
                ->where('hora_entrada', '<=', $res->hora_fin)
                ->update([
                    'hora_salida'  => $res->hora_fin,
                    'fecha_salida' => $res->fecha,
                ]);
        }

        if ($reservasParaCompletar->isNotEmpty()) {
            ReservaLaboratorio::whereIn('id', $reservasParaCompletar->pluck('id'))
                ->update(['estado' => 'completada']);
        }
        // ─────────────────────────────────────────────────────────────────

        $query = ReservaLaboratorio::with(['laboratorio'])
            ->where('profesor_id', $profesor->id)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'asc');

        if ($request->filled('laboratorio_id')) {
            $query->where('laboratorio_id', $request->laboratorio_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        $reservas = $query->paginate(15)->withQueryString();

        $laboratorios = Laboratorio::where('estado', 'activo')->orderBy('nombre')->get();

        // Próximas reservas del día para resumen
        $reservasHoy = ReservaLaboratorio::with('laboratorio')
            ->where('profesor_id', $profesor->id)
            ->whereDate('fecha', today())
            ->whereNotIn('estado', ['cancelada'])
            ->orderBy('hora_inicio')
            ->get();

        return view('profesor.reservas.index', compact('reservas', 'laboratorios', 'reservasHoy', 'profesor'));
    }

    /**
     * Formulario para nueva reserva
     */
    public function create()
    {
        $profesor = Auth::guard('profesor')->user();
        $laboratorios = Laboratorio::where('estado', 'activo')->orderBy('nombre')->get();

        return view('profesor.reservas.create', compact('laboratorios', 'profesor'));
    }

    /**
     * Guardar nueva reserva
     */
    public function store(Request $request)
    {
        $profesor = Auth::guard('profesor')->user();

        $validated = $request->validate([
            'laboratorio_id' => 'required|exists:laboratorios,id',
            'fecha'          => 'required|date|after_or_equal:today',
            'hora_inicio'    => 'required|date_format:H:i',
            'hora_fin'       => 'required|date_format:H:i|after:hora_inicio',
            'materia'        => 'nullable|string|max:150',
            'descripcion'    => 'nullable|string|max:500',
        ]);

        // Verificar conflicto de horario
        if (ReservaLaboratorio::existeConflicto(
            $validated['laboratorio_id'],
            $validated['fecha'],
            $validated['hora_inicio'],
            $validated['hora_fin']
        )) {
            return back()->withInput()->with(
                'error',
                'Ya existe una reserva para ese laboratorio en ese rango horario. Por favor elige otro horario.'
            );
        }

        ReservaLaboratorio::create([
            'profesor_id'    => $profesor->id,
            'laboratorio_id' => $validated['laboratorio_id'],
            'fecha'          => $validated['fecha'],
            'hora_inicio'    => $validated['hora_inicio'],
            'hora_fin'       => $validated['hora_fin'],
            'materia'        => $validated['materia'],
            'descripcion'    => $validated['descripcion'],
            'estado'         => 'confirmada',
        ]);

        return redirect()->route('profesor.reservas.index')
            ->with('success', 'Reserva registrada exitosamente para el ' . Carbon::parse($validated['fecha'])->format('d/m/Y') . ' de ' . $validated['hora_inicio'] . ' a ' . $validated['hora_fin'] . '.');
    }

    /**
     * Finalizar clase manualmente (marcar como completada)
     */
    public function completar($id)
    {
        $profesor = Auth::guard('profesor')->user();

        $reserva = ReservaLaboratorio::where('id', $id)
            ->where('profesor_id', $profesor->id)
            ->firstOrFail();

        if ($reserva->estado !== 'confirmada') {
            return back()->with('error', 'Solo se pueden finalizar reservas confirmadas.');
        }

        $ahora = Carbon::now();

        // Cerrar accesos abiertos del laboratorio en esa franja
        $cerrados = Acceso::where('laboratorio_id', $reserva->laboratorio_id)
            ->whereDate('fecha_entrada', $reserva->fecha)
            ->whereNull('hora_salida')
            ->where('marcado_ausente', false)
            ->where('hora_entrada', '<=', $ahora->format('H:i:s'))
            ->update([
                'hora_salida'  => $ahora->format('H:i:s'),
                'fecha_salida' => $ahora->toDateString(),
            ]);

        $reserva->update(['estado' => 'completada']);

        $msg = 'Clase finalizada. El laboratorio queda libre.';
        if ($cerrados > 0) {
            $msg .= " Se registró la salida de {$cerrados} estudiante(s) que seguían en clase.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Cancelar reserva
     */
    public function destroy($id)
    {
        $profesor = Auth::guard('profesor')->user();

        $reserva = ReservaLaboratorio::where('id', $id)
            ->where('profesor_id', $profesor->id)
            ->firstOrFail();

        if ($reserva->estado === 'completada') {
            return back()->with('error', 'No se puede cancelar una reserva ya completada.');
        }

        $reserva->update(['estado' => 'cancelada']);

        return back()->with('success', 'Reserva cancelada exitosamente.');
    }

    /**
     * Ver detalle de una reserva con lista de estudiantes en esa clase
     */
    public function show($id)
    {
        $profesor = Auth::guard('profesor')->user();

        $reserva = ReservaLaboratorio::with(['laboratorio'])
            ->where('id', $id)
            ->where('profesor_id', $profesor->id)
            ->firstOrFail();

        // Accesos del laboratorio en esa fecha que se solapan con el horario de la reserva
        $accesos = Acceso::with('usuario')
            ->where('laboratorio_id', $reserva->laboratorio_id)
            ->whereDate('fecha_entrada', $reserva->fecha)
            ->where(function ($q) use ($reserva) {
                // Entró durante la ventana de la reserva
                $q->whereBetween('hora_entrada', [$reserva->hora_inicio, $reserva->hora_fin])
                  // O salió durante la ventana
                  ->orWhereBetween('hora_salida', [$reserva->hora_inicio, $reserva->hora_fin])
                  // O estuvo todo el tiempo dentro (entró antes, salió después o sigue adentro)
                  ->orWhere(function ($q2) use ($reserva) {
                      $q2->where('hora_entrada', '<=', $reserva->hora_inicio)
                         ->where(function ($q3) use ($reserva) {
                             $q3->whereNull('hora_salida')
                                ->orWhere('hora_salida', '>=', $reserva->hora_fin);
                         });
                  });
            })
            ->orderBy('hora_entrada')
            ->get();

        // Estadísticas
        $stats = [
            'total'          => $accesos->count(),
            'presentes'      => $accesos->where('marcado_ausente', false)->whereNotNull('hora_salida')->count(),
            'ausentes'       => $accesos->where('marcado_ausente', true)->count(),
            'en_curso'       => $accesos->where('marcado_ausente', false)->whereNull('hora_salida')->count(),
            'salida_temprana'=> $accesos->filter(function ($a) {
                if (!$a->hora_salida || $a->marcado_ausente) return false;
                return Carbon::parse($a->hora_entrada)->diffInMinutes(Carbon::parse($a->hora_salida)) < 30;
            })->count(),
        ];

        return view('profesor.reservas.show', compact('reserva', 'accesos', 'stats'));
    }

    /**
     * Obtener reservas de un laboratorio para un día (AJAX - para verificar disponibilidad)
     */
    public function disponibilidad(Request $request)
    {
        $fecha       = $request->get('fecha', today()->toDateString());
        $labId       = $request->get('laboratorio_id');

        $reservas = ReservaLaboratorio::with('profesor')
            ->where('laboratorio_id', $labId)
            ->whereDate('fecha', $fecha)
            ->whereNotIn('estado', ['cancelada'])
            ->orderBy('hora_inicio')
            ->get()
            ->map(fn($r) => [
                'hora_inicio' => $r->hora_inicio_formateada,
                'hora_fin'    => $r->hora_fin_formateada,
                'materia'     => $r->materia ?? 'Sin materia',
                'profesor'    => $r->profesor->nombreCompleto,
            ]);

        return response()->json($reservas);
    }
}
