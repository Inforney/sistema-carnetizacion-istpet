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

        // Búsqueda por nombre o cédula del estudiante
        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->whereHas('usuario', function ($q) use ($termino) {
                $q->where('nombres', 'like', "%{$termino}%")
                  ->orWhere('apellidos', 'like', "%{$termino}%")
                  ->orWhere('cedula', 'like', "%{$termino}%");
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_entrada', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_entrada', '<=', $request->fecha_hasta);
        }

        if ($request->filled('laboratorio_id')) {
            $query->where('laboratorio_id', $request->laboratorio_id);
        }

        if ($request->filled('profesor_id')) {
            $query->where('profesor_id', $request->profesor_id);
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activo') {
                $query->whereNull('hora_salida')->where('marcado_ausente', false);
            } elseif ($request->estado === 'ausente') {
                $query->where('marcado_ausente', true);
            } else {
                $query->whereNotNull('hora_salida');
            }
        }

        $accesos = $query->orderBy('fecha_entrada', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->paginate(25)
            ->withQueryString();

        // Mini stats para la barra superior
        $miniStats = [
            'activos_ahora' => Acceso::whereNull('hora_salida')
                ->where('marcado_ausente', false)
                ->whereDate('fecha_entrada', Carbon::today())
                ->count(),
            'accesos_hoy'   => Acceso::whereDate('fecha_entrada', Carbon::today())->count(),
            'accesos_mes'   => Acceso::whereMonth('fecha_entrada', Carbon::now()->month)
                ->whereYear('fecha_entrada', Carbon::now()->year)
                ->count(),
        ];

        $laboratorios = Laboratorio::orderBy('nombre')->get();
        $profesores   = Profesor::orderBy('apellidos')->get();

        return view('admin.accesos.index', compact('accesos', 'laboratorios', 'profesores', 'miniStats'));
    }

    /**
     * Ver estadísticas de accesos
     */
    public function estadisticas()
    {
        $hoy = Carbon::today();
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        // Estadísticas generales
        $stats = [
            'total_accesos'      => Acceso::count(),
            'accesos_hoy'        => Acceso::whereDate('fecha_entrada', $hoy)->count(),
            'accesos_mes'        => Acceso::whereMonth('fecha_entrada', $mesActual)
                                          ->whereYear('fecha_entrada', $anioActual)->count(),
            'activos_ahora'      => Acceso::whereNull('hora_salida')
                                          ->where('marcado_ausente', false)
                                          ->whereDate('fecha_entrada', $hoy)->count(),
            'estudiantes_unicos' => Acceso::distinct('usuario_id')->count('usuario_id'),
        ];

        // Top 10 estudiantes con más accesos (para reconocimientos)
        $topEstudiantes = Acceso::selectRaw('usuario_id, COUNT(*) as total_accesos')
            ->groupBy('usuario_id')
            ->orderBy('total_accesos', 'desc')
            ->with('usuario')
            ->take(10)
            ->get();

        // Top 5 laboratorios más usados
        $topLaboratorios = Acceso::selectRaw('laboratorio_id, COUNT(*) as total_accesos')
            ->groupBy('laboratorio_id')
            ->orderBy('total_accesos', 'desc')
            ->with('laboratorio')
            ->take(5)
            ->get();

        // Accesos por día (últimos 30 días)
        $accesosPorDia = [];
        for ($i = 29; $i >= 0; $i--) {
            $fecha = $hoy->copy()->subDays($i);
            $accesosPorDia[] = [
                'fecha' => $fecha->format('d/m'),
                'total' => Acceso::whereDate('fecha_entrada', $fecha)->count(),
            ];
        }

        // Accesos por hora del día (0–23)
        $accesosPorHora = [];
        for ($h = 0; $h <= 23; $h++) {
            $accesosPorHora[$h] = Acceso::whereRaw('HOUR(hora_entrada) = ?', [$h])->count();
        }

        // Top 5 estudiantes este mes (para reconocimiento mensual)
        $topMes = Acceso::selectRaw('usuario_id, COUNT(*) as total_mes')
            ->whereMonth('fecha_entrada', $mesActual)
            ->whereYear('fecha_entrada', $anioActual)
            ->groupBy('usuario_id')
            ->orderBy('total_mes', 'desc')
            ->with('usuario')
            ->take(5)
            ->get();

        return view('admin.accesos.estadisticas', compact(
            'stats', 'topEstudiantes', 'topLaboratorios',
            'accesosPorDia', 'accesosPorHora', 'topMes'
        ));
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
