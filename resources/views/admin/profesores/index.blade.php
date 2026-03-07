@extends('layouts.app')

@section('title', 'Gestión de Profesores')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <div>
            <h2 class="mb-1" style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">
                <i class="bi bi-person-workspace me-2" style="color:var(--istpet-dorado);"></i>Gestión de Profesores
            </h2>
            <p class="text-muted mb-0" style="font-size:0.88rem;">Administra los profesores del instituto</p>
        </div>
        <a href="{{ route('admin.profesores.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Profesor
        </a>
    </div>
    <div class="mb-4" style="height:3px;background:linear-gradient(90deg,var(--istpet-dorado) 0%,var(--istpet-azul) 60%,transparent 100%);border-radius:2px;"></div>

    {{-- Estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Total Profesores</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-azul);">{{ $stats['total'] }}</h2>
                        </div>
                        <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card" style="border-top-color:#28a745!important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Activos</h6>
                            <h2 class="mb-0 fw-bold" style="color:#28a745;">{{ $stats['activos'] }}</h2>
                        </div>
                        <div class="stat-icon" style="color:#28a745;background:rgba(40,167,69,0.08);"><i class="bi bi-person-check-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card" style="border-top-color:#dc3545!important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Inactivos</h6>
                            <h2 class="mb-0 fw-bold" style="color:#dc3545;">{{ $stats['inactivos'] }}</h2>
                        </div>
                        <div class="stat-icon" style="color:#dc3545;background:rgba(220,53,69,0.08);"><i class="bi bi-person-x-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros de búsqueda --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filtros
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.profesores.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Buscar</label>
                        <input type="text"
                            name="buscar"
                            class="form-control"
                            placeholder="Nombre, apellido, cédula o correo"
                            value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Departamento</label>
                        <select name="departamento" class="form-select">
                            <option value="">Todos</option>
                            @foreach($departamentos as $dept)
                            <option value="{{ $dept }}" {{ request('departamento') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Filtrar
                            </button>
                            <a href="{{ route('admin.profesores.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Lista de Profesores --}}
    <div class="card">
        <div class="card-header bg-istpet">
            <h5 class="mb-0 text-white">
                <i class="bi bi-list-ul me-2" style="color:var(--istpet-dorado);"></i>
                Lista de Profesores
                <span class="badge ms-2" style="background:var(--istpet-dorado);color:var(--istpet-azul);font-size:0.8rem;">{{ $profesores->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($profesores->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background:rgba(34,44,87,0.05);">
                        <tr>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">#</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Profesor</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Cédula</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Correo</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Celular</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Especialidad</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Departamento</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Estado</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($profesores as $profesor)
                        <tr>
                            <td><strong>#{{ $profesor->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($profesor->foto_url)
                                    <img src="{{ asset($profesor->foto_url) }}"
                                         class="rounded-circle me-2"
                                         style="width:40px;height:40px;object-fit:cover;border:2px solid var(--istpet-dorado);"
                                         alt="Foto">
                                    @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                         style="width:40px;height:40px;background:rgba(34,44,87,0.1);color:var(--istpet-azul);border:2px solid rgba(34,44,87,0.15);">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $profesor->nombreCompleto }}</strong>
                                        @if($profesor->fecha_ingreso)
                                        <br><small class="text-muted">Desde: {{ date('d/m/Y', strtotime($profesor->fecha_ingreso)) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $profesor->cedula }}</td>
                            <td><small>{{ $profesor->correo }}</small></td>
                            <td>{{ $profesor->celular }}</td>
                            <td>{{ $profesor->especialidad ?? 'N/A' }}</td>
                            <td>{{ $profesor->departamento ?? 'N/A' }}</td>
                            <td>
                                @if($profesor->estado === 'activo')
                                <span class="badge" style="background:rgba(34,44,87,0.08);color:var(--istpet-azul);border:1px solid rgba(34,44,87,0.2);">
                                    <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;vertical-align:middle;color:#28a745;"></i>Activo
                                </span>
                                @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-lock me-1"></i>Inactivo
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.profesores.show', $profesor->id) }}"
                                        class="btn"
                                        style="background:var(--istpet-azul);color:white;border:none;"
                                        title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.profesores.edit', $profesor->id) }}"
                                        class="btn"
                                        style="background:var(--istpet-dorado);color:var(--istpet-azul);border:none;font-weight:700;"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.profesores.toggle', $profesor->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Confirmas {{ $profesor->estado === 'activo' ? 'desactivar' : 'activar' }} a {{ $profesor->nombreCompleto }}?')">
                                        @csrf
                                        <button type="submit"
                                            class="btn"
                                            style="background:{{ $profesor->estado === 'activo' ? '#dc3545' : '#198754' }};color:white;border:none;"
                                            title="{{ $profesor->estado === 'activo' ? 'Desactivar' : 'Activar' }}">
                                            <i class="bi bi-{{ $profesor->estado === 'activo' ? 'lock' : 'unlock' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.profesores.reset-password', $profesor->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Resetear contraseña de {{ $profesor->nombreCompleto }}? La nueva contraseña será ISTPET + últimos 4 dígitos de su cédula.')">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-outline-secondary btn-sm"
                                            title="Resetear contraseña">
                                            <i class="bi bi-key"></i>
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
                {{ $profesores->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="text-muted mt-3">No se encontraron profesores con esos filtros</p>
                <a href="{{ route('admin.profesores.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                </a>
                <a href="{{ route('admin.profesores.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Primer Profesor
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
