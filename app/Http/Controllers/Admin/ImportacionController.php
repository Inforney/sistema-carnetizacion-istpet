<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Carnet;
use App\Helpers\CedulaValidator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportacionController extends Controller
{
    /**
     * Mostrar formulario de importación
     */
    public function index()
    {
        return view('admin.importacion.index');
    }

    /**
     * Procesar archivo Excel
     */
    public function importar(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240', // Max 10MB
            'fotos_zip' => 'nullable|file|mimes:zip|max:102400', // Max 100MB
            'generar_carnets' => 'nullable|boolean',
        ], [
            'archivo_excel.required' => 'Debes seleccionar un archivo Excel',
            'archivo_excel.mimes' => 'Solo se permiten archivos .xlsx, .xls o .csv',
            'archivo_excel.max' => 'El archivo no debe pesar más de 10MB',
        ]);

        DB::beginTransaction();

        try {
            // 1. LEER EXCEL
            $archivoExcel = $request->file('archivo_excel');
            $spreadsheet = IOFactory::load($archivoExcel->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // 2. EXTRAER FOTOS DEL ZIP (si se subió)
            $fotosExtraidas = [];
            if ($request->hasFile('fotos_zip')) {
                $fotosExtraidas = $this->extraerFotosZip($request->file('fotos_zip'));
            }

            // 3. PROCESAR FILAS
            $resultados = $this->procesarFilas($rows, $fotosExtraidas, $request->generar_carnets);

            DB::commit();

            return view('admin.importacion.resultado', compact('resultados'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }

    /**
     * Extraer fotos del ZIP
     */
    private function extraerFotosZip($zipFile)
    {
        $zip = new \ZipArchive();
        $fotosExtraidas = [];

        if ($zip->open($zipFile->getPathname()) === TRUE) {
            $tempDir = storage_path('app/temp_fotos_' . time());
            $zip->extractTo($tempDir);
            $zip->close();

            // Buscar archivos de imagen
            $archivos = \File::allFiles($tempDir);
            foreach ($archivos as $archivo) {
                if (in_array(strtolower($archivo->getExtension()), ['jpg', 'jpeg', 'png'])) {
                    $nombreArchivo = $archivo->getFilename();
                    $fotosExtraidas[$nombreArchivo] = $archivo->getPathname();
                }
            }
        }

        return $fotosExtraidas;
    }

    /**
     * Procesar filas del Excel
     */
    private function procesarFilas($rows, $fotosExtraidas, $generarCarnets)
    {
        $resultados = [
            'exitosos' => [],
            'errores' => [],
            'duplicados' => [],
            'carnets_generados' => 0,
        ];

        // Saltar fila de encabezados
        $filas = array_slice($rows, 1);

        foreach ($filas as $index => $fila) {
            $numeroFila = $index + 2; // +2 porque empezamos en 1 y saltamos header

            try {
                // VALIDAR QUE LA FILA TENGA DATOS
                if (empty($fila[0]) || empty($fila[2])) {
                    continue; // Fila vacía
                }

                // EXTRAER DATOS
                $datos = [
                    'nombres' => trim($fila[0]),
                    'apellidos' => trim($fila[1]),
                    'cedula' => trim($fila[2]),
                    'tipo_documento' => trim($fila[3] ?? 'cedula'),
                    'correo_institucional' => trim($fila[4]),
                    'celular' => trim($fila[5]),
                    'carrera' => trim($fila[6]),
                    'ciclo_nivel' => trim($fila[7] ?? 'PRIMER NIVEL'),
                    'nacionalidad' => trim($fila[8] ?? 'Ecuatoriana'),
                    'foto_filename' => trim($fila[9] ?? ''),
                ];

                // VALIDACIONES
                $errores = $this->validarDatos($datos, $numeroFila);

                if (!empty($errores)) {
                    $resultados['errores'][] = [
                        'fila' => $numeroFila,
                        'datos' => $datos,
                        'errores' => $errores,
                    ];
                    continue;
                }

                // VERIFICAR DUPLICADOS
                $existente = Usuario::where('cedula', $datos['cedula'])->first();
                if ($existente) {
                    $resultados['duplicados'][] = [
                        'fila' => $numeroFila,
                        'cedula' => $datos['cedula'],
                        'nombre' => $datos['nombres'] . ' ' . $datos['apellidos'],
                    ];
                    continue;
                }

                // PROCESAR FOTO
                $fotoPath = null;
                if (!empty($datos['foto_filename']) && isset($fotosExtraidas[$datos['foto_filename']])) {
                    $fotoPath = $this->guardarFoto($fotosExtraidas[$datos['foto_filename']], $datos['cedula']);
                }

                // CREAR USUARIO
                $usuario = Usuario::create([
                    'nombres' => $datos['nombres'],
                    'apellidos' => $datos['apellidos'],
                    'cedula' => $datos['cedula'],
                    'tipo_documento' => $datos['tipo_documento'],
                    'correo_institucional' => $datos['correo_institucional'],
                    'celular' => $datos['celular'],
                    'carrera' => $datos['carrera'],
                    'ciclo_nivel' => $datos['ciclo_nivel'],
                    'semestre' => $datos['ciclo_nivel'], // Para compatibilidad
                    'nacionalidad' => $datos['nacionalidad'],
                    'foto_url' => $fotoPath,
                    'tipo_usuario' => 'estudiante',
                    'estado' => 'activo',
                    'password' => Hash::make('estudiante123'),
                    'password_temporal' => 1,
                ]);

                // GENERAR CARNET (si está habilitado)
                if ($generarCarnets) {
                    $codigoQr = 'ISTPET-' . date('Y') . '-' . $datos['cedula'];

                    Carnet::create([
                        'usuario_id' => $usuario->id,
                        'codigo_qr' => $codigoQr,
                        'fecha_emision' => Carbon::now(),
                        'fecha_vencimiento' => Carbon::now()->addYears(4),
                        'estado' => 'activo',
                    ]);

                    $resultados['carnets_generados']++;
                }

                $resultados['exitosos'][] = [
                    'fila' => $numeroFila,
                    'nombre' => $datos['nombres'] . ' ' . $datos['apellidos'],
                    'cedula' => $datos['cedula'],
                ];
            } catch (\Exception $e) {
                $resultados['errores'][] = [
                    'fila' => $numeroFila,
                    'datos' => $datos ?? [],
                    'errores' => ['Error inesperado: ' . $e->getMessage()],
                ];
            }
        }

        return $resultados;
    }

    /**
     * Validar datos de una fila
     */
    private function validarDatos($datos, $numeroFila)
    {
        $errores = [];

        // Validar nombres
        if (empty($datos['nombres'])) {
            $errores[] = 'El campo nombres es obligatorio';
        }

        // Validar apellidos
        if (empty($datos['apellidos'])) {
            $errores[] = 'El campo apellidos es obligatorio';
        }

        // Validar cédula
        if (empty($datos['cedula'])) {
            $errores[] = 'El campo cédula es obligatorio';
        } elseif ($datos['tipo_documento'] === 'cedula') {
            if (!CedulaValidator::validar($datos['cedula'])) {
                $errores[] = 'Cédula ecuatoriana no válida: ' . $datos['cedula'];
            }
        }

        // Validar correo
        if (empty($datos['correo_institucional'])) {
            $errores[] = 'El correo institucional es obligatorio';
        } elseif (!filter_var($datos['correo_institucional'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo no tiene formato válido';
        }

        // Validar celular
        if (empty($datos['celular'])) {
            $errores[] = 'El celular es obligatorio';
        } elseif (!preg_match('/^09[0-9]{8}$/', $datos['celular'])) {
            $errores[] = 'El celular debe tener formato ecuatoriano (09XXXXXXXX)';
        }

        // Validar carrera
        if (empty($datos['carrera'])) {
            $errores[] = 'La carrera es obligatoria';
        }

        return $errores;
    }

    /**
     * Guardar foto en storage
     */
    private function guardarFoto($rutaTemporal, $cedula)
    {
        try {
            $extension = pathinfo($rutaTemporal, PATHINFO_EXTENSION);
            $nombreArchivo = 'foto_' . $cedula . '_' . time() . '.' . $extension;
            $rutaDestino = 'fotos_perfil/' . $nombreArchivo;

            // Copiar archivo
            Storage::disk('public')->put($rutaDestino, file_get_contents($rutaTemporal));

            return 'storage/' . $rutaDestino;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Descargar plantilla de Excel
     */
    public function descargarPlantilla()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->setCellValue('A1', 'nombres');
        $sheet->setCellValue('B1', 'apellidos');
        $sheet->setCellValue('C1', 'cedula');
        $sheet->setCellValue('D1', 'tipo_documento');
        $sheet->setCellValue('E1', 'correo_institucional');
        $sheet->setCellValue('F1', 'celular');
        $sheet->setCellValue('G1', 'carrera');
        $sheet->setCellValue('H1', 'ciclo_nivel');
        $sheet->setCellValue('I1', 'nacionalidad');
        $sheet->setCellValue('J1', 'foto_filename');

        // Ejemplo
        $sheet->setCellValue('A2', 'Juan Carlos');
        $sheet->setCellValue('B2', 'Pérez López');
        $sheet->setCellValue('C2', '1750123456');
        $sheet->setCellValue('D2', 'cedula');
        $sheet->setCellValue('E2', 'juan.perez@istpet.edu.ec');
        $sheet->setCellValue('F2', '0987654321');
        $sheet->setCellValue('G2', 'Desarrollo de Software');
        $sheet->setCellValue('H2', 'TERCER NIVEL');
        $sheet->setCellValue('I2', 'Ecuatoriana');
        $sheet->setCellValue('J2', '1750123456.jpg');

        // Estilo de encabezados
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF1a2342');
        $sheet->getStyle('A1:J1')->getFont()->getColor()->setARGB('FFFFFFFF');

        // Ajustar ancho de columnas
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Descargar
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'plantilla_estudiantes_istpet.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
