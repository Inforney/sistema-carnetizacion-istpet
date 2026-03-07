@extends('layouts.app')

@section('title', 'Gestión de Estudiantes')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <div>
            <h2 class="mb-1" style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">
                <i class="bi bi-people me-2" style="color:var(--istpet-dorado);"></i>Gestión de Estudiantes
            </h2>
            <p class="text-muted mb-0" style="font-size:0.88rem;">Administra los estudiantes del instituto</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.importacion.actualizar-niveles') }}"
               class="btn btn-sm fw-bold"
               style="background:var(--istpet-dorado);color:var(--istpet-azul);">
                <i class="bi bi-arrow-up-circle me-1"></i>Actualizar Niveles
            </a>
            <a href="{{ route('admin.importacion.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-file-earmark-excel me-1"></i>Importar Estudiantes
            </a>
            <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-person-plus me-1"></i>Nuevo Estudiante
            </a>
        </div>
    </div>
    <div class="mb-4" style="height:3px;background:linear-gradient(90deg,var(--istpet-dorado) 0%,var(--istpet-azul) 60%,transparent 100%);border-radius:2px;"></div>

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
                            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo</label>
                        <select name="rol" class="form-select">
                            <option value="">Todos</option>
                            <option value="estudiante" {{ request('rol') == 'estudiante' ? 'selected' : '' }}>Estudiantes</option>
                            <option value="graduado" {{ request('rol') == 'graduado' ? 'selected' : '' }}>Graduados</option>
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
        <div class="card-header bg-istpet">
            <h5 class="mb-0 text-white">
                <i class="bi bi-list-ul me-2" style="color:var(--istpet-dorado);"></i>
                Lista de Estudiantes
                <span class="badge ms-2" style="background:var(--istpet-dorado);color:var(--istpet-azul);font-size:0.8rem;">{{ $estudiantes->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($estudiantes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background:rgba(34,44,87,0.05);">
                        <tr>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Estudiante</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Documento</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Contacto</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Ciclo</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Carnet</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Estado</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Acciones</th>
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
                                <span class="badge" style="background:rgba(34,44,87,0.1);color:var(--istpet-azul);font-weight:600;font-size:0.75rem;">
                                    {{ $estudiante->ciclo_nivel }}
                                </span>
                            </td>
                            <td>
                                @if($estudiante->carnet)
                                <span class="badge" style="background:var(--istpet-azul);color:white;">
                                    <i class="bi bi-check-circle me-1"></i>Asignado
                                </span>
                                @else
                                <span class="badge" style="background:rgba(196,168,87,0.15);color:#8a6d00;border:1px solid rgba(196,168,87,0.4);">
                                    <i class="bi bi-exclamation-circle me-1"></i>Sin carnet
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($estudiante->tipo_usuario === 'graduado')
                                <span class="badge" style="background:var(--istpet-dorado);color:var(--istpet-azul);font-weight:700;">
                                    <i class="bi bi-mortarboard me-1"></i>Graduado
                                </span>
                                @elseif($estudiante->estado === 'activo')
                                <span class="badge" style="background:rgba(34,44,87,0.08);color:var(--istpet-azul);border:1px solid rgba(34,44,87,0.2);">
                                    <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;vertical-align:middle;color:#28a745;"></i>Activo
                                </span>
                                @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-lock me-1"></i>Bloqueado
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.estudiantes.show', $estudiante->id) }}"
                                        class="btn" style="background:var(--istpet-azul);color:white;border:none;"
                                        title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.estudiantes.edit', $estudiante->id) }}"
                                        class="btn" style="background:var(--istpet-dorado);color:var(--istpet-azul);border:none;font-weight:700;"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($estudiante->tipo_usuario === 'graduado')
                                        <button type="button" class="btn btn-secondary disabled" disabled
                                            title="Graduado — gestionar desde Profesores">
                                            <i class="bi bi-mortarboard"></i>
                                        </button>
                                    @else
                                    <form action="{{ route('admin.estudiantes.toggle', $estudiante->id) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn"
                                            style="background:{{ $estudiante->estado === 'activo' ? '#dc3545' : '#198754' }};color:white;border:none;"
                                            title="{{ $estudiante->estado === 'activo' ? 'Bloquear' : 'Desbloquear' }}">
                                            <i class="bi bi-{{ $estudiante->estado === 'activo' ? 'lock' : 'unlock' }}"></i>
                                        </button>
                                    </form>
                                    @endif
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