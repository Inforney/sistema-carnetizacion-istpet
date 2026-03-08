@extends('layouts.app')

@section('title', 'Dashboard Administrador')

@section('content')
<div class="container-fluid py-4">
    {{-- Encabezado con separador de marca --}}
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h2 class="mb-1" style="font-family:'Oswald',sans-serif; color:var(--istpet-azul); font-size:1.6rem;">
                    <i class="bi bi-speedometer2 me-2" style="color:var(--istpet-dorado);"></i>
                    Panel de Administración
                </h2>
                <p class="text-muted mb-0" style="font-size:0.88rem;">Control general del sistema &mdash; {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
            <span class="badge px-3 py-2" style="background:var(--istpet-azul); font-family:'Oswald',sans-serif; font-size:0.8rem; letter-spacing:0.5px;">
                <i class="bi bi-shield-fill me-1" style="color:var(--istpet-dorado);"></i>ADMINISTRADOR
            </span>
        </div>
        {{-- Separador de marca --}}
        <div class="mt-3" style="height:3px; background:linear-gradient(90deg, var(--istpet-dorado) 0%, var(--istpet-azul) 60%, transparent 100%); border-radius:2px;"></div>
    </div>

    {{-- Estadísticas Principales --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;">Total Estudiantes</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-azul);">{{ $totalEstudiantes }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;">Carnets Activos</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-azul);">{{ $carnetsActivos }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;">En Laboratorios</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-azul);">{{ $estudiantesEnLabs }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm stat-card" style="border-top-color:var(--istpet-dorado)!important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;">Solicitudes Pendientes</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-dorado);">{{ $solicitudesPendientes }}</h2>
                        </div>
                        <div class="stat-icon" style="color:var(--istpet-dorado);background:rgba(196,168,87,0.1);">
                            <i class="bi bi-envelope"></i>
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
                <div class="card-header bg-istpet">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-lightning-fill me-2" style="color:var(--istpet-dorado);"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body" style="background:rgba(34,44,87,0.03);">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.estudiantes.create') }}" class="btn action-btn w-100 py-3 text-decoration-none">
                                <i class="bi bi-person-plus d-block mb-2" style="font-size:2rem;"></i>
                                <strong>Nuevo Estudiante</strong>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.carnets.create') }}" class="btn action-btn gold w-100 py-3 text-decoration-none">
                                <i class="bi bi-credit-card d-block mb-2" style="font-size:2rem;"></i>
                                <strong>Generar Carnet</strong>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.profesores.index') }}" class="btn action-btn w-100 py-3 text-decoration-none">
                                <i class="bi bi-person-workspace d-block mb-2" style="font-size:2rem;"></i>
                                <strong>Gestionar Profesores</strong>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.laboratorios.index') }}" class="btn action-btn w-100 py-3 text-decoration-none">
                                <i class="bi bi-building d-block mb-2" style="font-size:2rem;"></i>
                                <strong>Gestionar Laboratorios</strong>
                            </a>
                        </div>
                    </div>

                    {{-- Segunda fila de acciones --}}
                    <div class="row g-3 mt-1">
                        <div class="col-md-3">
                            <a href="{{ route('admin.accesos.estadisticas') }}" class="btn action-btn w-100 py-3 text-decoration-none">
                                <i class="bi bi-graph-up d-block mb-2" style="font-size:2rem;"></i>
                                <strong>Ver Estadísticas</strong>
                                <small class="d-block text-muted mt-1">Reportes y gráficas</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.importacion.index') }}" class="btn action-btn gold w-100 py-3 text-decoration-none">
                                <i class="bi bi-file-earmark-excel d-block mb-2" style="font-size:2rem;"></i>
                                <strong>Importación Masiva</strong>
                                <small class="d-block text-muted mt-1">Subir Excel</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.carnets.index') }}" class="btn action-btn w-100 py-3 text-decoration-none">
                                <i class="bi bi-credit-card-2-front d-block mb-2" style="font-size:2rem;"></i>
                                <strong>Ver Carnets</strong>
                                <small class="d-block text-muted mt-1">Todos los carnets</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.solicitudes.index') }}" class="btn action-btn w-100 py-3 text-decoration-none">
                                <i class="bi bi-envelope-paper d-block mb-2" style="font-size:2rem;"></i>
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
                <div class="card-header bg-istpet">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-building me-2"></i>Estado de Laboratorios
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($laboratorios as $lab)
                        @php
                        $ocupacion = $lab->ocupacion_hoy ?? 0;
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
                                    <div class="progress mb-3" style="height: 12px; border-radius:6px;">
                                        <div class="progress-bar"
                                            style="width:{{ $porcentaje }}%; background-color:{{ $porcentaje > 80 ? '#dc3545' : ($porcentaje > 50 ? 'var(--istpet-dorado)' : 'var(--istpet-azul)') }}; border-radius:6px;">
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mb-2" style="font-size:0.75rem;">{{ $porcentaje }}% ocupado</small>
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
                                    <span class="badge badge-istpet">Completado</span>
                                    @elseif($acceso->marcado_ausente)
                                    <span class="badge bg-danger">Ausente</span>
                                    @else
                                    <span class="badge badge-gold">En curso</span>
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