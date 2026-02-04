<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carnet;
use App\Models\Usuario;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CarnetController extends Controller
{
    /**
     * Listar carnets
     */
    public function index()
    {
        $carnets = Carnet::with('usuario')->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Carnet::count(),
            'activos' => Carnet::where('estado', 'activo')->count(),
            'bloqueados' => Carnet::where('estado', 'bloqueado')->count(),
            'por_vencer' => Carnet::where('estado', 'activo')
                ->whereNotNull('fecha_vencimiento')
                ->where('fecha_vencimiento', '<=', Carbon::now()->addDays(30))
                ->count(),
        ];

        return view('admin.carnets.index', compact('carnets', 'stats'));
    }

    /**
     * Mostrar formulario para crear carnet
     */
    public function create()
    {
        // Obtener estudiantes sin carnet
        $estudiantesSinCarnet = Usuario::where('tipo_usuario', 'estudiante')
            ->whereDoesntHave('carnet')
            ->orderBy('apellidos')
            ->get();

        return view('admin.carnets.create', compact('estudiantesSinCarnet'));
    }

    /**
     * Guardar nuevo carnet
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
        ]);

        $usuario = Usuario::findOrFail($request->usuario_id);

        // Verificar que no tenga carnet
        if ($usuario->carnet) {
            return back()->with('error', 'Este estudiante ya tiene un carnet asignado.');
        }

        // Generar código QR único
        $codigoQr = 'ISTPET-2026-' . strtoupper($usuario->cedula);

        Carnet::create([
            'usuario_id' => $usuario->id,
            'codigo_qr' => $codigoQr,
            'fecha_emision' => Carbon::now(),
            'fecha_vencimiento' => Carbon::now()->addYears(4),
            'estado' => 'activo',
        ]);

        return redirect()->route('admin.carnets.index')->with(
            'success',
            'Carnet generado exitosamente para ' . $usuario->nombreCompleto
        );
    }

    /**
     * Generar carnets masivos
     */
    public function generarMasivo()
    {
        $estudiantesSinCarnet = Usuario::where('tipo_usuario', 'estudiante')
            ->whereDoesntHave('carnet')
            ->get();

        if ($estudiantesSinCarnet->count() === 0) {
            return back()->with('info', 'Todos los estudiantes ya tienen carnet asignado.');
        }

        $generados = 0;
        foreach ($estudiantesSinCarnet as $estudiante) {
            $codigoQr = 'ISTPET-2026-' . strtoupper($estudiante->cedula);

            Carnet::create([
                'usuario_id' => $estudiante->id,
                'codigo_qr' => $codigoQr,
                'fecha_emision' => Carbon::now(),
                'fecha_vencimiento' => Carbon::now()->addYears(4),
                'estado' => 'activo',
            ]);

            $generados++;
        }

        return back()->with('success', "Se generaron {$generados} carnets exitosamente.");
    }

    /**
     * Descargar todos los carnets en PDF
     */
    public function descargarMasivo()
    {
        try {
            $carnets = Carnet::with('usuario')->where('estado', 'activo')->get();

            if ($carnets->count() === 0) {
                return back()->with('error', 'No hay carnets activos para descargar.');
            }

            $pdf = Pdf::loadView('admin.carnets.pdf-masivo', compact('carnets'))
                ->setPaper('letter', 'portrait');

            return $pdf->download('carnets_istpet_' . date('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalle de carnet
     */
    public function show($id)
    {
        $carnet = Carnet::with('usuario')->findOrFail($id);
        return view('admin.carnets.show', compact('carnet'));
    }

    /**
     * Descargar carnet individual
     */
    public function descargar($id)
    {
        try {
            $carnet = Carnet::with('usuario')->findOrFail($id);

            $pdf = Pdf::loadView('admin.carnets.pdf-individual', compact('carnet'))
                ->setPaper([0, 0, 226, 359], 'portrait');

            return $pdf->download('carnet_' . $carnet->usuario->cedula . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    /**
     * Bloquear/Desbloquear carnet
     */
    public function toggleEstado($id)
    {
        $carnet = Carnet::findOrFail($id);

        $nuevoEstado = $carnet->estado === 'activo' ? 'bloqueado' : 'activo';
        $carnet->update(['estado' => $nuevoEstado]);

        $mensaje = $nuevoEstado === 'bloqueado'
            ? 'Carnet bloqueado exitosamente.'
            : 'Carnet activado exitosamente.';

        return back()->with('success', $mensaje);
    }

    /**
     * Renovar carnet
     */
    public function renovar($id)
    {
        $carnet = Carnet::findOrFail($id);

        $carnet->update([
            'fecha_emision' => Carbon::now(),
            'fecha_vencimiento' => Carbon::now()->addYears(4),
            'estado' => 'activo',
        ]);

        return back()->with('success', 'Carnet renovado exitosamente por 4 años más.');
    }
}
