<?php

namespace App\Helpers;

class CedulaValidator
{
    /**
     * Valida una cédula ecuatoriana
     * 
     * @param string $cedula
     * @return bool
     */
    public static function validar($cedula)
    {
        // Verificar que sea string y tenga 10 dígitos
        if (!is_string($cedula) && !is_numeric($cedula)) {
            return false;
        }

        $cedula = (string) $cedula;

        // Debe tener exactamente 10 dígitos
        if (strlen($cedula) !== 10) {
            return false;
        }

        // Debe ser numérico
        if (!ctype_digit($cedula)) {
            return false;
        }

        // Validar código de provincia (primeros 2 dígitos)
        $provincia = (int) substr($cedula, 0, 2);
        if ($provincia < 1 || $provincia > 24) {
            return false;
        }

        // Tercer dígito debe ser menor a 6 (para personas naturales)
        $tercerDigito = (int) substr($cedula, 2, 1);
        if ($tercerDigito >= 6) {
            return false;
        }

        // Validar dígito verificador
        return self::validarDigitoVerificador($cedula);
    }

    /**
     * Valida el dígito verificador (último dígito) de la cédula
     * 
     * @param string $cedula
     * @return bool
     */
    private static function validarDigitoVerificador($cedula)
    {
        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $suma = 0;

        // Procesar los primeros 9 dígitos
        for ($i = 0; $i < 9; $i++) {
            $digito = (int) $cedula[$i];
            $resultado = $digito * $coeficientes[$i];

            // Si el resultado es mayor a 9, restar 9
            if ($resultado > 9) {
                $resultado -= 9;
            }

            $suma += $resultado;
        }

        // Obtener el dígito verificador calculado
        $residuo = $suma % 10;
        $digitoVerificadorCalculado = $residuo === 0 ? 0 : 10 - $residuo;

        // Comparar con el último dígito de la cédula
        $digitoVerificadorReal = (int) $cedula[9];

        return $digitoVerificadorCalculado === $digitoVerificadorReal;
    }

    /**
     * Genera una cédula ecuatoriana válida aleatoria
     * 
     * @param int $provincia (1-24)
     * @return string
     */
    public static function generar($provincia = null)
    {
        // Si no se especifica provincia, usar una aleatoria
        if ($provincia === null) {
            $provincia = rand(1, 24);
        }

        // Asegurar que sea de 2 dígitos
        $provincia = str_pad($provincia, 2, '0', STR_PAD_LEFT);

        // Tercer dígito (0-5 para personas naturales)
        $tercerDigito = rand(0, 5);

        // Generar 6 dígitos aleatorios
        $digitosAleatorios = '';
        for ($i = 0; $i < 6; $i++) {
            $digitosAleatorios .= rand(0, 9);
        }

        // Formar los primeros 9 dígitos
        $cedulaSinVerificador = $provincia . $tercerDigito . $digitosAleatorios;

        // Calcular dígito verificador
        $digitoVerificador = self::calcularDigitoVerificador($cedulaSinVerificador);

        return $cedulaSinVerificador . $digitoVerificador;
    }

    /**
     * Calcula el dígito verificador para una cédula
     * 
     * @param string $cedulaSinVerificador (9 dígitos)
     * @return int
     */
    private static function calcularDigitoVerificador($cedulaSinVerificador)
    {
        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $digito = (int) $cedulaSinVerificador[$i];
            $resultado = $digito * $coeficientes[$i];

            if ($resultado > 9) {
                $resultado -= 9;
            }

            $suma += $resultado;
        }

        $residuo = $suma % 10;
        return $residuo === 0 ? 0 : 10 - $residuo;
    }

    /**
     * Formatea una cédula con guiones: 1234-5678-90
     * 
     * @param string $cedula
     * @return string
     */
    public static function formatear($cedula)
    {
        if (strlen($cedula) !== 10) {
            return $cedula;
        }

        return substr($cedula, 0, 4) . '-' .
            substr($cedula, 4, 4) . '-' .
            substr($cedula, 8, 2);
    }
}
