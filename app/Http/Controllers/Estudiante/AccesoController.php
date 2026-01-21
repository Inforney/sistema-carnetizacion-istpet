<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acceso;
use App\Models\Laboratorio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AccesoQRController extends Controller
{
    /**
     * Mostrar vista para escanear QR
     */
    public function mostrarEscaneo()
    {
        $estudiante = Auth::user();

        // Verificar si tiene un acceso activo (sin salida)
        $accesoActivo = Acceso::where('usuario_id', $estudiante->id)
            ->whereNull('hora_salida')
            ->where('marcado_ausente', false)
            ->whereDate('fecha_entrada', today())
            ->with('laboratorio')
            ->first();

        return view('estudiante.acceso.escanear', compact('estudiante', 'accesoActivo'));
    }

    /**
     * Procesar QR escaneado del laboratorio
     */
    public function procesarQR(Request $request)
    {
        $request->validate([
            'codigo_qr' => 'required|string',
        ]);

        $estudiante = Auth::user();
        $codigoQR = $request->codigo_qr;

        // Buscar laboratorio por código QR
        $laboratorio = Laboratorio::where('codigo_qr_lab', $codigoQR)
            ->where('estado', 'activo')
            ->first();

        if (!$laboratorio) {
            return response()->json([
                'success' => false,
                'message' => 'Código QR inválido. Este no corresponde a ningún laboratorio.',
            ], 404);
        }

        // Verificar si el estudiante está bloqueado
        if ($estudiante->estado !== 'activo') {
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta está bloqueada. Contacta con administración.',
            ], 403);
        }

        // Buscar si tiene un acceso activo HOY
        $accesoActivo = Acceso::where('usuario_id', $estudiante->id)
            ->whereNull('hora_salida')
            ->where('marcado_ausente', false)
            ->whereDate('fecha_entrada', today())
            ->first();

        // CASO 1: Ya tiene entrada registrada → Registrar SALIDA
        if ($accesoActivo) {
            // Verificar que sea del mismo laboratorio
            if ($accesoActivo->laboratorio_id != $laboratorio->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tienes entrada registrada en ' . $accesoActivo->laboratorio->nombre .
                        '. Debes registrar salida en ese laboratorio primero.',
                ], 400);
            }

            // Registrar salida
            $accesoActivo->update([
                'fecha_salida' => Carbon::now()->toDateString(),
                'hora_salida' => Carbon::now()->toTimeString(),
            ]);

            $duracion = $accesoActivo->duracion_formateada ?? 'N/A';

            return response()->json([
                'success' => true,
                'accion' => 'salida',
                'message' => '¡Salida registrada exitosamente!',
                'laboratorio' => $laboratorio->nombre,
                'hora_entrada' => $accesoActivo->hora_entrada,
                'hora_salida' => $accesoActivo->hora_salida,
                'duracion' => $duracion,
            ]);
        }

        // CASO 2: No tiene entrada → Registrar ENTRADA
        // Verificar capacidad del laboratorio
        if ($laboratorio->estaLleno()) {
            return response()->json([
                'success' => false,
                'message' => 'El laboratorio está lleno. Capacidad: ' . $laboratorio->capacidad . ' estudiantes.',
            ], 400);
        }

        // Registrar entrada
        $acceso = Acceso::create([
            'usuario_id' => $estudiante->id,
            'laboratorio_id' => $laboratorio->id,
            'profesor_id' => null,
            'fecha_entrada' => Carbon::now()->toDateString(),
            'hora_entrada' => Carbon::now()->toTimeString(),
            'metodo_registro' => 'qr_estudiante',
            'marcado_ausente' => false,
        ]);

        return response()->json([
            'success' => true,
            'accion' => 'entrada',
            'message' => '¡Entrada registrada exitosamente!',
            'laboratorio' => $laboratorio->nombre,
            'ubicacion' => $laboratorio->ubicacion,
            'hora_entrada' => $acceso->hora_entrada,
            'ocupacion' => $laboratorio->ocupacion_actual . '/' . $laboratorio->capacidad,
        ]);
    }

    /**
     * Ver mi historial de accesos
     */
    public function miHistorial()
    {
        $estudiante = Auth::user();

        $accesos = Acceso::where('usuario_id', $estudiante->id)
            ->with('laboratorio')
            ->orderBy('fecha_entrada', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->paginate(20);

        return view('estudiante.acceso.historial', compact('accesos'));
    }
}
