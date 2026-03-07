@extends('layouts.app')

@section('title', 'Historial de Accesos')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-0 fw-bold" style="color:var(--istpet-azul);">
                <i class="bi bi-clock-history me-2" style="color:var(--istpet-dorado);"></i>Historial de Accesos
            </h4>
            <small class="text-muted">Todos los registros de entrada/salida a laboratorios</small>
        </div>
        {{-- Botón para descargar reporte --}}
        <button class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#reporteModal">
            <i class="bi bi-file-earmark-excel me-2"></i>Descargar Reporte Excel
        </button>
    </div>

    {{-- Tarjetas de resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold" style="color:var(--istpet-azul);">{{ $stats['total'] }}</div>
                <div class="text-muted small">Total registros (filtro actual)</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3 {{ $stats['ausentes'] > 0 ? 'border-danger' : '' }}">
                <div class="fs-2 fw-bold text-danger">{{ $stats['ausentes'] }}</div>
                <div class="text-muted small">Marcados ausentes</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3 {{ $stats['sin_salida'] > 0 ? 'border-warning' : '' }}">
                <div class="fs-2 fw-bold text-warning">{{ $stats['sin_salida'] }}</div>
                <div class="text-muted small">Sin salida registrada (días anteriores)</div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-header fw-bold py-2">
            <i class="bi bi-funnel me-1"></i>Filtros de búsqueda
        </div>
        <div class="card-body py-3">
            <form method="GET" action="{{ route('profesor.accesos.historial') }}" class="row g-2 align-items-end">

                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">Laboratorio</label>
                    <select name="laboratorio_id" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($laboratorios as $lab)
                        <option value="{{ $lab->id }}" {{ request('laboratorio_id') == $lab->id ? 'selected' : '' }}>
                            {{ $lab->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">Profesor validador</label>
                    <select name="profesor_id" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($profesores as $prof)
                        <option value="{{ $prof->id }}" {{ request('profesor_id') == $prof->id ? 'selected' : '' }}>
                            {{ $prof->nombres }} {{ $prof->apellidos }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">Problemas</label>
                    <select name="tipo_problema" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="ausente"        {{ request('tipo_problema') === 'ausente'        ? 'selected' : '' }}>Solo ausentes</option>
                        <option value="salida_temprana"{{ request('tipo_problema') === 'salida_temprana'? 'selected' : '' }}>Salida &lt; 30 min</option>
                        <option value="sin_salida"     {{ request('tipo_problema') === 'sin_salida'     ? 'selected' : '' }}>Sin salida (días ant.)</option>
                        <option value="todos_problemas"{{ request('tipo_problema') === 'todos_problemas'? 'selected' : '' }}>Todos los problemas</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('profesor.accesos.historial') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>

                {{-- Atajos rápidos --}}
                <div class="col-12 d-flex gap-2 flex-wrap mt-1">
                    <span class="text-muted small me-1">Accesos rápidos:</span>
                    <a href="{{ route('profesor.accesos.historial', ['fecha_desde' => today()->toDateString(), 'fecha_hasta' => today()->toDateString()]) }}"
                       class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size:0.78rem;">Hoy</a>
                    <a href="{{ route('profesor.accesos.historial', ['fecha_desde' => now()->startOfWeek()->toDateString(), 'fecha_hasta' => now()->endOfWeek()->toDateString()]) }}"
                       class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size:0.78rem;">Esta semana</a>
                    <a href="{{ route('profesor.accesos.historial', ['fecha_desde' => now()->startOfMonth()->toDateString(), 'fecha_hasta' => now()->endOfMonth()->toDateString()]) }}"
                       class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size:0.78rem;">Este mes</a>
                    <a href="{{ route('profesor.accesos.historial', ['tipo_problema' => 'todos_problemas']) }}"
                       class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size:0.78rem;">⚠ Solo problemas</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de historial --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-table me-1"></i>Registros de acceso</span>
            <span class="badge bg-secondary">{{ $accesos->total() }} total</span>
        </div>
        <div class="card-body p-0">
            @if($accesos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:0.9rem;">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Estudiante</th>
                            <th>Laboratorio</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Duración</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th class="text-center">Ver</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accesos as $acceso)
                        @php
                            $dur = $acceso->hora_salida
                                ? \Carbon\Carbon::parse($acceso->hora_entrada)->diffInMinutes(\Carbon\Carbon::parse($acceso->hora_salida))
                                : null;
                            $esSalidaTemprana = $dur !== null && $dur < 30 && !$acceso->marcado_ausente;
                            $esSinSalida = !$acceso->hora_salida && !$acceso->marcado_ausente && ($acceso->fecha_entrada < today()->toDateString());
                        @endphp
                        <tr class="
                            {{ $acceso->marcado_ausente ? 'table-danger' : '' }}
                            {{ $esSalidaTemprana ? 'table-warning' : '' }}
                            {{ $esSinSalida ? 'table-warning' : '' }}
                        ">
                            <td class="text-nowrap">
                                {{ \Carbon\Carbon::parse($acceso->fecha_entrada)->format('d/m/Y') }}
                            </td>
                            <td>
                                <strong>{{ $acceso->usuario?->nombreCompleto ?? '-' }}</strong>
                                <br><small class="text-muted">{{ $acceso->usuario?->cedula }}</small>
                            </td>
                            <td>
                                <i class="bi bi-display me-1 text-primary"></i>
                                {{ $acceso->laboratorio?->nombre ?? '-' }}
                            </td>
                            <td class="text-nowrap">{{ \Carbon\Carbon::parse($acceso->hora_entrada)->format('H:i') }}</td>
                            <td class="text-nowrap">
                                @if($acceso->hora_salida)
                                    {{ \Carbon\Carbon::parse($acceso->hora_salida)->format('H:i') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                @if($acceso->marcado_ausente)
                                    <span class="badge bg-danger">Ausente</span>
                                @elseif($dur !== null)
                                    @if($dur < 30)
                                        <span class="text-warning fw-bold">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            {{ $dur }} min
                                        </span>
                                    @else
                                        {{ $acceso->duracion_formateada }}
                                    @endif
                                @elseif($esSinSalida)
                                    <span class="badge bg-warning text-dark">Sin salida</span>
                                @else
                                    <span class="text-muted">En curso</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $acceso->metodo_registro === 'qr_estudiante' ? 'bg-info text-dark' : 'bg-secondary' }}" style="font-size:0.72rem;">
                                    {{ $acceso->metodo_registro === 'qr_estudiante' ? 'QR' : 'Manual' }}
                                </span>
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ausente</span>
                                @elseif($esSalidaTemprana)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i>Salida temprana</span>
                                @elseif($esSinSalida)
                                    <span class="badge bg-warning text-dark">Sin salida</span>
                                @elseif($acceso->hora_salida)
                                    <span class="badge bg-success">Completado</span>
                                @else
                                    <span class="badge bg-primary"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>En curso</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('profesor.accesos.detalle', $acceso->id) }}"
                                   class="btn btn-sm btn-outline-info" title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2">
                {{ $accesos->links() }}
            </div>
            @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:3rem;"></i>
                <p class="mt-2">No hay registros con los filtros seleccionados.</p>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- Modal de Reporte --}}
<div class="modal fade" id="reporteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--istpet-azul);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-file-earmark-excel me-2"></i>Descargar Reporte Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profesor.reportes.descargar') }}" method="GET" target="_blank">
                <div class="modal-body">

                    {{-- Tipo de reporte --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tipo de reporte</label>
                        <div class="row g-2" id="tipo_reporte_btns">
                            @foreach(['diario' => ['Diario', 'calendar-day'], 'semanal' => ['Semanal', 'calendar-week'], 'mensual' => ['Mensual', 'calendar-month'], 'personalizado' => ['Personalizado', 'calendar-range']] as $val => [$label, $icon])
                            <div class="col-3">
                                <input type="radio" class="btn-check" name="tipo" id="tipo_{{ $val }}" value="{{ $val }}" {{ $val === 'mensual' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary w-100" for="tipo_{{ $val }}">
                                    <i class="bi bi-{{ $icon }} d-block mb-1" style="font-size:1.3rem;"></i>
                                    {{ $label }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Fecha para diario/semanal/mensual --}}
                    <div id="campo_fecha" class="mb-3">
                        <label class="form-label fw-bold">Fecha de referencia</label>
                        <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}">
                        <small class="text-muted">Para <strong>diario</strong>: ese día. Para <strong>semanal</strong>: la semana que contiene esa fecha. Para <strong>mensual</strong>: ese mes.</small>
                    </div>

                    {{-- Rango personalizado --}}
                    <div id="campo_rango" class="mb-3 d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Desde</label>
                                <input type="date" name="fecha_desde" class="form-control" value="{{ now()->startOfMonth()->toDateString() }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hasta</label>
                                <input type="date" name="fecha_hasta" class="form-control" value="{{ now()->toDateString() }}">
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Filtros adicionales --}}
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Laboratorio</label>
                            <select name="laboratorio_id" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                @foreach($laboratorios as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Profesor</label>
                            <select name="profesor_id" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                @foreach($profesores as $prof)
                                <option value="{{ $prof->id }}">{{ $prof->nombres }} {{ $prof->apellidos }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="solo_problemas" value="1" id="chkProblemas">
                                <label class="form-check-label fw-bold" for="chkProblemas">
                                    <i class="bi bi-exclamation-triangle text-warning me-1"></i>
                                    Solo problemas
                                </label>
                                <div class="text-muted" style="font-size:0.78rem;">Ausencias y salidas &lt; 30 min</div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="bi bi-download me-2"></i>Descargar Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Mostrar/ocultar campos de fecha según tipo de reporte
    document.querySelectorAll('input[name="tipo"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const esPersonalizado = this.value === 'personalizado';
            document.getElementById('campo_fecha').classList.toggle('d-none', esPersonalizado);
            document.getElementById('campo_rango').classList.toggle('d-none', !esPersonalizado);
        });
    });
</script>
@endsection
