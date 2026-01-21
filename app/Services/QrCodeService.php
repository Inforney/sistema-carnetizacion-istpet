<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    /**
     * Generar código QR único para un carnet
     * 
     * @param string $codigoQr
     * @return string Base64 del QR
     */
    public function generarQrCarnet($codigoQr)
    {
        // Generar QR en formato SVG
        $qr = QrCode::size(200)
            ->style('round')
            ->eye('circle')
            ->margin(1)
            ->generate($codigoQr);

        return $qr;
    }

    /**
     * Generar código QR en formato Base64
     *
     * @param string $codigoQr
     * @return string Base64 del QR
     */
    public function generarQrBase64($codigoQr)
    {
        $qr = QrCode::format('png')
            ->size(200)
            ->margin(1)
            ->generate($codigoQr);

        return 'data:image/png;base64,' . base64_encode($qr);
    }

    /**
     * Generar código QR y guardarlo como imagen
     * 
     * @param string $codigoQr
     * @param string $filename
     * @return string Path del archivo
     */
    public function generarYGuardarQr($codigoQr, $filename)
    {
        $qr = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($codigoQr);

        // Guardar en storage/app/public/qrcodes
        $path = 'qrcodes/' . $filename . '.png';
        Storage::disk('public')->put($path, $qr);

        return $path;
    }

    /**
     * Generar código único para carnet
     * Formato: ISTPET-YYYY-CEDULA
     * 
     * @param string $cedula
     * @return string
     */
    public static function generarCodigoUnico($cedula)
    {
        $year = date('Y');
        $codigo = 'ISTPET-' . $year . '-' . $cedula;

        return $codigo;
    }

    /**
     * Validar código QR
     * 
     * @param string $codigoQr
     * @return bool
     */
    public function validarCodigo($codigoQr)
    {
        // Verificar formato ISTPET-YYYY-CEDULA
        $pattern = '/^ISTPET-\d{4}-\d{10}$/';

        return preg_match($pattern, $codigoQr) === 1;
    }
}
