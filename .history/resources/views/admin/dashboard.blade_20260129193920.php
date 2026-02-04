@extends('layouts.app')

@section('title', 'Dashboard Administrador')

@section('content')
<div class="container-fluid py-4">
    {{-- Saludo --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>¡Bienvenido, Admin! 👋</h2>
            <p class="text-muted mb-0">Panel de control del administrador</p>
        </div>
        <div class="text-end">
            <small class="text-muted">{{ date('l, d F Y') }}</small>
        </div>
    </div>

    {{-- Estadísticas Principales --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Estudiantes</h6>
                            <h3 class="mb-0">{{ $totalEstudiantes }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-people display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Carnets Activos</h6>
                            <h3 class="mb-0">{{ $carnetsActivos }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-credit-card display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">En Laboratorios</h6>
                            <h3 class="mb-0">{{ $estudiantesEnLabs }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-building display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Solicitudes Pendientes</h6>
                            <h3 class="mb-0">{{ $solicitudesPendientes }}</h3>
                        </div>
                        <div class="text-danger">
                            <i class="bi bi-envelope display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones Rápidas --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #1a2342;">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-lightning-fill me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-person-plus d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Nuevo Estudiante</strong>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.carnets.create') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="bi bi-credit-card d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Generar Carnet</strong>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.laboratorios.index') }}" class="btn btn-outline-warning w-100 py-3">
                                <i class="bi bi-building d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Gestionar Laboratorios</strong>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.accesos.estadisticas') }}" class="btn btn-outline-info w-100 py-3">
                                <i class="bi bi-graph-up d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Ver Estadísticas</strong>
                            </a>
                        </div>
                    </div>

                    {{-- Segunda fila de acciones --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <a href="{{ route('admin.importacion.index') }}" class="btn btn-outline-danger w-100 py-3">
                                <i class="bi bi-file-earmark-excel d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Importación Masiva</strong>
                                <small class="d-block text-muted mt-1">Subir Excel</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.carnets.index') }}" class="btn btn-outline-secondary w-100 py-3">
                                <i class="bi bi-credit-card-2-front d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Ver Carnets</strong>
                                <small class="d-block text-muted mt-1">Todos los carnets</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-outline-secondary w-100 py-3">
                                <i class="bi bi-people-fill d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Ver Estudiantes</strong>
                                <small class="d-block text-muted mt-1">Lista completa</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-outline-secondary w-100 py-3">
                                <i class="bi bi-envelope-paper d-block mb-2" style="font-size: 2rem;"></i>
                                <strong>Solicitudes</strong>
                                <small class="d-block text-muted mt-1">Gestionar solicitudes</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Laboratorios con su ocupación --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #1a2342;">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-building me-2"></i>Estado de Laboratorios
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($laboratorios as $lab)
                        @php
                        $ocupacion = \App\Models\Acceso::where('laboratorio_id', $lab->id)
                        ->whereNull('hora_salida')
                        ->where('marcado_ausente', false)
                        ->whereDate('fecha_entrada', today())
                        ->count();
                        $porcentaje = $lab->capacidad > 0 ? round(($ocupacion / $lab->capacidad) * 100) : 0;
                        @endphp
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold">{{ $lab->nombre }}</h6>
                                    <p class="small text-muted mb-2">{{ $lab->ubicacion }}</p>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small">Ocupación:</span>
                                        <strong>{{ $ocupacion }}/{{ $lab->capacidad }}</strong>
                                    </div>
                                    <div class="progress mb-3" style="height: 20px;">
                                        <div class="progress-bar {{ $porcentaje > 80 ? 'bg-danger' : ($porcentaje > 50 ? 'bg-warning' : 'bg-success') }}"
                                            style="width: {{ $porcentaje }}%">
                                            {{ $porcentaje }}%
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.accesos.estudiantes-lab', $lab->id) }}"
                                        class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-people me-2"></i>Ver Estudiantes
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Últimos Accesos Registrados --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Últimos Accesos
                    </h5>
                </div>
                <div class="card-body">
                    @if($ultimosAccesos->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($ultimosAccesos as $acceso)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $acceso->usuario->nombreCompleto }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $acceso->laboratorio->nombre }}</small>
                                </div>
                                <div class="text-end">
                                    <small>{{ date('H:i', strtotime($acceso->hora_entrada)) }}</small>
                                    <br>
                                    @if($acceso->hora_salida)
                                    <span class="badge bg-success">Completado</span>
                                    @else
                                    <span class="badge bg-warning">En curso</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center py-3">No hay accesos registrados hoy</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope me-2"></i>Solicitudes Recientes
                    </h5>
                </div>
                <div class="card-body">
                    @if($solicitudesRecientes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($solicitudesRecientes as $solicitud)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $solicitud->usuario->nombreCompleto }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $solicitud->correo }}</small>
                                </div>
                                <a href="{{ route('admin.solicitudes.index') }}"
                                    class="btn btn-sm btn-primary">
                                    Atender
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center py-3">No hay solicitudes pendientes</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection