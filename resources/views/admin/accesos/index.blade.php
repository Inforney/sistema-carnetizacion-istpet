@extends('layouts.app')

@section('title', 'Gestión de Accesos')

@section('content')
<div class="container-fluid py-4">

    {{-- ── HEADER ── --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <div>
            <h2 class="mb-1" style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">
                <i class="bi bi-door-open me-2" style="color:var(--istpet-dorado);"></i>Gestión de Accesos
            </h2>
            <p class="text-muted mb-0" style="font-size:0.88rem;">Registro de entradas y salidas a laboratorios</p>
        </div>
        <a href="{{ route('admin.accesos.estadisticas') }}"
           class="btn btn-sm fw-bold"
           style="background:var(--istpet-dorado);color:var(--istpet-azul);">
            <i class="bi bi-graph-up me-1"></i>Ver Estadísticas
        </a>
    </div>
    <div class="mb-4" style="height:3px;background:linear-gradient(90deg,var(--istpet-dorado) 0%,var(--istpet-azul) 60%,transparent 100%);border-radius:2px;"></div>

    {{-- ── MINI STATS ── --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-center" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);border-top:4px solid #198754;">
                <div class="card-body py-3">
                    <div style="font-size:2rem;font-weight:700;color:#198754;font-family:'Oswald',sans-serif;">
                        {{ $miniStats['activos_ahora'] }}
                    </div>
                    <div style="font-size:0.78rem;color:#555;text-transform:uppercase;letter-spacing:0.5px;">
                        <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;vertical-align:middle;animation:pulse 1.5s infinite;"></i>
                        En labs ahora
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);border-top:4px solid var(--istpet-dorado);">
                <div class="card-body py-3">
                    <div style="font-size:2rem;font-weight:700;color:var(--istpet-azul);font-family:'Oswald',sans-serif;">
                        {{ $miniStats['accesos_hoy'] }}
                    </div>
                    <div style="font-size:0.78rem;color:#555;text-transform:uppercase;letter-spacing:0.5px;">Accesos hoy</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);border-top:4px solid var(--istpet-azul);">
                <div class="card-body py-3">
                    <div style="font-size:2rem;font-weight:700;color:var(--istpet-azul);font-family:'Oswald',sans-serif;">
                        {{ $miniStats['accesos_mes'] }}
                    </div>
                    <div style="font-size:0.78rem;color:#555;text-transform:uppercase;letter-spacing:0.5px;">
                        Este mes ({{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }})
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FILTROS ── --}}
    <div class="card mb-4" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
            <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                <i class="bi bi-funnel me-2" style="color:var(--istpet-dorado);"></i>Filtros
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.accesos.index') }}">
                <div class="row g-3">
                    {{-- Búsqueda texto libre --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="font-size:0.82rem;color:var(--istpet-azul);">
                            <i class="bi bi-search me-1"></i>Estudiante (nombre o cédula)
                        </label>
                        <input type="text"
                               name="buscar"
                               class="form-control"
                               placeholder="Ej: Juan Pérez o 1750123456"
                               value="{{ request('buscar') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold" style="font-size:0.82rem;color:var(--istpet-azul);">Desde</label>
                        <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold" style="font-size:0.82rem;color:var(--istpet-azul);">Hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold" style="font-size:0.82rem;color:var(--istpet-azul);">Laboratorio</label>
                        <select name="laboratorio_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($laboratorios as $lab)
                            <option value="{{ $lab->id }}" {{ request('laboratorio_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold" style="font-size:0.82rem;color:var(--istpet-azul);">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activo (en lab)</option>
                            <option value="finalizado" {{ request('estado') === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                            <option value="ausente" {{ request('estado') === 'ausente' ? 'selected' : '' }}>Marcado Ausente</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold" style="font-size:0.82rem;color:var(--istpet-azul);">Profesor</label>
                        <select name="profesor_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($profesores as $prof)
                            <option value="{{ $prof->id }}" {{ request('profesor_id') == $prof->id ? 'selected' : '' }}>
                                {{ $prof->nombres }} {{ $prof->apellidos }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-9 d-flex align-items-end gap-2">
                        <button type="submit" class="btn fw-bold"
                                style="background:var(--istpet-azul);color:#fff;">
                            <i class="bi bi-search me-1"></i>Filtrar
                        </button>
                        <a href="{{ route('admin.accesos.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Limpiar
                        </a>
                        @if(request()->hasAny(['buscar','fecha_desde','fecha_hasta','laboratorio_id','estado','profesor_id']))
                        <span class="badge ms-1" style="background:var(--istpet-dorado);color:var(--istpet-azul);font-size:0.78rem;padding:6px 10px;">
                            {{ $accesos->total() }} resultado(s)
                        </span>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TABLA ── --}}
    <div class="card" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header d-flex justify-content-between align-items-center"
             style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
            <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                <i class="bi bi-list-ul me-2" style="color:var(--istpet-dorado);"></i>
                Registros de Acceso
                <span class="ms-2" style="font-size:0.8rem;color:rgba(196,168,87,0.75);font-weight:400;">
                    ({{ $accesos->total() }} total)
                </span>
            </h6>
        </div>
        <div class="card-body p-0">
            @if($accesos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:0.84rem;">
                    <thead style="background:rgba(34,44,87,0.05);">
                        <tr>
                            <th class="ps-3" style="color:var(--istpet-azul);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">Fecha</th>
                            <th style="color:var(--istpet-azul);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">Estudiante</th>
                            <th style="color:var(--istpet-azul);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">Laboratorio</th>
                            <th style="color:var(--istpet-azul);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">Profesor / Entrada</th>
                            <th style="color:var(--istpet-azul);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">Entrada</th>
                            <th style="color:var(--istpet-azul);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">Salida</th>
                            <th style="color:var(--istpet-azul);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">Duración</th>
                            <th style="color:var(--istpet-azul);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">Estado</th>
                            <th style="color:var(--istpet-azul);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accesos as $acceso)
                        <tr>
                            <td class="ps-3" style="white-space:nowrap;">
                                <span style="font-weight:600;color:var(--istpet-azul);">
                                    {{ \Carbon\Carbon::parse($acceso->fecha_entrada)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <strong style="color:var(--istpet-azul);">{{ $acceso->usuario->nombreCompleto }}</strong><br>
                                <small class="text-muted">{{ $acceso->usuario->cedula }}</small>
                            </td>
                            <td>
                                <span style="color:var(--istpet-azul);">
                                    <i class="bi bi-building me-1" style="color:var(--istpet-dorado);"></i>{{ $acceso->laboratorio->nombre }}
                                </span>
                            </td>
                            <td>
                                @if($acceso->profesor)
                                <small style="color:#555;">
                                    <i class="bi bi-person-workspace me-1" style="color:var(--istpet-dorado);"></i>
                                    {{ $acceso->profesor->nombres }} {{ $acceso->profesor->apellidos }}
                                </small>
                                @else
                                <small class="text-muted">
                                    <i class="bi bi-qr-code me-1"></i>Escaneo QR
                                </small>
                                @endif
                            </td>
                            <td style="font-weight:600;">
                                {{ \Carbon\Carbon::parse($acceso->hora_entrada)->format('H:i') }}
                            </td>
                            <td>
                                @if($acceso->hora_salida)
                                {{ \Carbon\Carbon::parse($acceso->hora_salida)->format('H:i') }}
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger" style="font-size:0.72rem;">Ausente</span>
                                @elseif($acceso->duracion_formateada)
                                <span class="badge" style="background:rgba(34,44,87,0.1);color:var(--istpet-azul);font-size:0.72rem;">{{ $acceso->duracion_formateada }}</span>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger" style="font-size:0.72rem;">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Ausente
                                </span>
                                @elseif($acceso->estaEnLaboratorio())
                                <span class="badge" style="background:rgba(25,135,84,0.12);color:#198754;border:1px solid rgba(25,135,84,0.25);font-size:0.72rem;">
                                    <i class="bi bi-circle-fill me-1" style="font-size:0.45rem;vertical-align:middle;"></i>Activo
                                </span>
                                @else
                                <span class="badge bg-secondary" style="font-size:0.72rem;">Finalizado</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.accesos.show', $acceso->id) }}"
                                       class="btn btn-sm"
                                       style="background:rgba(34,44,87,0.08);color:var(--istpet-azul);padding:3px 8px;"
                                       title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.accesos.destroy', $acceso->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar este registro de acceso?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                style="padding:3px 8px;"
                                                title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-3 py-3">
                {{ $accesos->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size:3rem;color:#ccc;"></i>
                <p class="text-muted mt-3">No se encontraron accesos con los filtros seleccionados</p>
                <a href="{{ route('admin.accesos.index') }}" class="btn btn-sm btn-outline-secondary">Limpiar filtros</a>
            </div>
            @endif
        </div>
    </div>

</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.3; }
}
</style>
@endsection
