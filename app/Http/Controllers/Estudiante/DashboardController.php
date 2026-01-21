<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Acceso;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard del estudiante
     */
    public function index()
    {
        $usuario = Auth::guard('web')->user();

        // Obtener el carnet del estudiante
        $carnet = $usuario->carnet;

        // Obtener últimos 5 accesos
        $accesos = Acceso::where('usuario_id', $usuario->id)
            ->with(['laboratorio', 'profesor'])
            ->orderBy('fecha_entrada', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->limit(5)
            ->get();

        // Calcular estadísticas
        $totalAccesos = Acceso::where('usuario_id', $usuario->id)->count();

        $ultimoAcceso = Acceso::where('usuario_id', $usuario->id)
            ->orderBy('fecha_entrada', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->first();

        // Calcular horas totales
        $accesosCompletos = Acceso::where('usuario_id', $usuario->id)
            ->whereNotNull('fecha_salida')
            ->get();

        $horasTotales = 0;
        foreach ($accesosCompletos as $acceso) {
            $duracion = $acceso->calcularDuracion();
            if ($duracion) {
                $horasTotales += $duracion;
            }
        }
        $horasTotales = round($horasTotales / 60, 1); // Convertir a horas

        $stats = [
            'total_accesos' => $totalAccesos,
            'ultimo_acceso' => $ultimoAcceso ? $ultimoAcceso->created_at : null,
            'horas_totales' => $horasTotales,
        ];

        return view('estudiante.dashboard', compact('carnet', 'accesos', 'stats'));
    }
}
