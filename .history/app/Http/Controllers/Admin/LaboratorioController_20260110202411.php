<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laboratorio;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LaboratorioController extends Controller
{
    /**
     * Listar laboratorios
     */
    public function index()
    {
        $laboratorios = Laboratorio::withCount([
            'accesos' => function ($query) {
                $query->whereNull('hora_salida')
                    ->where('marcado_ausente', false)
                    ->whereDate('fecha_entrada', today());
            }
        ])->get();

        return view('admin.laboratorios.index', compact('laboratorios'));
    }

    /**
     * Generar PDF con QR individual
     */
    public function generarQR($id)
    {
        $laboratorio = Laboratorio::findOrFail($id);

        $pdf = Pdf::loadView('admin.laboratorios.qr-individual', compact('laboratorio'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('QR_' . str_replace(' ', '_', $laboratorio->nombre) . '.pdf');
    }

    /**
     * Descargar todos los QR en un solo PDF
     */
    public function descargarTodosQR()
    {
        $laboratorios = Laboratorio::where('estado', 'activo')->get();

        $pdf = Pdf::loadView('admin.laboratorios.qr-todos', compact('laboratorios'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('QR_Laboratorios_ISTPET_' . date('Y-m-d') . '.pdf');
    }
}
