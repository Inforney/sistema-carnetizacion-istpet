@extends('layouts.app')

@section('title', 'Dashboard - Administrador')

@section('content')
<div class="container-fluid">
    {{-- Saludo --}}
    <div class="mb-4">
        <h1 class="display-6">Panel de Administración 👨‍💼</h1>
        <p class="text-muted">Bienvenido, {{ Auth::guard('administrador')->user()->usuario }}</p>
    </div>

    {{-- Estadísticas Principales --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">ESTUDIANTES</p>
                            <h3 class="mb-0">{{ $stats['total_estudiantes'] ?? 0 }}</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> {{ $stats['estudiantes_activos'] ?? 0 }} activos
                            </small>
                        </div>
                        <div class="text-primary" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">PROFESORES</p>
                            <h3 class="mb-0">{{ $stats['total_profesores'] ?? 0 }}</h3>
                            <small class="text-muted">Docentes registrados</small>
                        </div>
                        <div class="text-success" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">CARNETS</p>
                            <h3 class="mb-0">{{ $stats['total_carnets'] ?? 0 }}</h3>
                            <small class="text-success">
                                <i class="bi bi-check-circle"></i> {{ $stats['carnets_activos'] ?? 0 }} activos
                            </small>
                        </div>
                        <div class="text-warning" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="bi bi-credit-card"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">LABORATORIOS</p>
                            <h3 class="mb-0">{{ $stats['total_laboratorios'] ?? 0 }}</h3>
                            <small class="text-muted">Espacios disponibles</small>
                        </div>
                        <div class="text-info" style="font-size: 2.5rem; opacity: 0.3;">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estadísticas de Accesos --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-calendar-check me-2"></i>Accesos de Hoy
                    </h5>
                    <h2 class="mb-0">{{ $stats['accesos_hoy'] ?? 0 }}</h2>
                    <small class="text-muted">registros totales</small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-people me-2"></i>Estudiantes en Laboratorios
                    </h5>
                    <h2 class="mb-0">{{ $stats['estudiantes_en_labs'] ?? 0 }}</h2>
                    <small class="text-success">estudiantes ahora mismo</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Ocupación de Laboratorios --}}
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>Estado de Laboratorios
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($laboratorios) && $laboratorios->count() > 0)
                    @foreach($laboratorios as $lab)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">{{ $lab->nombre }}</h6>
                            <span class="badge bg-{{ $lab->estado === 'disponible' ? 'success' : 'danger' }}">
                                {{ ucfirst($lab->estado) }}
                            </span>
                        </div>

                        @php
                        $porcentaje = isset($lab->porcentaje) ? $lab->porcentaje : 0;
                        $ocupacion = isset($lab->ocupacion_actual) ? $lab->ocupacion_actual : 0;
                        $capacidad = isset($lab->capacidad) ? $lab->capacidad : 0;

                        if ($porcentaje > 80) {
                        $colorBarra = 'bg-danger';
                        } elseif ($porcentaje > 50) {
                        $colorBarra = 'bg-warning';
                        } else {
                        $colorBarra = 'bg-success';
                        }
                        @endphp

                        <div class="progress mb-1" style="height: 20px;">
                            <div class="progress-bar {{ $colorBarra }}"
                                role="progressbar"
                                style="width: {{ $porcentaje }}%"
                                aria-valuenow="{{ $porcentaje }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                                {{ $ocupacion }}/{{ $capacidad }}
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-geo-alt"></i> {{ $lab->ubicacion }}
                            | {{ round($porcentaje) }}% ocupado
                        </small>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="text-muted mt-2">No hay laboratorios registrados</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Últimos Accesos --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Últimos Accesos Registrados
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($ultimosAccesos) && $ultimosAccesos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Laboratorio</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosAccesos as $acceso)
                                <tr>
                                    <td>
                                        <small>{{ optional($acceso->usuario)->nombreCompleto ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <small>{{ optional($acceso->laboratorio)->nombre ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($acceso->hora_entrada)->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($acceso->estaEnLaboratorio())
                                        <span class="badge bg-success">Activo</span>
                                        @else
                                        <span class="badge bg-secondary">Finalizado</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="text-muted mt-2">No hay accesos registrados aún</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones Rápidas --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-person-plus d-block mb-2" style="font-size: 2rem;"></i>
                                Nuevo Estudiante
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.carnets.create') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-credit-card d-block mb-2" style="font-size: 2rem;"></i>
                                Generar Carnet
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.laboratorios.index') }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-building d-block mb-2" style="font-size: 2rem;"></i>
                                Gestionar Laboratorios
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.accesos.estadisticas') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-file-earmark-bar-graph d-block mb-2" style="font-size: 2rem;"></i>
                                Ver Estadísticas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection