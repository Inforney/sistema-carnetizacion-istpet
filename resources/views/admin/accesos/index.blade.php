@extends('layouts.app')

@section('title', 'Gestión de Accesos')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-door-open me-2"></i>Gestión de Accesos</h2>
            <p class="text-muted mb-0">Administra todos los registros de acceso a laboratorios</p>
        </div>
        <a href="{{ route('admin.accesos.estadisticas') }}" class="btn btn-primary">
            <i class="bi bi-graph-up me-2"></i>Ver Estadísticas
        </a>
    </div>

    {{-- Filtros --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filtros
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.accesos.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Desde</label>
                        <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Estudiante</label>
                        <select name="estudiante_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($estudiantes as $est)
                            <option value="{{ $est->id }}" {{ request('estudiante_id') == $est->id ? 'selected' : '' }}>
                                {{ $est->nombreCompleto }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Laboratorio</label>
                        <select name="laboratorio_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($laboratorios as $lab)
                            <option value="{{ $lab->id }}" {{ request('laboratorio_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Profesor</label>
                        <select name="profesor_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($profesores as $prof)
                            <option value="{{ $prof->id }}" {{ request('profesor_id') == $prof->id ? 'selected' : '' }}>
                                {{ $prof->nombres }} {{ $prof->apellidos }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                            <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>Finalizados</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Filtrar
                            </button>
                            <a href="{{ route('admin.accesos.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de accesos --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lista de Accesos ({{ $accesos->total() }})</h5>
        </div>
        <div class="card-body">
            @if($accesos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Estudiante</th>
                            <th>Laboratorio</th>
                            <th>Profesor</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Duración</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accesos as $acceso)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($acceso->fecha_entrada)->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $acceso->usuario->nombreCompleto }}</strong>
                                <br>
                                <small class="text-muted">{{ $acceso->usuario->cedula }}</small>
                            </td>
                            <td>
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $acceso->laboratorio->nombre }}
                            </td>
                            <td>
                                @if($acceso->profesor)
                                <small>
                                    {{ $acceso->profesor->nombres }} {{ $acceso->profesor->apellidos }}
                                </small>
                                @else
                                <small class="text-muted">
                                    <i class="bi bi-qr-code"></i> Escaneo QR
                                </small>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($acceso->hora_entrada)->format('H:i') }}</td>
                            <td>
                                @if($acceso->hora_salida)
                                {{ \Carbon\Carbon::parse($acceso->hora_salida)->format('H:i') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger">Marcado Ausente</span>
                                @elseif($acceso->duracion_formateada)
                                <span class="badge bg-info">{{ $acceso->duracion_formateada }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Ausente
                                </span>
                                @elseif($acceso->estaEnLaboratorio())
                                <span class="badge bg-success">Activo</span>
                                @else
                                <span class="badge bg-secondary">Finalizado</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.accesos.show', $acceso->id) }}"
                                        class="btn btn-outline-primary"
                                        title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.accesos.destroy', $acceso->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Eliminar este registro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Eliminar">
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

            {{-- Paginación --}}
            <div class="mt-3">
                {{ $accesos->withQueryString()->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="text-muted mt-3">No se encontraron accesos con los filtros seleccionados</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection