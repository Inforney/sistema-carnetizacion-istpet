<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CarnetController extends Controller
{
    /**
     * Mostrar carnet del estudiante (NUEVO DISEÑO)
     */
    public function show()
    {
        $usuario = Auth::guard('web')->user();
        $carnet = $usuario->carnet;

        if (!$carnet) {
            return redirect()->route('estudiante.dashboard')
                ->with('error', 'No tienes un carnet asignado. Contacta con administración.');
        }

        // USAR VISTA NUEVA: show-nuevo.blade.php
        return view('estudiante.carnet.show-nuevo', compact('carnet', 'usuario'));
    }

    /**
     * Mostrar carnet del estudiante (DISEÑO VIEJO - BACKUP)
     */
    public function showViejo()
    {
        $usuario = Auth::guard('web')->user();
        $carnet = $usuario->carnet;

        if (!$carnet) {
            return redirect()->route('estudiante.dashboard')
                ->with('error', 'No tienes un carnet asignado. Contacta con administración.');
        }

        // Generar QR en SVG para mostrar en pantalla (si usas un servicio)
        // $qrCode = $this->qrService->generarQrCarnet($carnet->codigo_qr);

        return view('estudiante.carnet.show', compact('carnet'));
    }

    /**
     * Descargar carnet en PDF (NUEVO DISEÑO)
     */
    public function descargar()
    {
        try {
            $usuario = Auth::guard('web')->user();
            $carnet = $usuario->carnet;

            if (!$carnet) {
                return redirect()->route('estudiante.dashboard')
                    ->with('error', 'No tienes un carnet asignado.');
            }

            // Formatear fechas
            $fechaEmision = Carbon::parse($carnet->fecha_emision)->format('d/m/Y');
            $fechaVencimiento = Carbon::parse($carnet->fecha_vencimiento)->format('d/m/Y');

            // USAR VISTA NUEVA: pdf-nuevo.blade.php
            $pdf = Pdf::loadView('carnets.pdf-nuevo', compact('carnet', 'usuario', 'fechaEmision', 'fechaVencimiento'))
                ->setPaper('A4', 'portrait');

            return $pdf->download('mi_carnet_' . $usuario->cedula . '_' . date('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('estudiante.carnet.show')
                ->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    /**
     * Visualizar carnet en el navegador (NUEVO DISEÑO)
     */
    public function visualizar()
    {
        try {
            $usuario = Auth::guard('web')->user();
            $carnet = $usuario->carnet;

            if (!$carnet) {
                return redirect()->route('estudiante.dashboard')
                    ->with('error', 'No tienes un carnet asignado.');
            }

            // Formatear fechas
            $fechaEmision = Carbon::parse($carnet->fecha_emision)->format('d/m/Y');
            $fechaVencimiento = Carbon::parse($carnet->fecha_vencimiento)->format('d/m/Y');

            // USAR VISTA NUEVA: pdf-nuevo.blade.php
            $pdf = Pdf::loadView('carnets.pdf-nuevo', compact('carnet', 'usuario', 'fechaEmision', 'fechaVencimiento'))
                ->setPaper('A4', 'portrait');

            return $pdf->stream('mi_carnet_' . $usuario->cedula . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('estudiante.carnet.show')
                ->with('error', 'Error al visualizar el PDF: ' . $e->getMessage());
        }
    }
}
