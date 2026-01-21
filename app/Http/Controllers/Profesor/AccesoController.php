<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acceso;
use App\Models\Laboratorio;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AccesoController extends Controller
{
    /**
     * Vista principal de control de accesos
     */
    public function index()
    {
        $profesor = Auth::guard('profesor')->user();

        $laboratorios = Laboratorio::where('estado', 'activo')->get();

        $accesosHoy = Acceso::whereDate('fecha_entrada', today())
            ->with(['usuario', 'laboratorio'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('profesor.accesos.index', compact('laboratorios', 'accesosHoy', 'profesor'));
    }

    /**
     * Registrar entrada de un estudiante
     */
    public function registrarEntrada(Request $request)
    {
        $request->validate([
            'estudiante_cedula' => 'required',
            'laboratorio_id' => 'required|exists:laboratorios,id',
        ]);

        $estudiante = Usuario::where('cedula', $request->estudiante_cedula)
            ->where('tipo_usuario', 'estudiante')
            ->where('estado', 'activo')
            ->first();

        if (!$estudiante) {
            return back()->with('error', 'Estudiante no encontrado o cuenta bloqueada.');
        }

        // Verificar si ya tiene entrada sin salida
        $accesoActivo = Acceso::where('usuario_id', $estudiante->id)
            ->whereNull('hora_salida')
            ->whereDate('fecha_entrada', today())
            ->first();

        if ($accesoActivo) {
            return back()->with('error', 'El estudiante ya tiene una entrada registrada en ' .
                $accesoActivo->laboratorio->nombre . ' sin salida.');
        }

        // Registrar entrada
        Acceso::create([
            'usuario_id' => $estudiante->id,
            'laboratorio_id' => $request->laboratorio_id,
            'profesor_id' => Auth::guard('profesor')->id(),
            'fecha_entrada' => Carbon::now()->toDateString(),
            'hora_entrada' => Carbon::now()->toTimeString(),
            'metodo_registro' => 'manual_profesor',
        ]);

        return back()->with('success', 'Entrada registrada para ' . $estudiante->nombreCompleto);
    }

    /**
     * Registrar salida de un estudiante
     */
    public function registrarSalida(Request $request)
    {
        $request->validate([
            'estudiante_cedula' => 'required',
        ]);

        $estudiante = Usuario::where('cedula', $request->estudiante_cedula)
            ->where('tipo_usuario', 'estudiante')
            ->first();

        if (!$estudiante) {
            return back()->with('error', 'Estudiante no encontrado.');
        }

        $acceso = Acceso::where('usuario_id', $estudiante->id)
            ->whereNull('hora_salida')
            ->whereDate('fecha_entrada', today())
            ->first();

        if (!$acceso) {
            return back()->with('error', 'No hay entrada registrada para este estudiante hoy.');
        }

        $acceso->update([
            'fecha_salida' => Carbon::now()->toDateString(),
            'hora_salida' => Carbon::now()->toTimeString(),
        ]);

        return back()->with('success', 'Salida registrada para ' . $estudiante->nombreCompleto);
    }

    /**
     * Registrar salida directa desde botón
     */
    public function registrarSalidaDirecta($id)
    {
        $acceso = Acceso::findOrFail($id);

        if ($acceso->hora_salida) {
            return back()->with('error', 'Este acceso ya tiene salida registrada.');
        }

        $acceso->update([
            'fecha_salida' => Carbon::now()->toDateString(),
            'hora_salida' => Carbon::now()->toTimeString(),
        ]);

        return back()->with('success', 'Salida registrada exitosamente.');
    }

    /**
     * Ver historial de accesos
     */
    public function historial(Request $request)
    {
        $query = Acceso::with(['usuario', 'laboratorio', 'profesor']);

        // Filtros
        if ($request->fecha_desde) {
            $query->whereDate('fecha_entrada', '>=', $request->fecha_desde);
        }
        if ($request->fecha_hasta) {
            $query->whereDate('fecha_entrada', '<=', $request->fecha_hasta);
        }
        if ($request->laboratorio_id) {
            $query->where('laboratorio_id', $request->laboratorio_id);
        }

        $accesos = $query->orderBy('fecha_entrada', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->paginate(20);

        $laboratorios = Laboratorio::where('estado', 'activo')->get();

        return view('profesor.accesos.historial', compact('accesos', 'laboratorios'));
    }

    /**
     * Ver estudiantes actualmente en un laboratorio
     */
    public function estudiantesEnLab($id)
    {
        $laboratorio = Laboratorio::findOrFail($id);

        $estudiantesActivos = Acceso::where('laboratorio_id', $id)
            ->whereNull('hora_salida')
            ->where('marcado_ausente', false)
            ->whereDate('fecha_entrada', today())
            ->with('usuario')
            ->orderBy('hora_entrada', 'desc')
            ->get();

        return view('profesor.accesos.estudiantes-lab', compact('laboratorio', 'estudiantesActivos'));
    }

    /**
     * Marcar estudiante como ausente (eliminar registro fraudulento)
     */
    public function marcarAusente(Request $request, $id)
    {
        $request->validate([
            'nota' => 'required|string|max:500',
        ]);

        $acceso = Acceso::findOrFail($id);

        // Verificar que no tenga salida registrada
        if ($acceso->hora_salida) {
            return back()->with('error', 'No se puede marcar como ausente un acceso con salida registrada.');
        }

        // Guardar el laboratorio_id para redirigir después
        $laboratorioId = $acceso->laboratorio_id;

        // Marcar como ausente
        $acceso->update([
            'marcado_ausente' => true,
            'nota_ausencia' => $request->nota,
            'profesor_valida_id' => Auth::guard('profesor')->id(),
        ]);

        // Redirigir explícitamente a la misma página para forzar recarga
        return redirect()->route('profesor.accesos.estudiantes-lab', $laboratorioId)
            ->with('success', 'Estudiante marcado como ausente. El registro ha sido invalidado.');
    }

    /**
     * Búsqueda AJAX de estudiantes
     */
    public function buscarEstudiante(Request $request)
    {
        $term = $request->get('term', '');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $estudiantes = Usuario::where('tipo_usuario', 'estudiante')
            ->where('estado', 'activo')
            ->where(function ($query) use ($term) {
                $query->where('cedula', 'like', "%{$term}%")
                    ->orWhere('apellidos', 'like', "%{$term}%")
                    ->orWhere('nombres', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get();

        return response()->json($estudiantes->map(function ($est) {
            return [
                'id' => $est->id,
                'cedula' => $est->cedula,
                'nombreCompleto' => $est->nombres . ' ' . $est->apellidos,
                'nombres' => $est->nombres,
                'apellidos' => $est->apellidos,
            ];
        }));
    }
}
