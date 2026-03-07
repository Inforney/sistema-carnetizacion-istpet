<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acceso;
use App\Models\Laboratorio;
use App\Models\Profesor;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ReporteController extends Controller
{
    /**
     * Descargar reporte Excel de accesos
     * Parámetros: tipo (diario|semanal|mensual|personalizado), fecha, fecha_desde, fecha_hasta,
     *             laboratorio_id, profesor_id, solo_problemas
     */
    public function descargar(Request $request)
    {
        $request->validate([
            'tipo'           => 'required|in:diario,semanal,mensual,personalizado',
            'fecha'          => 'nullable|date',
            'fecha_desde'    => 'nullable|date',
            'fecha_hasta'    => 'nullable|date|after_or_equal:fecha_desde',
            'laboratorio_id' => 'nullable|exists:laboratorios,id',
            'profesor_id'    => 'nullable|exists:profesores,id',
            'solo_problemas' => 'nullable|boolean',
        ]);

        // Calcular rango de fechas según tipo
        [$fechaDesde, $fechaHasta, $tituloRango] = $this->calcularRango($request);

        // Construir query
        $query = Acceso::with(['usuario', 'laboratorio', 'profesor'])
            ->whereBetween('fecha_entrada', [$fechaDesde, $fechaHasta])
            ->orderBy('fecha_entrada', 'asc')
            ->orderBy('hora_entrada', 'asc');

        if ($request->filled('laboratorio_id')) {
            $query->where('laboratorio_id', $request->laboratorio_id);
        }

        if ($request->filled('profesor_id')) {
            $query->where('profesor_id', $request->profesor_id);
        }

        if ($request->boolean('solo_problemas')) {
            $query->where(function ($q) {
                // Salida temprana: menos de 30 minutos, o marcado ausente
                $q->where('marcado_ausente', true)
                    ->orWhere(function ($q2) {
                        $q2->whereNotNull('hora_salida')
                            ->whereRaw('TIMESTAMPDIFF(MINUTE, hora_entrada, hora_salida) < 30');
                    });
            });
        }

        $accesos = $query->get();

        // Generar Excel
        $spreadsheet = $this->generarSpreadsheet($accesos, $tituloRango, $request);
        $writer = new Xlsx($spreadsheet);

        $nombreArchivo = 'reporte_accesos_' . $request->tipo . '_' . now()->format('Ymd_His') . '.xlsx';

        // Stream directo al navegador
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $nombreArchivo, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Calcular rango de fechas según tipo de reporte
     */
    private function calcularRango(Request $request): array
    {
        $tipo = $request->tipo;

        switch ($tipo) {
            case 'diario':
                $fecha = $request->filled('fecha') ? Carbon::parse($request->fecha) : Carbon::today();
                return [
                    $fecha->toDateString(),
                    $fecha->toDateString(),
                    'Diario: ' . $fecha->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY'),
                ];

            case 'semanal':
                $fecha = $request->filled('fecha') ? Carbon::parse($request->fecha) : Carbon::today();
                $inicio = $fecha->copy()->startOfWeek(Carbon::MONDAY);
                $fin    = $fecha->copy()->endOfWeek(Carbon::SUNDAY);
                return [
                    $inicio->toDateString(),
                    $fin->toDateString(),
                    'Semanal: ' . $inicio->format('d/m/Y') . ' al ' . $fin->format('d/m/Y'),
                ];

            case 'mensual':
                $fecha = $request->filled('fecha') ? Carbon::parse($request->fecha) : Carbon::today();
                $inicio = $fecha->copy()->startOfMonth();
                $fin    = $fecha->copy()->endOfMonth();
                return [
                    $inicio->toDateString(),
                    $fin->toDateString(),
                    'Mensual: ' . $fecha->locale('es')->isoFormat('MMMM [de] YYYY'),
                ];

            case 'personalizado':
                $desde = $request->filled('fecha_desde') ? $request->fecha_desde : Carbon::today()->toDateString();
                $hasta = $request->filled('fecha_hasta') ? $request->fecha_hasta : Carbon::today()->toDateString();
                return [
                    $desde,
                    $hasta,
                    'Personalizado: ' . Carbon::parse($desde)->format('d/m/Y') . ' al ' . Carbon::parse($hasta)->format('d/m/Y'),
                ];
        }
    }

    /**
     * Generar el spreadsheet con los datos
     */
    private function generarSpreadsheet($accesos, $tituloRango, Request $request): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Accesos');

        // ── Estilos base ──────────────────────────────────────────────
        $azul   = '222C57'; // ISTPET azul
        $dorado = 'C4A857'; // ISTPET dorado
        $grisClaro = 'F5F5F5';

        // ── Encabezado del instituto ──────────────────────────────────
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'INSTITUTO SUPERIOR TECNOLÓGICO MAYOR PEDRO TRAVERSARI');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $azul]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);

        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'REPORTE DE ACCESOS A LABORATORIOS');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $azul]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', $tituloRango);
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $azul]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF9E6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Info de filtros aplicados
        $filtros = [];
        if ($request->filled('laboratorio_id')) {
            $lab = Laboratorio::find($request->laboratorio_id);
            if ($lab) $filtros[] = 'Laboratorio: ' . $lab->nombre;
        }
        if ($request->filled('profesor_id')) {
            $prof = Profesor::find($request->profesor_id);
            if ($prof) $filtros[] = 'Profesor: ' . $prof->nombreCompleto;
        }
        if ($request->boolean('solo_problemas')) {
            $filtros[] = 'Solo problemas (ausencias y salidas < 30 min)';
        }

        $sheet->mergeCells('A4:J4');
        $sheet->setCellValue('A4', empty($filtros) ? 'Todos los registros' : implode(' | ', $filtros));
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A5:J5');
        $sheet->setCellValue('A5', 'Generado: ' . now()->format('d/m/Y H:i:s') . ' | Total registros: ' . $accesos->count());
        $sheet->getStyle('A5')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '888888']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Cabecera de tabla ─────────────────────────────────────────
        $row = 7;
        $headers = [
            'A' => '#',
            'B' => 'Fecha',
            'C' => 'Estudiante',
            'D' => 'Cédula',
            'E' => 'Carrera / Nivel',
            'F' => 'Laboratorio',
            'G' => 'Entrada',
            'H' => 'Salida',
            'I' => 'Duración',
            'J' => 'Estado',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col . $row, $label);
        }

        $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $dorado]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(18);

        // ── Datos ─────────────────────────────────────────────────────
        $row++;
        $num = 1;
        foreach ($accesos as $acceso) {
            $esProblema = $acceso->marcado_ausente ||
                ($acceso->hora_salida && Carbon::parse($acceso->hora_entrada)->diffInMinutes(Carbon::parse($acceso->hora_salida)) < 30);

            $duracion = $acceso->hora_salida
                ? $this->formatearDuracion(Carbon::parse($acceso->hora_entrada)->diffInMinutes(Carbon::parse($acceso->hora_salida)))
                : 'En curso';

            if ($acceso->marcado_ausente) {
                $estado = 'AUSENTE';
            } elseif ($acceso->hora_salida) {
                $dur = Carbon::parse($acceso->hora_entrada)->diffInMinutes(Carbon::parse($acceso->hora_salida));
                $estado = $dur < 30 ? 'SALIDA TEMPRANA' : 'Completado';
            } else {
                $estado = 'En curso';
            }

            $sheet->setCellValue('A' . $row, $num++);
            $sheet->setCellValue('B' . $row, $acceso->fecha_entrada ? Carbon::parse($acceso->fecha_entrada)->format('d/m/Y') : '');
            $sheet->setCellValue('C' . $row, $acceso->usuario?->nombreCompleto ?? '-');
            $sheet->setCellValue('D' . $row, $acceso->usuario?->cedula ?? '-');
            $sheet->setCellValue('E' . $row, ($acceso->usuario?->carrera ?? '-') . ' / ' . ($acceso->usuario?->ciclo_nivel ?? '-'));
            $sheet->setCellValue('F' . $row, $acceso->laboratorio?->nombre ?? '-');
            $sheet->setCellValue('G' . $row, $acceso->hora_entrada ? Carbon::parse($acceso->hora_entrada)->format('H:i') : '-');
            $sheet->setCellValue('H' . $row, $acceso->hora_salida ? Carbon::parse($acceso->hora_salida)->format('H:i') : '-');
            $sheet->setCellValue('I' . $row, $duracion);
            $sheet->setCellValue('J' . $row, $estado);

            // Color de fila según estado
            if ($acceso->marcado_ausente) {
                $colorFila = 'FFEBEE'; // rojo claro
            } elseif ($acceso->hora_salida && Carbon::parse($acceso->hora_entrada)->diffInMinutes(Carbon::parse($acceso->hora_salida)) < 30) {
                $colorFila = 'FFF8E1'; // amarillo claro
            } elseif ($num % 2 === 0) {
                $colorFila = $grisClaro;
            } else {
                $colorFila = 'FFFFFF';
            }

            $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => ltrim($colorFila, '#')]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);

            $row++;
        }

        // ── Fila de totales ───────────────────────────────────────────
        $ausentes    = $accesos->where('marcado_ausente', true)->count();
        $completados = $accesos->filter(fn($a) => $a->hora_salida && !$a->marcado_ausente)->count();
        $enCurso     = $accesos->filter(fn($a) => !$a->hora_salida && !$a->marcado_ausente)->count();

        $sheet->mergeCells('A' . $row . ':I' . $row);
        $sheet->setCellValue('A' . $row, "TOTALES — Completados: {$completados} | En curso: {$enCurso} | Ausentes: {$ausentes}");
        $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3E8F0']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Ancho de columnas ─────────────────────────────────────────
        $anchos = ['A' => 5, 'B' => 12, 'C' => 28, 'D' => 14, 'E' => 30, 'F' => 22, 'G' => 10, 'H' => 10, 'I' => 10, 'J' => 16];
        foreach ($anchos as $col => $ancho) {
            $sheet->getColumnDimension($col)->setWidth($ancho);
        }

        return $spreadsheet;
    }

    private function formatearDuracion(int $minutos): string
    {
        if ($minutos < 60) {
            return $minutos . ' min';
        }
        $h = intdiv($minutos, 60);
        $m = $minutos % 60;
        return $m > 0 ? "{$h}h {$m}m" : "{$h}h";
    }
}
