<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Carnet;
use App\Models\Profesor;
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
     * Extraer fotos del ZIP e indexarlas por múltiples claves
     */
    private function extraerFotosZip($zipFile)
    {
        $zip = new \ZipArchive();
        $fotosExtraidas = [];

        if ($zip->open($zipFile->getPathname()) === TRUE) {
            $tempDir = storage_path('app/temp_fotos_' . time());
            $zip->extractTo($tempDir);
            $zip->close();

            $archivos = \File::allFiles($tempDir);
            foreach ($archivos as $archivo) {
                if (in_array(strtolower($archivo->getExtension()), ['jpg', 'jpeg', 'png'])) {
                    $ruta = $archivo->getPathname();
                    $nombre = $archivo->getFilename();           // "1714369590.jpg"
                    $sinExt = $archivo->getFilenameWithoutExtension(); // "1714369590"

                    // Indexar por nombre exacto, nombre en minúsculas, y cédula sin extensión
                    $fotosExtraidas[$nombre]               = $ruta;
                    $fotosExtraidas[strtolower($nombre)]   = $ruta;
                    $fotosExtraidas[$sinExt]               = $ruta;
                    $fotosExtraidas[strtolower($sinExt)]   = $ruta;
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
                $tipoDoc   = strtolower(trim($fila[3] ?? ''));
                $documento = trim((string) $fila[2]);
                $celular   = trim((string) $fila[5]);

                // Recuperar cero inicial si Excel lo eliminó (cédula de 9 dígitos numéricos → 10)
                if ($tipoDoc === 'cedula' && strlen($documento) === 9 && ctype_digit($documento)) {
                    $documento = '0' . $documento;
                }
                // Celular ecuatoriano sin el 0 inicial (9 dígitos empieza en 9) → agregar 0
                if (strlen($celular) === 9 && ctype_digit($celular) && $celular[0] === '9') {
                    $celular = '0' . $celular;
                }

                $datos = [
                    'nombres'              => trim($fila[0]),
                    'apellidos'            => trim($fila[1]),
                    'cedula'               => $documento,
                    'tipo_documento'       => $tipoDoc,
                    'correo_institucional' => trim($fila[4]),
                    'celular'              => $celular,
                    'carrera'              => trim($fila[6]),
                    'ciclo_nivel'          => trim($fila[7] ?? 'PRIMER NIVEL'),
                    'nacionalidad'         => trim($fila[8] ?? 'Ecuatoriana'),
                    'foto_filename'        => trim($fila[9] ?? ''),
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

                // VERIFICAR DUPLICADOS (en usuarios Y en profesores)
                $existente = Usuario::where('cedula', $datos['cedula'])->first();
                if ($existente) {
                    $resultados['duplicados'][] = [
                        'fila'   => $numeroFila,
                        'cedula' => $datos['cedula'],
                        'nombre' => $datos['nombres'] . ' ' . $datos['apellidos'],
                        'motivo' => 'Ya existe como estudiante',
                    ];
                    continue;
                }

                $esProfesor = Profesor::where('cedula', $datos['cedula'])->exists();
                if ($esProfesor) {
                    $resultados['duplicados'][] = [
                        'fila'   => $numeroFila,
                        'cedula' => $datos['cedula'],
                        'nombre' => $datos['nombres'] . ' ' . $datos['apellidos'],
                        'motivo' => 'Ya existe como profesor',
                    ];
                    continue;
                }

                // PROCESAR FOTO
                // Prioridad: 1) foto_filename del Excel, 2) auto-match por cédula
                $fotoPath = null;
                $fotoFilename = $datos['foto_filename'];
                $cedula = $datos['cedula'];

                if (!empty($fotoFilename) && isset($fotosExtraidas[$fotoFilename])) {
                    // Coincidencia exacta con lo indicado en columna J
                    $fotoPath = $this->guardarFoto($fotosExtraidas[$fotoFilename], $cedula);
                } elseif (!empty($fotoFilename) && isset($fotosExtraidas[strtolower($fotoFilename)])) {
                    // Coincidencia case-insensitive
                    $fotoPath = $this->guardarFoto($fotosExtraidas[strtolower($fotoFilename)], $cedula);
                } elseif (isset($fotosExtraidas[$cedula])) {
                    // Auto-match: el ZIP tiene un archivo llamado exactamente con la cédula
                    $fotoPath = $this->guardarFoto($fotosExtraidas[$cedula], $cedula);
                } elseif (!empty($fotosExtraidas)) {
                    // Buscar cualquier variante: cedula.jpg, cedula.jpeg, cedula.png
                    foreach (['jpg', 'jpeg', 'png'] as $ext) {
                        $key = $cedula . '.' . $ext;
                        if (isset($fotosExtraidas[$key])) {
                            $fotoPath = $this->guardarFoto($fotosExtraidas[$key], $cedula);
                            break;
                        }
                    }
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
                    'password' => Hash::make('ISTPET' . substr($datos['cedula'], -4)),
                    'password_temporal' => 1,
                ]);

                // GENERAR CARNET (si está habilitado)
                if ($generarCarnets) {
                    $codigoQr = 'ISTPET-' . date('Y') . '-' . $datos['cedula'];

                    Carnet::create([
                        'usuario_id'        => $usuario->id,
                        'codigo_qr'         => $codigoQr,
                        'fecha_emision'     => Carbon::now(),
                        'fecha_vencimiento' => CarnetController::calcularFinPeriodoAcademico(Carbon::now()),
                        'estado'            => 'activo',
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

        // Validar tipo_documento (OBLIGATORIO)
        if (empty($datos['tipo_documento'])) {
            $errores[] = 'El tipo de documento es obligatorio (cedula o pasaporte)';
        } elseif (!in_array($datos['tipo_documento'], ['cedula', 'pasaporte'])) {
            $errores[] = 'tipo_documento debe ser "cedula" o "pasaporte", se recibió: "' . $datos['tipo_documento'] . '"';
        }

        // Validar documento
        if (empty($datos['cedula'])) {
            $errores[] = 'El campo documento es obligatorio';
        } elseif ($datos['tipo_documento'] === 'cedula') {
            if (!CedulaValidator::validar($datos['cedula'])) {
                $errores[] = 'Cédula ecuatoriana no válida: ' . $datos['cedula'];
            }
        } elseif ($datos['tipo_documento'] === 'pasaporte') {
            if (!preg_match('/^[A-Z0-9]{5,20}$/i', $datos['cedula'])) {
                $errores[] = 'Formato de pasaporte no válido: ' . $datos['cedula'];
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
     * Mostrar formulario de actualización masiva de niveles
     */
    public function actualizarNiveles()
    {
        return view('admin.importacion.actualizar-niveles');
    }

    /**
     * Procesar Excel de actualización de ciclo/nivel
     */
    public function procesarActualizacionNiveles(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'archivo_excel.required' => 'Debes seleccionar un archivo Excel',
            'archivo_excel.mimes'    => 'Solo se permiten archivos .xlsx, .xls o .csv',
            'archivo_excel.max'      => 'El archivo no debe pesar más de 10MB',
        ]);

        DB::beginTransaction();

        try {
            $spreadsheet = IOFactory::load($request->file('archivo_excel')->getPathname());
            $rows        = $spreadsheet->getActiveSheet()->toArray();
            $filas       = array_slice($rows, 1); // saltar encabezados

            $actualizados    = [];
            $no_encontrados  = [];

            foreach ($filas as $index => $fila) {
                $numeroFila = $index + 2;

                $cedula      = trim((string) ($fila[0] ?? ''));
                $nuevoCiclo  = trim((string) ($fila[1] ?? ''));
                $nuevaCarrera = trim((string) ($fila[2] ?? ''));

                // Restaurar cero inicial si Excel lo eliminó
                if (strlen($cedula) === 9 && ctype_digit($cedula)) {
                    $cedula = '0' . $cedula;
                }

                if (empty($cedula) || empty($nuevoCiclo)) {
                    continue;
                }

                $usuario = Usuario::where('cedula', $cedula)->first();

                if (!$usuario) {
                    $no_encontrados[] = ['fila' => $numeroFila, 'cedula' => $cedula];
                    continue;
                }

                $cicloAnterior   = $usuario->ciclo_nivel;
                $carreraAnterior = $usuario->carrera;

                $cambios = [
                    'ciclo_nivel' => $nuevoCiclo,
                    'semestre'    => $nuevoCiclo,
                ];
                if (!empty($nuevaCarrera)) {
                    $cambios['carrera'] = $nuevaCarrera;
                }

                $usuario->update($cambios);

                $actualizados[] = [
                    'fila'            => $numeroFila,
                    'nombre'          => $usuario->nombreCompleto,
                    'cedula'          => $cedula,
                    'ciclo_anterior'  => $cicloAnterior,
                    'ciclo_nuevo'     => $nuevoCiclo,
                    'carrera_anterior'=> $carreraAnterior,
                    'carrera_nueva'   => !empty($nuevaCarrera) ? $nuevaCarrera : null,
                ];
            }

            DB::commit();

            $resultados = compact('actualizados', 'no_encontrados');
            return view('admin.importacion.resultado-actualizacion', compact('resultados'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }

    /**
     * Descargar plantilla Excel para actualización de niveles
     */
    public function descargarPlantillaActualizacion()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Actualización Niveles');

        // Encabezados
        $sheet->setCellValue('A1', 'cedula * (10 dígitos, obligatorio)');
        $sheet->setCellValue('B1', 'ciclo_nivel * (obligatorio)');
        $sheet->setCellValue('C1', 'carrera (opcional — dejar vacío para no cambiar)');

        // Columna A como texto para preservar cero inicial
        $sheet->getStyle('A2:A5000')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        // Dropdown ciclo_nivel
        $val = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
        $val->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $val->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $val->setAllowBlank(false);
        $val->setShowDropDown(true);
        $val->setFormula1('"PRIMER NIVEL,SEGUNDO NIVEL,TERCER NIVEL,CUARTO NIVEL"');
        $sheet->setDataValidation('B2:B5000', $val);

        // Filas de ejemplo
        $sheet->getCell('A2')->setValueExplicit('1750123456', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B2', 'SEGUNDO NIVEL');
        $sheet->setCellValue('C2', '');

        $sheet->getCell('A3')->setValueExplicit('0912345678', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B3', 'TERCER NIVEL');
        $sheet->setCellValue('C3', 'Redes y Telecomunicaciones');

        // Estilos cabecera
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FF1a2342']],
            'alignment' => ['wrapText' => true,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Estilos filas de ejemplo
        $sheet->getStyle('A2:C3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFDE7');
        $sheet->getStyle('A2:C3')->getFont()->setItalic(true)
            ->setColor((new \PhpOffice\PhpSpreadsheet\Style\Color())->setARGB('FF555555'));

        $sheet->getColumnDimension('A')->setWidth(24);
        $sheet->getColumnDimension('B')->setWidth(34);
        $sheet->getColumnDimension('C')->setWidth(38);
        $sheet->freezePane('A2');

        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'plantilla_actualizacion_niveles_istpet.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    /**
     * Descargar plantilla de Excel
     */
    public function descargarPlantilla()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Estudiantes');

        // ── Encabezados con indicación de obligatorio ─────────────────
        $headers = [
            'A1' => 'nombres *',
            'B1' => 'apellidos *',
            'C1' => 'documento * (cédula 10 dígitos o pasaporte)',
            'D1' => 'tipo_documento * (cedula / pasaporte)',
            'E1' => 'correo_institucional *',
            'F1' => 'celular * (09XXXXXXXX)',
            'G1' => 'carrera *',
            'H1' => 'ciclo_nivel (PRIMER/SEGUNDO/TERCER/CUARTO NIVEL)',
            'I1' => 'nacionalidad',
            'J1' => 'foto_filename (ej: 1750123456.jpg — opcional)',
        ];
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // ── CRÍTICO: formato TEXTO en columnas de números ─────────────
        // Columna C = documento (cédula/pasaporte) — preserva ceros iniciales
        $sheet->getStyle('C2:C5000')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        // Columna F = celular — preserva el 0 inicial (09XXXXXXXX)
        $sheet->getStyle('F2:F5000')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        // ── Fila de ejemplo (valores explícitos como texto) ───────────
        $sheet->setCellValue('A2', 'Juan Carlos');
        $sheet->setCellValue('B2', 'Pérez López');
        $sheet->getCell('C2')->setValueExplicit('1750123456', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('D2', 'cedula');
        $sheet->setCellValue('E2', 'juan.perez@istpet.edu.ec');
        $sheet->getCell('F2')->setValueExplicit('0987654321', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('G2', 'Desarrollo de Software');
        $sheet->setCellValue('H2', 'TERCER NIVEL');
        $sheet->setCellValue('I2', 'Ecuatoriana');
        $sheet->setCellValue('J2', '1750123456.jpg');

        // Segunda fila de ejemplo con pasaporte
        $sheet->setCellValue('A3', 'María Fernanda');
        $sheet->setCellValue('B3', 'García Ruiz');
        $sheet->getCell('C3')->setValueExplicit('AB123456', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('D3', 'pasaporte');
        $sheet->setCellValue('E3', 'maria.garcia@istpet.edu.ec');
        $sheet->getCell('F3')->setValueExplicit('0991234567', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('G3', 'Redes y Telecomunicaciones');
        $sheet->setCellValue('H3', 'PRIMER NIVEL');
        $sheet->setCellValue('I3', 'Colombiana');
        $sheet->setCellValue('J3', 'AB123456.jpg');

        // ── Dropdown obligatorio para tipo_documento ──────────────────
        $validation = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowDropDown(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Valor no permitido');
        $validation->setError('Solo se acepta "cedula" o "pasaporte"');
        $validation->setShowInputMessage(true);
        $validation->setPromptTitle('tipo_documento');
        $validation->setPrompt('Seleccione "cedula" para cédula ecuatoriana, o "pasaporte" para documento extranjero.');
        $validation->setFormula1('"cedula,pasaporte"');
        $sheet->setDataValidation('D2:D5000', $validation);

        // ── Dropdown para ciclo_nivel ─────────────────────────────────
        $valNivel = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
        $valNivel->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $valNivel->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $valNivel->setAllowBlank(true);
        $valNivel->setShowDropDown(true);
        $valNivel->setFormula1('"PRIMER NIVEL,SEGUNDO NIVEL,TERCER NIVEL,CUARTO NIVEL"');
        $sheet->setDataValidation('H2:H5000', $valNivel);

        // ── Estilos de encabezado ─────────────────────────────────────
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1a2342']],
            'alignment' => ['wrapText' => true, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Filas de ejemplo con fondo suave
        $sheet->getStyle('A2:J3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFDE7');
        $sheet->getStyle('A2:J3')->getFont()->setItalic(true)->setColor(
            (new \PhpOffice\PhpSpreadsheet\Style\Color())->setARGB('FF555555')
        );

        // Borde en cabecera
        $sheet->getStyle('A1:J1')->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            ->getColor()->setARGB('FFCCCCCC');

        // ── Anchos de columna ─────────────────────────────────────────
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(22);
        $sheet->getColumnDimension('D')->setWidth(22);
        $sheet->getColumnDimension('E')->setWidth(32);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(16);
        $sheet->getColumnDimension('J')->setWidth(24);

        // Freeze primera fila
        $sheet->freezePane('A2');

        // ── Hoja de instrucciones ─────────────────────────────────────
        $instr = $spreadsheet->createSheet();
        $instr->setTitle('Instrucciones');
        $instr->setCellValue('A1', 'INSTRUCCIONES DE LLENADO');
        $instr->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $instrucciones = [
            ['Campo', 'Obligatorio', 'Formato / Valores válidos'],
            ['nombres', 'SÍ', 'Texto libre'],
            ['apellidos', 'SÍ', 'Texto libre'],
            ['documento', 'SÍ', 'Cédula ecuatoriana (10 dígitos) o número de pasaporte. IMPORTANTE: la columna tiene formato TEXTO para conservar los ceros iniciales.'],
            ['tipo_documento', 'SÍ', 'Exactamente "cedula" o "pasaporte" (sin tildes, en minúsculas)'],
            ['correo_institucional', 'SÍ', 'Correo electrónico válido (ej: nombre@istpet.edu.ec)'],
            ['celular', 'SÍ', 'Número ecuatoriano: 09XXXXXXXX (10 dígitos, empieza con 0). La columna tiene formato TEXTO para conservar el 0 inicial.'],
            ['carrera', 'SÍ', 'Nombre de la carrera (texto libre)'],
            ['ciclo_nivel', 'No', 'PRIMER NIVEL / SEGUNDO NIVEL / TERCER NIVEL / CUARTO NIVEL'],
            ['nacionalidad', 'No', 'Texto libre (ej: Ecuatoriana, Colombiana)'],
            ['foto_filename', 'No', 'Nombre del archivo de foto dentro del ZIP (ej: 1750123456.jpg). Si el nombre del archivo es la cédula del estudiante, se detecta automáticamente.'],
        ];
        $row = 3;
        foreach ($instrucciones as $i => $cols) {
            $instr->setCellValue('A'.$row, $cols[0]);
            $instr->setCellValue('B'.$row, $cols[1]);
            $instr->setCellValue('C'.$row, $cols[2]);
            if ($i === 0) {
                $instr->getStyle("A{$row}:C{$row}")->getFont()->setBold(true);
            }
            $row++;
        }
        $instr->getColumnDimension('A')->setWidth(22);
        $instr->getColumnDimension('B')->setWidth(12);
        $instr->getColumnDimension('C')->setWidth(80);
        $instr->getStyle('C3:C20')->getAlignment()->setWrapText(true);

        $spreadsheet->setActiveSheetIndex(0);

        // ── Descargar ─────────────────────────────────────────────────
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'plantilla_estudiantes_istpet.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
