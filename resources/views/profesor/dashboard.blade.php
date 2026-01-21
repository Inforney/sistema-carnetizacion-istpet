@extends('layouts.app')

@section('title', 'Dashboard Profesor')

@section('content')
<div class="container-fluid py-4">
    {{-- Saludo --}}
    <div class="mb-4">
        <h2>¡Bienvenido, {{ Auth::guard('profesor')->user()->nombres ?? 'Profesor' }}! 👋</h2>
        <p class="text-muted">Panel de control del profesor</p>
    </div>

    {{-- Estadísticas --}}
    <div class="row mb-4">
        @php
        $accesosHoy = \App\Models\Acceso::whereDate('fecha_entrada', today())->count();
        $estudiantesEnLabs = \App\Models\Acceso::whereNull('hora_salida')
        ->where('marcado_ausente', false)
        ->whereDate('fecha_entrada', today())
        ->distinct('usuario_id')
        ->count();
        $totalLaboratorios = \App\Models\Laboratorio::where('estado', 'activo')->count();
        $totalEstudiantes = \App\Models\Usuario::where('tipo_usuario', 'estudiante')->where('estado', 'activo')->count();
        @endphp

        <div class="col-md-3">
            <div class="card shadow-sm" style="background: #1a2342;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Accesos Hoy</h6>
                            <h3 class="mb-0">{{ $accesosHoy }}</h3>
                        </div>
                        <div>
                            <i class="bi bi-door-open display-4" style="color: #C4A857;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm" style="background: #C4A857;">
                <div class="card-body text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">En Laboratorios</h6>
                            <h3 class="mb-0">{{ $estudiantesEnLabs }}</h3>
                        </div>
                        <div>
                            <i class="bi bi-people display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm bg-primary">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Laboratorios</h6>
                            <h3 class="mb-0">{{ $totalLaboratorios }}</h3>
                        </div>
                        <div>
                            <i class="bi bi-building display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm bg-success">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Estudiantes Totales</h6>
                            <h3 class="mb-0">{{ $totalEstudiantes }}</h3>
                        </div>
                        <div>
                            <i class="bi bi-person-badge display-4"></i>
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
                        <i class="bi bi-lightning me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Registrar Acceso --}}
                        <div class="col-md-4">
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <i class="bi bi-door-open display-3 text-primary mb-3"></i>
                                    <h5>Registrar Acceso</h5>
                                    <p class="text-muted small">Registra entrada o salida de un estudiante</p>
                                    <a href="{{ route('profesor.accesos.index') }}" class="btn btn-primary">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Ir a Control de Accesos
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Ver Estudiantes en Labs --}}
                        <div class="col-md-4">
                            <div class="card h-100 border-warning">
                                <div class="card-body text-center">
                                    <i class="bi bi-building display-3 text-warning mb-3"></i>
                                    <h5>Laboratorios</h5>
                                    <p class="text-muted small">Ver estudiantes actualmente en cada laboratorio</p>
                                    @php
                                    $laboratoriosActivos = \App\Models\Laboratorio::where('estado', 'activo')->get();
                                    @endphp
                                    @if($laboratoriosActivos->count() > 0)
                                    <div class="dropdown">
                                        <button class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="bi bi-building me-2"></i>Seleccionar Lab
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($laboratoriosActivos as $lab)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('profesor.accesos.estudiantes-lab', $lab->id) }}">
                                                    {{ $lab->nombre }}
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @else
                                    <p class="text-muted">No hay laboratorios disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Ver Historial --}}
                        <div class="col-md-4">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <i class="bi bi-clock-history display-3 text-success mb-3"></i>
                                    <h5>Historial</h5>
                                    <p class="text-muted small">Consulta el historial de accesos</p>
                                    <a href="{{ route('profesor.accesos.historial') }}" class="btn btn-success">
                                        <i class="bi bi-clock-history me-2"></i>Ver Historial
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estado de Laboratorios --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #C4A857;">
                    <h5 class="mb-0 text-dark">
                        <i class="bi bi-building me-2"></i>Estado Actual de Laboratorios
                    </h5>
                </div>
                <div class="card-body">
                    @php
                    $laboratorios = \App\Models\Laboratorio::where('estado', 'activo')->get();
                    @endphp
                    @if($laboratorios->count() > 0)
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
                                    <a href="{{ route('profesor.accesos.estudiantes-lab', $lab->id) }}"
                                        class="btn btn-sm btn-outline-primary w-100">
                                        <i class="bi bi-people me-2"></i>Ver Estudiantes
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center">No hay laboratorios disponibles</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Información del Profesor --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #1a2342;">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-person me-2"></i>Mi Información
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted" width="40%">Nombre:</td>
                            <td><strong>{{ Auth::guard('profesor')->user()->nombres ?? '' }} {{ Auth::guard('profesor')->user()->apellidos ?? '' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Cédula:</td>
                            <td>{{ Auth::guard('profesor')->user()->cedula ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Correo:</td>
                            <td>{{ Auth::guard('profesor')->user()->correo ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightbulb me-2"></i>Información Importante
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">Puedes registrar entrada y salida manualmente por cédula</li>
                        <li class="mb-2">Revisa los estudiantes en cada laboratorio en tiempo real</li>
                        <li class="mb-2">Marca ausentes a estudiantes que registraron pero no están presentes</li>
                        <li class="mb-0">Consulta el historial completo de accesos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection