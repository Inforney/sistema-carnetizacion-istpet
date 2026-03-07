@extends('layouts.app')

@section('title', 'Mis Reservas de Laboratorio')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold" style="color:var(--istpet-azul);">
                <i class="bi bi-calendar-check me-2" style="color:var(--istpet-dorado);"></i>Mis Reservas de Laboratorio
            </h4>
            <small class="text-muted">Agenda tus clases antes de usar el laboratorio</small>
        </div>
        <a href="{{ route('profesor.reservas.create') }}" class="btn btn-primary fw-bold">
            <i class="bi bi-plus-circle me-1"></i>Nueva Reserva
        </a>
    </div>

    {{-- Panel de ayuda colapsable --}}
    <div class="mb-4">
        <a class="text-decoration-none d-inline-flex align-items-center gap-1"
           data-bs-toggle="collapse" href="#guiaReservas" role="button"
           aria-expanded="false" aria-controls="guiaReservas"
           style="font-size:0.88rem; color:var(--istpet-azul);">
            <i class="bi bi-question-circle-fill" style="color:var(--istpet-dorado);"></i>
            <span class="fw-semibold">¿Cómo funciona el sistema de reservas?</span>
            <i class="bi bi-chevron-down ms-1" style="font-size:0.7rem;"></i>
        </a>

        <div class="collapse mt-2" id="guiaReservas">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-2" style="background:linear-gradient(90deg,var(--istpet-azul),#1a4a8a); color:#fff;">
                    <span class="fw-bold"><i class="bi bi-book me-2"></i>Guía rápida — Reservas de Laboratorio</span>
                </div>
                <div class="card-body pb-3 pt-3">

                    {{-- Flujo paso a paso --}}
                    <h6 class="fw-bold mb-3" style="color:var(--istpet-azul);">
                        <i class="bi bi-diagram-3 me-1" style="color:var(--istpet-dorado);"></i>
                        Flujo del día a día
                    </h6>
                    <div class="row g-2 mb-4">
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100 text-center" style="border-color:#dce3f0!important;">
                                <div class="mb-2">
                                    <span class="badge rounded-pill px-3 py-2 fw-bold" style="background:var(--istpet-dorado);color:#000;font-size:1rem;">1</span>
                                </div>
                                <i class="bi bi-calendar-plus fs-4 mb-2 d-block" style="color:var(--istpet-azul);"></i>
                                <strong class="d-block mb-1">Crear reserva</strong>
                                <small class="text-muted">Antes del día de clase, hacé clic en <strong>"Nueva Reserva"</strong> y elegí laboratorio, fecha y horario.</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100 text-center" style="border-color:#dce3f0!important;">
                                <div class="mb-2">
                                    <span class="badge rounded-pill px-3 py-2 fw-bold" style="background:var(--istpet-dorado);color:#000;font-size:1rem;">2</span>
                                </div>
                                <i class="bi bi-clock-history fs-4 mb-2 d-block text-warning"></i>
                                <strong class="d-block mb-1">El día de la clase</strong>
                                <small class="text-muted">La reserva aparece en la sección <strong>"Hoy"</strong> con el horario reservado. El laboratorio está bloqueado para otros.</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100 text-center" style="border-color:#dce3f0!important;">
                                <div class="mb-2">
                                    <span class="badge rounded-pill px-3 py-2 fw-bold" style="background:var(--istpet-dorado);color:#000;font-size:1rem;">3</span>
                                </div>
                                <i class="bi bi-play-circle fs-4 mb-2 d-block text-success"></i>
                                <strong class="d-block mb-1">Al iniciar la clase</strong>
                                <small class="text-muted">Cuando llegue la <strong>hora de inicio</strong>, el botón <span class="badge bg-success">Finalizar Clase</span> aparece automáticamente al recargar la página.</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 h-100 text-center" style="border-color:#dce3f0!important;">
                                <div class="mb-2">
                                    <span class="badge rounded-pill px-3 py-2 fw-bold" style="background:var(--istpet-dorado);color:#000;font-size:1rem;">4</span>
                                </div>
                                <i class="bi bi-check2-circle fs-4 mb-2 d-block text-success"></i>
                                <strong class="d-block mb-1">Al terminar la clase</strong>
                                <small class="text-muted">Hacé clic en <span class="badge bg-success">Finalizar Clase</span> para marcar el laboratorio como <strong>libre</strong>. Si olvidás, el sistema lo completa solo al día siguiente.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Significado de los botones --}}
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2" style="color:var(--istpet-azul);">
                                <i class="bi bi-cursor me-1" style="color:var(--istpet-dorado);"></i>
                                ¿Qué botón aparece en cada momento?
                            </h6>
                            <table class="table table-sm table-bordered mb-0" style="font-size:0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Situación</th>
                                        <th>Botón visible</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><i class="bi bi-calendar-event text-primary me-1"></i>Reserva para otro día</td>
                                        <td><span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Cancelar</span></td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-clock text-warning me-1"></i>Hoy, antes del inicio</td>
                                        <td><span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Cancelar</span></td>
                                    </tr>
                                    <tr class="table-success">
                                        <td><i class="bi bi-play-fill text-success me-1"></i><strong>Hoy, ya empezó</strong></td>
                                        <td><span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Finalizar Clase</span></td>
                                    </tr>
                                    <tr class="table-light">
                                        <td><i class="bi bi-check2-all text-secondary me-1"></i>Completada / Cancelada</td>
                                        <td><span class="text-muted">— sin acción</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Significado de los estados --}}
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2" style="color:var(--istpet-azul);">
                                <i class="bi bi-tag me-1" style="color:var(--istpet-dorado);"></i>
                                ¿Qué significa cada estado?
                            </h6>
                            <table class="table table-sm table-bordered mb-0" style="font-size:0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Estado</th>
                                        <th>Significado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-success">CONFIRMADA</span></td>
                                        <td>Laboratorio reservado, clase programada</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="badge bg-success d-inline-flex align-items-center gap-1">
                                                <span class="rounded-circle bg-white" style="width:7px;height:7px;display:inline-block;"></span>EN CURSO
                                            </span>
                                        </td>
                                        <td>Clase en progreso ahora mismo</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-secondary">COMPLETADA</span></td>
                                        <td>Clase finalizada, laboratorio liberado</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-danger">CANCELADA</span></td>
                                        <td>Reserva cancelada, horario disponible</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="alert alert-warning border-0 py-2 px-3 mt-2 mb-0" style="font-size:0.82rem;">
                                <i class="bi bi-lightbulb-fill me-1"></i>
                                <strong>Consejo:</strong> Si terminás antes de la hora programada, podés hacer clic en <strong>"Finalizar Clase"</strong> para liberar el laboratorio y que otro profesor pueda usarlo.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Reservas de HOY --}}
    @if($reservasHoy->count() > 0)
    <div class="card mb-4" style="border-left: 5px solid var(--istpet-dorado);">
        <div class="card-header" style="background: linear-gradient(90deg,#fff9e6,#fff);">
            <h6 class="mb-0 fw-bold" style="color:var(--istpet-azul);">
                <i class="bi bi-calendar-day me-1" style="color:var(--istpet-dorado);"></i>
                @php echo 'Hoy &mdash; ' . \Carbon\Carbon::today()->locale('es')->isoFormat('dddd D [de] MMMM'); @endphp
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Laboratorio</th>
                            <th>Horario</th>
                            <th>Materia</th>
                            <th>Estado</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservasHoy as $r)
                        <tr class="{{ $r->esta_en_curso ? 'table-success' : '' }}">
                            <td>
                                <i class="bi bi-display me-1 text-primary"></i>
                                <strong>{{ $r->laboratorio->nombre }}</strong>
                            </td>
                            <td>
                                <i class="bi bi-clock me-1"></i>
                                {{ $r->hora_inicio_formateada }} &ndash; {{ $r->hora_fin_formateada }}
                            </td>
                            <td>{{ $r->materia ?? 'Sin materia' }}</td>
                            <td>
                                @if($r->esta_en_curso)
                                    <span class="badge bg-success">
                                        <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>EN CURSO
                                    </span>
                                @else
                                    <span class="badge bg-{{ $r->estado_badge }}">{{ strtoupper($r->estado) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                @if($r->puede_finalizar)
                                <form action="{{ route('profesor.reservas.completar', $r->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Finalizar la clase y liberar el laboratorio?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm fw-bold">
                                        <i class="bi bi-check-circle me-1"></i>Finalizar
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('profesor.reservas.show', $r->id) }}"
                                   class="btn btn-outline-primary btn-sm" title="Ver estudiantes">
                                    <i class="bi bi-people me-1"></i>Ver Clase
                                </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('profesor.reservas.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
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
                    <label class="form-label small fw-bold mb-1">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="pendiente"  {{ request('estado') == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="cancelada"  {{ request('estado') == 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
                        <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm"
                           value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm"
                           value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('profesor.reservas.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de reservas --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-list-ul me-1"></i>Todas mis reservas</span>
            <span class="badge bg-secondary">{{ $reservas->total() }} registros</span>
        </div>
        <div class="card-body p-0">
            @if($reservas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Laboratorio</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Duracion</th>
                            <th>Materia</th>
                            <th>Estado</th>
                            <th class="text-center">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservas as $reserva)
                        @php
                            $esPasada     = $reserva->estado === 'completada' || $reserva->estado === 'cancelada';
                            $esHoy        = $reserva->fecha->isToday();
                            $estaEnCurso  = $reserva->esta_en_curso;
                            $esFutura     = $reserva->es_futura;
                            $puedeF       = $reserva->puede_finalizar;
                        @endphp
                        <tr class="
                            {{ $estaEnCurso ? 'table-success' : '' }}
                            {{ (!$estaEnCurso && $esHoy && $reserva->estado === 'confirmada') ? 'table-warning' : '' }}
                            {{ $esPasada ? 'text-muted' : '' }}
                        ">
                            <td>
                                {{ $reserva->fecha->format('d/m/Y') }}
                                @if($esHoy && !$esPasada)
                                <span class="badge bg-warning text-dark ms-1">Hoy</span>
                                @endif
                            </td>
                            <td><strong>{{ $reserva->laboratorio->nombre }}</strong></td>
                            <td>{{ $reserva->hora_inicio_formateada }}</td>
                            <td>{{ $reserva->hora_fin_formateada }}</td>
                            <td>{{ $reserva->duracion_formateada }}</td>
                            <td>{{ $reserva->materia ?? 'Sin especificar' }}</td>
                            <td>
                                @if($estaEnCurso)
                                    {{-- Pulsando animado cuando está en curso --}}
                                    <span class="badge bg-success d-flex align-items-center gap-1" style="width:fit-content;">
                                        <span class="rounded-circle bg-white" style="width:8px;height:8px;display:inline-block;animation:pulse 1s infinite;"></span>
                                        EN CURSO
                                    </span>
                                @else
                                    <span class="badge bg-{{ $reserva->estado_badge }}">
                                        {{ strtoupper($reserva->estado) }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center flex-wrap">
                                    @if($puedeF)
                                        {{-- Botón FINALIZAR CLASE --}}
                                        <form action="{{ route('profesor.reservas.completar', $reserva->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Finalizar la clase y liberar el laboratorio?')">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm fw-bold">
                                                <i class="bi bi-check-circle me-1"></i>Finalizar
                                            </button>
                                        </form>
                                    @elseif($esFutura)
                                        {{-- Botón CANCELAR --}}
                                        <form action="{{ route('profesor.reservas.destroy', $reserva->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Cancelar esta reserva?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Cancelar reserva">
                                                <i class="bi bi-x-circle me-1"></i>Cancelar
                                            </button>
                                        </form>
                                    @endif
                                    {{-- Botón VER CLASE — visible en completadas y en curso --}}
                                    @if(!$esFutura)
                                    <a href="{{ route('profesor.reservas.show', $reserva->id) }}"
                                       class="btn btn-outline-primary btn-sm" title="Ver estudiantes en clase">
                                        <i class="bi bi-people me-1"></i>Ver Clase
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2">
                {{ $reservas->links() }}
            </div>
            @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x" style="font-size:3rem;"></i>
                <p class="mt-2">No hay reservas registradas con los filtros seleccionados.</p>
                <a href="{{ route('profesor.reservas.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Crear primera reserva
                </a>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@section('scripts')
<style>
@keyframes pulse {
    0%   { opacity: 1; transform: scale(1); }
    50%  { opacity: 0.4; transform: scale(1.4); }
    100% { opacity: 1; transform: scale(1); }
}
</style>
@endsection
