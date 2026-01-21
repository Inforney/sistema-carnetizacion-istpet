<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Carnet;
use App\Models\Acceso;
use App\Models\Laboratorio;
use App\Models\SolicitudPassword;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas principales
        $totalEstudiantes = Usuario::where('tipo_usuario', 'estudiante')->count();

        $carnetsActivos = Carnet::where('estado', 'activo')->count();

        // Estudiantes actualmente en laboratorios (sin salida registrada hoy)
        $estudiantesEnLabs = Acceso::whereNull('hora_salida')
            ->where('marcado_ausente', false)
            ->whereDate('fecha_entrada', today())
            ->distinct('usuario_id')
            ->count();

        // Solicitudes pendientes
        $solicitudesPendientes = SolicitudPassword::where('estado', 'pendiente')->count();

        // Laboratorios con ocupación
        $laboratorios = Laboratorio::where('estado', 'activo')->get();

        // Últimos 10 accesos registrados
        $ultimosAccesos = Acceso::with(['usuario', 'laboratorio'])
            ->whereDate('fecha_entrada', today())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Solicitudes recientes
        $solicitudesRecientes = SolicitudPassword::with('usuario')
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEstudiantes',
            'carnetsActivos',
            'estudiantesEnLabs',
            'solicitudesPendientes',
            'laboratorios',
            'ultimosAccesos',
            'solicitudesRecientes'
        ));
    }
}
