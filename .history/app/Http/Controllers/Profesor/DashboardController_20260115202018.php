<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Acceso;
use App\Models\Laboratorio;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard del profesor
     */
    public function index()
    {
        $profesor = Auth::guard('profesor')->user();

        // Obtener laboratorios disponibles
        $laboratorios = Laboratorio::all();

        // Accesos de hoy validados por este profesor
        $accesosHoy = Acceso::where('profesor_id', $profesor->id)
            ->whereDate('fecha_entrada', today())
            ->with(['usuario', 'laboratorio'])
            ->orderBy('hora_entrada', 'desc')
            ->get();

        // Estudiantes actualmente en laboratorios
        $estudiantesActivos = Acceso::whereNull('fecha_salida')
            ->whereDate('fecha_entrada', today())
            ->with(['usuario', 'laboratorio'])
            ->get();

        // Estadísticas
        $totalAccesosHoy = Acceso::whereDate('fecha_entrada', today())->count();
        $accesosValidados = Acceso::where('profesor_id', $profesor->id)
            ->whereDate('fecha_entrada', today())
            ->count();

        $stats = [
            'total_accesos_hoy' => $totalAccesosHoy,
            'accesos_validados' => $accesosValidados,
            'estudiantes_activos' => $estudiantesActivos->count(),
        ];

        return view('profesor.dashboard', compact('laboratorios', 'accesosHoy', 'estudiantesActivos', 'stats'));
    }

    /**
     * Ver detalles de un acceso específico
     */
    public function verDetalleAcceso($id)
    {
        $acceso = Acceso::with([
            'usuario',
            'laboratorio',
            'profesor',
            'registradoPor'
        ])->findOrFail($id);

        return view('profesor.acceso-detalle', compact('acceso'));
    }
}
