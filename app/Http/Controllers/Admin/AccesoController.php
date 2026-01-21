<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acceso;
use App\Models\Usuario;
use App\Models\Laboratorio;
use App\Models\Profesor;
use Carbon\Carbon;

class AccesoController extends Controller
{
    /**
     * Ver estudiantes en un laboratorio específico
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
     * Listar todos los accesos
     */
    public function index(Request $request)
    {
        $query = Acceso::with(['usuario', 'laboratorio', 'profesor']);

        // Filtros
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_entrada', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_entrada', '<=', $request->fecha_hasta);
        }

        if ($request->filled('estudiante_id')) {
            $query->where('usuario_id', $request->estudiante_id);
        }

        if ($request->filled('laboratorio_id')) {
            $query->where('laboratorio_id', $request->laboratorio_id);
        }

        if ($request->filled('profesor_id')) {
            $query->where('profesor_id', $request->profesor_id);
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activo') {
                $query->whereNull('hora_salida');
            } else {
                $query->whereNotNull('hora_salida');
            }
        }

        $accesos = $query->orderBy('fecha_entrada', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->paginate(20);

        // Datos para filtros
        $estudiantes = Usuario::where('tipo_usuario', 'estudiante')
            ->orderBy('apellidos')
            ->get();

        $laboratorios = Laboratorio::orderBy('nombre')->get();

        $profesores = Profesor::orderBy('apellidos')->get();

        return view('admin.accesos.index', compact('accesos', 'estudiantes', 'laboratorios', 'profesores'));
    }

    /**
     * Ver estadísticas de accesos
     */
    public function estadisticas()
    {
        // Estadísticas generales
        $stats = [
            'total_accesos' => Acceso::count(),
            'accesos_hoy' => Acceso::whereDate('fecha_entrada', Carbon::today())->count(),
            'accesos_mes' => Acceso::whereMonth('fecha_entrada', Carbon::now()->month)->count(),
            'estudiantes_activos' => Acceso::whereNull('hora_salida')->distinct('usuario_id')->count(),
        ];

        // Top 5 estudiantes con más accesos
        $topEstudiantes = Acceso::selectRaw('usuario_id, COUNT(*) as total_accesos')
            ->groupBy('usuario_id')
            ->orderBy('total_accesos', 'desc')
            ->with('usuario')
            ->take(5)
            ->get();

        // Top 3 laboratorios más usados
        $topLaboratorios = Acceso::selectRaw('laboratorio_id, COUNT(*) as total_accesos')
            ->groupBy('laboratorio_id')
            ->orderBy('total_accesos', 'desc')
            ->with('laboratorio')
            ->take(3)
            ->get();

        // Accesos por día (últimos 7 días)
        $accesosPorDia = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $accesosPorDia[] = [
                'fecha' => $fecha->format('d/m'),
                'total' => Acceso::whereDate('fecha_entrada', $fecha)->count(),
            ];
        }

        return view('admin.accesos.estadisticas', compact('stats', 'topEstudiantes', 'topLaboratorios', 'accesosPorDia'));
    }

    /**
     * Ver detalles de un acceso
     */
    public function show($id)
    {
        $acceso = Acceso::with(['usuario', 'laboratorio', 'profesor'])->findOrFail($id);

        return view('admin.accesos.show', compact('acceso'));
    }

    /**
     * Eliminar un acceso
     */
    public function destroy($id)
    {
        $acceso = Acceso::findOrFail($id);
        $acceso->delete();

        return redirect()->route('admin.accesos.index')->with('success', 'Acceso eliminado exitosamente.');
    }
}
