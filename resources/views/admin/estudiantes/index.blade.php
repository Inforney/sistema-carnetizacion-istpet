@extends('layouts.app')

@section('title', 'Gestión de Estudiantes')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-people me-2"></i>Gestión de Estudiantes</h2>
            <p class="text-muted mb-0">Administra los estudiantes del instituto</p>
        </div>
        <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>Nuevo Estudiante
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
            <form method="GET" action="{{ route('admin.estudiantes.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Buscar</label>
                        <input type="text"
                            name="buscar"
                            class="form-control"
                            placeholder="Nombre, apellido o cédula"
                            value="{{ request('buscar') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Ciclo/Semestre</label>
                        <select name="ciclo" class="form-select">
                            <option value="">Todos</option>
                            <option value="PRIMER NIVEL" {{ request('ciclo') == 'PRIMER NIVEL' ? 'selected' : '' }}>Primer Semestre</option>
                            <option value="SEGUNDO NIVEL" {{ request('ciclo') == 'SEGUNDO NIVEL' ? 'selected' : '' }}>Segundo Semestre</option>
                            <option value="TERCER NIVEL" {{ request('ciclo') == 'TERCER NIVEL' ? 'selected' : '' }}>Tercer Semestre</option>
                            <option value="CUARTO NIVEL" {{ request('ciclo') == 'CUARTO NIVEL' ? 'selected' : '' }}>Cuarto Semestre</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                            <option value="bloqueado" {{ request('estado') == 'bloqueado' ? 'selected' : '' }}>Bloqueados</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Filtrar
                            </button>
                            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de estudiantes --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lista de Estudiantes ({{ $estudiantes->total() }})</h5>
        </div>
        <div class="card-body">
            @if($estudiantes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Documento</th>
                            <th>Contacto</th>
                            <th>Ciclo</th>
                            <th>Carnet</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudiantes as $estudiante)
                        <tr>
                            <td>
                                <strong>{{ $estudiante->nombreCompleto }}</strong>
                                <br>
                                <small class="text-muted">{{ $estudiante->nacionalidad }}</small>
                            </td>
                            <td>
                                <small>
                                    {{ strtoupper($estudiante->tipo_documento) }}<br>
                                    {{ $estudiante->cedula }}
                                </small>
                            </td>
                            <td>
                                <small>
                                    <i class="bi bi-envelope me-1"></i>{{ $estudiante->correo_institucional }}<br>
                                    <i class="bi bi-phone me-1"></i>{{ $estudiante->celular }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $estudiante->ciclo_nivel }}
                                </span>
                            </td>
                            <td>
                                @if($estudiante->carnet)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Asignado
                                </span>
                                @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-exclamation-circle"></i> Sin carnet
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($estudiante->estado === 'activo')
                                <span class="badge bg-success">Activo</span>
                                @else
                                <span class="badge bg-danger">Bloqueado</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.estudiantes.show', $estudiante->id) }}"
                                        class="btn btn-outline-primary"
                                        title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.estudiantes.edit', $estudiante->id) }}"
                                        class="btn btn-outline-warning"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.estudiantes.toggle', $estudiante->id) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-outline-{{ $estudiante->estado === 'activo' ? 'danger' : 'success' }}"
                                            title="{{ $estudiante->estado === 'activo' ? 'Bloquear' : 'Desbloquear' }}">
                                            <i class="bi bi-{{ $estudiante->estado === 'activo' ? 'lock' : 'unlock' }}"></i>
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
                {{ $estudiantes->withQueryString()->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="text-muted mt-3">No se encontraron estudiantes</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection