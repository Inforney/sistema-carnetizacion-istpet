@extends('layouts.app')

@section('title', 'Gestión de Profesores')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>
                <i class="bi bi-person-workspace me-2"></i>Gestión de Profesores
            </h2>
            <p class="text-muted mb-0">Administra los profesores del instituto</p>
        </div>
        <div>
            <a href="{{ route('admin.profesores.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Profesor
            </a>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted">TOTAL PROFESORES</h6>
                    <h2 class="mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted">ACTIVOS</h6>
                    <h2 class="mb-0">{{ $stats['activos'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-danger border-4">
                <div class="card-body">
                    <h6 class="text-muted">INACTIVOS</h6>
                    <h2 class="mb-0">{{ $stats['inactivos'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Lista de Profesores --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lista de Profesores ({{ $profesores->total() }})</h5>
        </div>
        <div class="card-body">
            @if($profesores->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Profesor</th>
                            <th>Cédula</th>
                            <th>Correo</th>
                            <th>Celular</th>
                            <th>Especialidad</th>
                            <th>Departamento</th>
                            <th>Estado</th>
                            <th>Acciones</th>
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
                                         style="width: 40px; height: 40px; object-fit: cover;"
                                         alt="Foto">
                                    @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2"
                                         style="width: 40px; height: 40px;">
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
                            <td>{{ $profesor->correo }}</td>
                            <td>{{ $profesor->celular }}</td>
                            <td>{{ $profesor->especialidad ?? 'N/A' }}</td>
                            <td>{{ $profesor->departamento ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $profesor->estado === 'activo' ? 'success' : 'danger' }}">
                                    {{ ucfirst($profesor->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.profesores.show', $profesor->id) }}"
                                        class="btn btn-info"
                                        title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.profesores.edit', $profesor->id) }}"
                                        class="btn btn-warning"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.profesores.toggle', $profesor->id) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-{{ $profesor->estado === 'activo' ? 'danger' : 'success' }}"
                                            title="{{ $profesor->estado === 'activo' ? 'Desactivar' : 'Activar' }}">
                                            <i class="bi bi-{{ $profesor->estado === 'activo' ? 'lock' : 'unlock' }}"></i>
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
                <p class="text-muted mt-3">No hay profesores registrados</p>
                <a href="{{ route('admin.profesores.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Primer Profesor
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
