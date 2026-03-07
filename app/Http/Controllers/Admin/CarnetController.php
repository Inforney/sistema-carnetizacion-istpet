<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carnet;
use App\Models\Usuario;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;

class CarnetController extends Controller
{
    /**
     * Listar carnets con filtros de búsqueda
     */
    public function index(Request $request)
    {
        $query = Carnet::with('usuario')->orderBy('created_at', 'desc');

        // Búsqueda por estudiante (nombre o cédula)
        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->whereHas('usuario', function ($q) use ($termino) {
                $q->where('nombres', 'like', "%{$termino}%")
                    ->orWhere('apellidos', 'like', "%{$termino}%")
                    ->orWhere('cedula', 'like', "%{$termino}%");
            });
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por vencimiento próximo (30 días)
        if ($request->filled('por_vencer')) {
            $query->where('fecha_vencimiento', '<=', Carbon::now()->addDays(30))
                ->where('estado', 'activo');
        }

        $carnets = $query->paginate(20)->withQueryString();

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

        if ($usuario->carnet) {
            return back()->with('error', 'Este estudiante ya tiene un carnet asignado.');
        }

        $codigoQr = 'ISTPET-' . Carbon::now()->year . '-' . strtoupper($usuario->cedula);

        Carnet::create([
            'usuario_id'        => $usuario->id,
            'codigo_qr'         => $codigoQr,
            'fecha_emision'     => Carbon::now(),
            'fecha_vencimiento' => self::calcularFinPeriodoAcademico(Carbon::now()),
            'estado'            => 'activo',
        ]);

        return redirect()->route('admin.carnets.index')->with(
            'success',
            'Carnet generado exitosamente para ' . $usuario->nombreCompleto
        );
    }

    /**
     * Generar carnets masivos con transacción
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

        DB::transaction(function () use ($estudiantesSinCarnet, &$generados) {
            foreach ($estudiantesSinCarnet as $estudiante) {
                $codigoQr = 'ISTPET-' . Carbon::now()->year . '-' . strtoupper($estudiante->cedula);

                Carnet::create([
                    'usuario_id'        => $estudiante->id,
                    'codigo_qr'         => $codigoQr,
                    'fecha_emision'     => Carbon::now(),
                    'fecha_vencimiento' => self::calcularFinPeriodoAcademico(Carbon::now()),
                    'estado'            => 'activo',
                ]);

                $generados++;
            }
        });

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
                ->setPaper('a4', 'portrait')
                ->setOption('margin-top', 0)
                ->setOption('margin-right', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0)
                ->setOption('dpi', 96);

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
     * Renovar carnet al fin del período académico actual
     */
    public function renovar($id)
    {
        $carnet = Carnet::findOrFail($id);
        $ahora = Carbon::now();
        $finPeriodo = self::calcularFinPeriodoAcademico($ahora);

        [$nombrePeriodo] = self::infoPeridoAcademico($ahora);

        $carnet->update([
            'fecha_emision'     => $ahora,
            'fecha_vencimiento' => $finPeriodo,
            'estado'            => 'activo',
        ]);

        return back()->with('success',
            "Carnet renovado para el período {$nombrePeriodo}. Válido hasta " . $finPeriodo->format('d/m/Y') . '.'
        );
    }

    /**
     * Calcula la fecha de fin del período académico en curso.
     * Período 1: Abril – Octubre  → vence el 31 de octubre del mismo año
     * Período 2: Noviembre – Marzo → vence el 31 de marzo del año siguiente (si nov/dic)
     *                                 o del mismo año (si ene/feb/mar)
     */
    public static function calcularFinPeriodoAcademico(Carbon $desde): Carbon
    {
        $mes = $desde->month;
        if ($mes >= 4 && $mes <= 10) {
            return Carbon::create($desde->year, 10, 31)->endOfDay();
        } elseif ($mes >= 11) {
            return Carbon::create($desde->year + 1, 3, 31)->endOfDay();
        } else {
            return Carbon::create($desde->year, 3, 31)->endOfDay();
        }
    }

    /**
     * Devuelve [nombrePeriodo, fechaFinFormateada] para la fecha dada.
     */
    public static function infoPeridoAcademico(Carbon $desde): array
    {
        $mes = $desde->month;
        if ($mes >= 4 && $mes <= 10) {
            $nombre = 'Abril–Octubre ' . $desde->year;
            $fin    = Carbon::create($desde->year, 10, 31)->format('d/m/Y');
        } elseif ($mes >= 11) {
            $nombre = 'Noviembre ' . $desde->year . '–Marzo ' . ($desde->year + 1);
            $fin    = Carbon::create($desde->year + 1, 3, 31)->format('d/m/Y');
        } else {
            $nombre = 'Noviembre ' . ($desde->year - 1) . '–Marzo ' . $desde->year;
            $fin    = Carbon::create($desde->year, 3, 31)->format('d/m/Y');
        }
        return [$nombre, $fin];
    }
}
