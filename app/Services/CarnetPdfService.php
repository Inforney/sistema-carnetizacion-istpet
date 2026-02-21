<?php

namespace App\Services;

use App\Models\Carnet;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CarnetPdfService
{
    protected $qrService;

    public function __construct(QrCodeService $qrService)
    {
        $this->qrService = $qrService;
    }

    /**
     * Generar PDF del carnet
     * 
     * @param Carnet $carnet
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generarCarnetPdf(Carnet $carnet)
    {
        $usuario = $carnet->usuario;

        // Datos para la vista (SIN QR imagen)
        $data = [
            'carnet' => $carnet,
            'usuario' => $usuario,
            'fechaEmision' => Carbon::parse($carnet->fecha_emision)->format('d/m/Y'),
            'fechaVencimiento' => Carbon::parse($carnet->fecha_vencimiento)->format('d/m/Y'),
            'generadoEn' => Carbon::now()->format('d/m/Y H:i'),
        ];

        // Generar PDF
        $pdf = Pdf::loadView('carnets.pdf', $data);

        // Configurar tamaño de carnet (85.6mm x 135mm - tamaño vertical)
        // Dimensiones: 85.6mm = 242.65 points, 135mm = 382.68 points
        $pdf->setPaper([0, 0, 242.65, 382.68], 'portrait')
            ->setOption('margin-top', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0);

        return $pdf;
    }

    /**
     * Descargar carnet como PDF
     * 
     * @param Carnet $carnet
     * @return \Illuminate\Http\Response
     */
    public function descargarCarnet(Carnet $carnet)
    {
        $pdf = $this->generarCarnetPdf($carnet);

        $filename = 'carnet_' . $carnet->usuario->cedula . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Ver carnet en el navegador
     * 
     * @param Carnet $carnet
     * @return \Illuminate\Http\Response
     */
    public function visualizarCarnet(Carnet $carnet)
    {
        $pdf = $this->generarCarnetPdf($carnet);

        return $pdf->stream('carnet.pdf');
    }

    /**
     * Generar carnets masivos para múltiples estudiantes
     * 
     * @param \Illuminate\Database\Eloquent\Collection $carnets
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generarCarnetsMasivos($carnets)
    {
        $carnetesData = [];

        foreach ($carnets as $carnet) {
            $carnetesData[] = [
                'carnet' => $carnet,
                'usuario' => $carnet->usuario,
                'fechaEmision' => Carbon::parse($carnet->fecha_emision)->format('d/m/Y'),
                'fechaVencimiento' => Carbon::parse($carnet->fecha_vencimiento)->format('d/m/Y'),
            ];
        }

        $data = [
            'carnetes' => $carnetesData,
            'generadoEn' => Carbon::now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('carnets.pdf-masivo', $data);
        $pdf->setPaper('letter', 'portrait');

        return $pdf;
    }
}
