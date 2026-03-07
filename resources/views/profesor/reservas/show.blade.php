@extends('layouts.app')

@section('title', 'Detalle de Clase — ' . $reserva->laboratorio->nombre)

@section('content')
<div class="container py-4" id="contenido-reporte">

    {{-- Header pantalla --}}
    <div class="d-flex align-items-center justify-content-between mb-4 no-print">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('profesor.reservas.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 fw-bold" style="color:var(--istpet-azul);">
                    <i class="bi bi-people me-2" style="color:var(--istpet-dorado);"></i>Estudiantes en Clase
                </h4>
                <small class="text-muted">
                    {{ $reserva->laboratorio->nombre }} &mdash;
                    {{ $reserva->fecha->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}
                </small>
            </div>
        </div>
        <button onclick="window.print()" class="btn btn-primary fw-bold">
            <i class="bi bi-printer me-2"></i>Imprimir / Guardar PDF
        </button>
    </div>

    {{-- Encabezado SOLO para impresión --}}
    <div class="print-header d-none">
        <div class="print-logo-row">
            <div>
                <div class="print-instituto">INSTITUTO SUPERIOR TECNOLÓGICO MAYOR PEDRO TRAVERSARI</div>
                <div class="print-subtitulo">REPORTE DE ASISTENCIA A LABORATORIO</div>
            </div>
        </div>
        <hr style="border-top:3px solid #C4A857; margin:6px 0 10px;">
    </div>

    {{-- Tarjeta info de la reserva --}}
    <div class="card mb-4 print-info-card" style="border-left:5px solid var(--istpet-dorado);">
        <div class="card-body py-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-3 col-6">
                    <div class="text-muted print-label">LABORATORIO</div>
                    <strong>{{ $reserva->laboratorio->nombre }}</strong>
                    @if($reserva->laboratorio->ubicacion)
                    <br><small class="text-muted">{{ $reserva->laboratorio->ubicacion }}</small>
                    @endif
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-muted print-label">FECHA</div>
                    <strong>{{ $reserva->fecha->format('d/m/Y') }}</strong>
                    <br><small class="text-muted">{{ $reserva->fecha->locale('es')->isoFormat('dddd') }}</small>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-muted print-label">HORARIO</div>
                    <strong>{{ $reserva->hora_inicio_formateada }} – {{ $reserva->hora_fin_formateada }}</strong>
                    <span class="text-muted ms-1" style="font-size:0.82rem;">({{ $reserva->duracion_formateada }})</span>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-muted print-label">MATERIA / ESTADO</div>
                    <strong>{{ $reserva->materia ?? 'Sin especificar' }}</strong>
                    @if($reserva->descripcion)
                    <br><small class="text-muted">{{ $reserva->descripcion }}</small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3 print-stat">
                <div class="fs-2 fw-bold" style="color:var(--istpet-azul);">{{ $stats['total'] }}</div>
                <div class="text-muted small">Total registros</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3 print-stat">
                <div class="fs-2 fw-bold text-success">{{ $stats['presentes'] }}</div>
                <div class="text-muted small">Completaron clase</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3 print-stat {{ $stats['ausentes'] > 0 ? 'border-danger border' : '' }}">
                <div class="fs-2 fw-bold text-danger">{{ $stats['ausentes'] }}</div>
                <div class="text-muted small">Marcados ausentes</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3 print-stat {{ $stats['salida_temprana'] > 0 ? 'border-warning border' : '' }}">
                <div class="fs-2 fw-bold text-warning">{{ $stats['salida_temprana'] }}</div>
                <div class="text-muted small">Salida &lt; 30 min</div>
            </div>
        </div>
    </div>

    {{-- Tabla de estudiantes --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">
                <i class="bi bi-person-lines-fill me-1 no-print"></i>
                Registro de estudiantes durante la clase
            </span>
            <span class="badge bg-secondary no-print">{{ $stats['total'] }} registros</span>
        </div>
        <div class="card-body p-0">
            @if($accesos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 print-table" style="font-size:0.9rem;">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Apellidos y Nombres</th>
                            <th>Cédula</th>
                            <th>Carrera</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Duración</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accesos as $i => $acceso)
                        @php
                            $dur = $acceso->hora_salida
                                ? \Carbon\Carbon::parse($acceso->hora_entrada)->diffInMinutes(\Carbon\Carbon::parse($acceso->hora_salida))
                                : null;
                            $esTemprana = $dur !== null && $dur < 30 && !$acceso->marcado_ausente;
                        @endphp
                        <tr class="{{ $acceso->marcado_ausente ? 'table-danger' : ($esTemprana ? 'table-warning' : '') }}">
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td><strong>{{ $acceso->usuario?->nombreCompleto ?? '—' }}</strong></td>
                            <td class="text-muted">{{ $acceso->usuario?->cedula ?? '—' }}</td>
                            <td class="text-muted small">{{ $acceso->usuario?->carrera ?? '—' }}</td>
                            <td class="text-nowrap fw-bold text-success">
                                {{ \Carbon\Carbon::parse($acceso->hora_entrada)->format('H:i') }}
                            </td>
                            <td class="text-nowrap">
                                @if($acceso->hora_salida)
                                    <span class="{{ $esTemprana ? 'fw-bold text-warning' : 'text-danger' }}">
                                        {{ \Carbon\Carbon::parse($acceso->hora_salida)->format('H:i') }}
                                    </span>
                                @elseif($acceso->marcado_ausente)
                                    <span class="text-muted">—</span>
                                @else
                                    <span class="text-primary">En curso</span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                    <span class="text-muted">—</span>
                                @elseif($dur !== null)
                                    @if($esTemprana)
                                        <span class="fw-bold text-warning">{{ $dur }} min ⚠</span>
                                    @else
                                        {{ $acceso->duracion_formateada }}
                                    @endif
                                @else
                                    <span class="text-muted small">En curso</span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                    <span class="print-badge print-badge-danger">AUSENTE</span>
                                    @if($acceso->nota_ausencia)
                                    <br><small class="text-muted" style="font-size:0.75rem;">{{ \Str::limit($acceso->nota_ausencia, 40) }}</small>
                                    @endif
                                @elseif($esTemprana)
                                    <span class="print-badge print-badge-warning">SALIDA TEMPRANA</span>
                                @elseif(!$acceso->hora_salida)
                                    <span class="print-badge print-badge-info">EN CURSO</span>
                                @else
                                    <span class="print-badge print-badge-success">COMPLETO</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5 text-muted no-print">
                <i class="bi bi-people" style="font-size:3rem; opacity:0.3;"></i>
                <p class="mt-3 mb-1 fw-semibold">No hay registros de estudiantes en este horario</p>
                <small>Nadie accedió al {{ $reserva->laboratorio->nombre }} durante
                    {{ $reserva->hora_inicio_formateada }} – {{ $reserva->hora_fin_formateada }}</small>
            </div>
            <div class="print-only py-3 text-center text-muted">
                <em>No se registraron accesos durante esta clase.</em>
            </div>
            @endif
        </div>
    </div>

    {{-- Pie de reporte solo en impresión --}}
    <div class="print-footer d-none mt-4">
        <hr style="border-top:2px solid #222C57; margin-bottom:6px;">
        <div style="display:flex; justify-content:space-between; font-size:10px; color:#555;">
            <span>Generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }}</span>
            <span>ISTPET — Sistema de Carnetización y Control de Accesos</span>
            <span>Reporte de Asistencia</span>
        </div>
        <div class="mt-4" style="display:flex; justify-content:flex-end; gap:60px;">
            <div class="text-center" style="font-size:10px;">
                <div style="border-top:1px solid #333; width:200px; padding-top:4px;">Firma del Profesor</div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<style>
/* ── Estilos solo para impresión ────────────────────────────────────────── */
@media print {
    /* Ocultar navegación y elementos de pantalla */
    nav, .navbar, header, footer, .sidebar,
    .no-print, .btn, [data-bs-toggle] {
        display: none !important;
    }

    /* Mostrar elementos solo para impresión */
    .print-header,
    .print-footer,
    .print-only {
        display: block !important;
    }

    body {
        font-size: 11px;
        color: #000;
        background: #fff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .container {
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .card {
        border: 1px solid #ccc !important;
        box-shadow: none !important;
        margin-bottom: 10px !important;
        break-inside: avoid;
    }

    .card-header {
        background: #222C57 !important;
        color: #fff !important;
        padding: 6px 10px !important;
        font-size: 11px !important;
    }

    .print-info-card {
        border-left: 4px solid #C4A857 !important;
    }

    /* Stats compactos */
    .print-stat {
        padding: 8px !important;
        border: 1px solid #ddd !important;
    }

    .print-stat .fs-2 {
        font-size: 1.4rem !important;
    }

    /* Tabla */
    .print-table {
        width: 100% !important;
        font-size: 9.5px !important;
        border-collapse: collapse !important;
    }

    .print-table thead tr {
        background: #222C57 !important;
        color: #fff !important;
    }

    .print-table th,
    .print-table td {
        border: 1px solid #ccc !important;
        padding: 4px 6px !important;
    }

    .print-table .table-danger td { background: #ffe6e6 !important; }
    .print-table .table-warning td { background: #fff8e0 !important; }

    /* Badges de estado para impresión */
    .print-badge {
        font-size: 9px;
        font-weight: bold;
        padding: 2px 5px;
        border-radius: 3px;
        border: 1px solid;
    }
    .print-badge-success { color: #155724; border-color: #c3e6cb; background: #d4edda; }
    .print-badge-danger  { color: #721c24; border-color: #f5c6cb; background: #f8d7da; }
    .print-badge-warning { color: #856404; border-color: #ffc107; background: #fff3cd; }
    .print-badge-info    { color: #0c5460; border-color: #bee5eb; background: #d1ecf1; }

    /* Encabezado de impresión */
    .print-instituto {
        font-size: 13px;
        font-weight: bold;
        color: #222C57;
        text-transform: uppercase;
    }
    .print-subtitulo {
        font-size: 11px;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .print-label {
        font-size: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #777 !important;
        margin-bottom: 2px;
    }

    /* Quitar shadows y bordes de pantalla */
    .shadow-sm { box-shadow: none !important; }
    .table-hover tbody tr:hover { background: inherit !important; }

    /* Evitar cortes de página en filas */
    .print-table tbody tr { break-inside: avoid; }
}
</style>
@endsection
