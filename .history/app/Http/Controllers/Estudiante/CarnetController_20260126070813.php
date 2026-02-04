del admin  y del estudiante 

```php
<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\QrCodeService;
use App\Services\CarnetPdfService;

class CarnetController extends Controller
{
    protected $qrService;
    protected $pdfService;

    public function __construct(QrCodeService $qrService, CarnetPdfService $pdfService)
    {
        $this->qrService = $qrService;
        $this->pdfService = $pdfService;
    }

    /**
     * Mostrar carnet del estudiante
     */
    public function show()
    {
        $usuario = Auth::guard('web')->user();
        $carnet = $usuario->carnet;

        if (!$carnet) {
            return redirect()->route('estudiante.dashboard')
                ->with('error', 'No tienes un carnet asignado. Contacta con administración.');
        }

        // Generar QR en SVG para mostrar en pantalla
        $qrCode = $this->qrService->generarQrCarnet($carnet->codigo_qr);

        return view('estudiante.carnet.show', compact('carnet', 'qrCode'));
    }

    /**
     * Descargar carnet en PDF
     */
    public function descargar()
    {
        $usuario = Auth::guard('web')->user();
        $carnet = $usuario->carnet;

        if (!$carnet) {
            return redirect()->route('estudiante.dashboard')
                ->with('error', 'No tienes un carnet asignado.');
        }

        return $this->pdfService->descargarCarnet($carnet);
    }

    /**
     * Visualizar carnet en el navegador
     */
    public function visualizar()
    {
        $usuario = Auth::guard('web')->user();
        $carnet = $usuario->carnet;

        if (!$carnet) {
            return redirect()->route('estudiante.dashboard')
                ->with('error', 'No tienes un carnet asignado.');
        }

        return $this->pdfService->visualizarCarnet($carnet);
    }
}

```