@extends('layouts.app')

@section('title', 'Dashboard Profesor')

@section('content')
<div class="container-fluid py-4">

    {{-- Encabezado con separador de marca --}}
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h2 class="mb-1" style="font-family:'Oswald',sans-serif; color:var(--istpet-azul); font-size:1.6rem;">
                    <i class="bi bi-person-workspace me-2" style="color:var(--istpet-dorado);"></i>
                    Bienvenido, {{ Auth::guard('profesor')->user()->nombres ?? 'Profesor' }}
                </h2>
                <p class="text-muted mb-0" style="font-size:0.88rem;">Panel de control del profesor &mdash; {{ date('l, d \d\e F \d\e Y') }}</p>
            </div>
            <span class="badge px-3 py-2" style="background:var(--istpet-azul); font-family:'Oswald',sans-serif; font-size:0.8rem; letter-spacing:0.5px;">
                <i class="bi bi-person-check me-1" style="color:var(--istpet-dorado);"></i>PROFESOR
            </span>
        </div>
        {{-- Separador de marca --}}
        <div class="mt-3" style="height:3px; background:linear-gradient(90deg, var(--istpet-dorado) 0%, var(--istpet-azul) 60%, transparent 100%); border-radius:2px;"></div>
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
            <div class="card shadow-sm stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Accesos Hoy</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-azul);">{{ $accesosHoy }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-door-open"></i>
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
                            <h6 class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">En Laboratorios</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-dorado);">{{ $estudiantesEnLabs }}</h2>
                        </div>
                        <div class="stat-icon" style="color:var(--istpet-dorado);background:rgba(196,168,87,0.1);">
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
                            <h6 class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Laboratorios</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-azul);">{{ $totalLaboratorios }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-building"></i>
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
                            <h6 class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Estudiantes Activos</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-azul);">{{ $totalEstudiantes }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reservas de hoy --}}
    @php
        $profLogueado = Auth::guard('profesor')->user();
        $reservasHoy = \App\Models\ReservaLaboratorio::with('laboratorio')
            ->where('profesor_id', $profLogueado->id)
            ->whereDate('fecha', today())
            ->whereNotIn('estado', ['cancelada'])
            ->orderBy('hora_inicio')
            ->get();
    @endphp
    @if($reservasHoy->count() > 0)
    <div class="card mb-4" style="border-left:5px solid var(--istpet-dorado);">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:linear-gradient(90deg,#fff9e6,#fff);">
            <h6 class="mb-0 fw-bold" style="color:var(--istpet-azul);">
                <i class="bi bi-calendar-day me-1" style="color:var(--istpet-dorado);"></i>
                Mis reservas para hoy — {{ \Carbon\Carbon::today()->locale('es')->isoFormat('dddd D [de] MMMM') }}
            </h6>
            <a href="{{ route('profesor.reservas.index') }}" class="btn btn-outline-primary btn-sm">Ver todas</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Laboratorio</th><th>Hora inicio</th><th>Hora fin</th><th>Materia</th><th>Estado</th></tr>
                    </thead>
                    <tbody>
                        @foreach($reservasHoy as $rv)
                        <tr>
                            <td><i class="bi bi-display me-1 text-primary"></i><strong>{{ $rv->laboratorio->nombre }}</strong></td>
                            <td>{{ $rv->hora_inicio_formateada }}</td>
                            <td>{{ $rv->hora_fin_formateada }}</td>
                            <td>{{ $rv->materia ?? '—' }}</td>
                            <td><span class="badge bg-{{ $rv->estado_badge }}">{{ strtoupper($rv->estado) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Acciones Rápidas --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-istpet">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-lightning-fill me-2" style="color:var(--istpet-dorado);"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        {{-- Registrar Acceso --}}
                        <div class="col-md-3">
                            <div class="card h-100" style="border:2px solid var(--istpet-azul); border-radius:10px; transition:all 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(34,44,87,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                <div class="card-body text-center py-4">
                                    <div class="mb-3" style="width:60px;height:60px;border-radius:12px;background:rgba(34,44,87,0.08);display:inline-flex;align-items:center;justify-content:center;">
                                        <i class="bi bi-door-open" style="font-size:1.8rem;color:var(--istpet-azul);"></i>
                                    </div>
                                    <h5 style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">Registrar Acceso</h5>
                                    <p class="text-muted small mb-3">Registra entrada o salida de un estudiante</p>
                                    <a href="{{ route('profesor.accesos.index') }}" class="btn btn-primary btn-sm px-4">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>Ir a Control
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Reservar Laboratorio --}}
                        <div class="col-md-3">
                            <div class="card h-100" style="border:2px solid var(--istpet-dorado); border-radius:10px; transition:all 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(196,168,87,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                <div class="card-body text-center py-4">
                                    <div class="mb-3" style="width:60px;height:60px;border-radius:12px;background:rgba(196,168,87,0.1);display:inline-flex;align-items:center;justify-content:center;">
                                        <i class="bi bi-calendar-plus" style="font-size:1.8rem;color:var(--istpet-dorado);"></i>
                                    </div>
                                    <h5 style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">Mis Reservas</h5>
                                    <p class="text-muted small mb-3">Agenda tu clase antes de ir al laboratorio</p>
                                    <a href="{{ route('profesor.reservas.create') }}" class="btn btn-sm px-4 fw-bold" style="background:var(--istpet-dorado);color:var(--istpet-azul);border:none;">
                                        <i class="bi bi-plus-circle me-1"></i>Nueva Reserva
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Ver Laboratorios --}}
                        <div class="col-md-3">
                            <div class="card h-100" style="border:2px solid var(--istpet-azul); border-radius:10px; transition:all 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(34,44,87,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                <div class="card-body text-center py-4">
                                    <div class="mb-3" style="width:60px;height:60px;border-radius:12px;background:rgba(34,44,87,0.08);display:inline-flex;align-items:center;justify-content:center;">
                                        <i class="bi bi-building" style="font-size:1.8rem;color:var(--istpet-azul);"></i>
                                    </div>
                                    <h5 style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">Laboratorios</h5>
                                    <p class="text-muted small mb-3">Ver estudiantes actualmente en cada laboratorio</p>
                                    @php $laboratoriosActivos = \App\Models\Laboratorio::where('estado', 'activo')->get(); @endphp
                                    @if($laboratoriosActivos->count() > 0)
                                    <div class="dropdown">
                                        <button class="btn btn-sm px-4 dropdown-toggle" style="background:var(--istpet-azul);color:white;font-weight:600;border:none;" data-bs-toggle="dropdown">
                                            <i class="bi bi-building me-1"></i>Seleccionar
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($laboratoriosActivos as $lab)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('profesor.accesos.estudiantes-lab', $lab->id) }}">
                                                    <i class="bi bi-display me-2" style="color:var(--istpet-azul);"></i>{{ $lab->nombre }}
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @else
                                    <p class="text-muted small">No hay laboratorios disponibles</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Ver Historial --}}
                        <div class="col-md-3">
                            <div class="card h-100" style="border:2px solid var(--istpet-azul); border-radius:10px; transition:all 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(34,44,87,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                <div class="card-body text-center py-4">
                                    <div class="mb-3" style="width:60px;height:60px;border-radius:12px;background:rgba(34,44,87,0.08);display:inline-flex;align-items:center;justify-content:center;">
                                        <i class="bi bi-clock-history" style="font-size:1.8rem;color:var(--istpet-azul);"></i>
                                    </div>
                                    <h5 style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">Historial</h5>
                                    <p class="text-muted small mb-3">Consulta y descarga reportes de accesos</p>
                                    <a href="{{ route('profesor.accesos.historial') }}" class="btn btn-primary btn-sm px-4">
                                        <i class="bi bi-clock-history me-1"></i>Ver Historial
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
                <div class="card-header bg-istpet">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-building me-2" style="color:var(--istpet-dorado);"></i>Estado Actual de Laboratorios
                    </h5>
                </div>
                <div class="card-body">
                    @php $laboratorios = \App\Models\Laboratorio::where('estado', 'activo')->get(); @endphp
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
                            <div class="card h-100" style="border-left:4px solid var(--istpet-dorado); border-radius:8px;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0" style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">{{ $lab->nombre }}</h6>
                                        <span class="badge" style="background:{{ $porcentaje > 80 ? '#dc3545' : ($porcentaje > 50 ? 'var(--istpet-dorado)' : 'var(--istpet-azul)') }};color:{{ $porcentaje > 50 && $porcentaje <= 80 ? 'var(--istpet-azul)' : 'white' }};font-size:0.7rem;">
                                            {{ $porcentaje }}%
                                        </span>
                                    </div>
                                    <p class="small text-muted mb-2">{{ $lab->ubicacion }}</p>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small text-muted">Ocupación:</span>
                                        <strong style="color:var(--istpet-azul);">{{ $ocupacion }} / {{ $lab->capacidad }}</strong>
                                    </div>
                                    <div class="progress mb-3" style="height:8px; border-radius:4px; background:rgba(34,44,87,0.1);">
                                        <div class="progress-bar" style="width:{{ $porcentaje }}%; background-color:{{ $porcentaje > 80 ? '#dc3545' : ($porcentaje > 50 ? 'var(--istpet-dorado)' : 'var(--istpet-azul)') }}; border-radius:4px;"></div>
                                    </div>
                                    <a href="{{ route('profesor.accesos.estudiantes-lab', $lab->id) }}" class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-people me-1"></i>Ver Estudiantes
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center py-3">
                        <i class="bi bi-building me-2"></i>No hay laboratorios disponibles
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Información del Profesor --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-istpet">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-person me-2" style="color:var(--istpet-dorado);"></i>Mi Información
                    </h5>
                </div>
                <div class="card-body">
                    @php $prof = Auth::guard('profesor')->user(); @endphp
                    <div class="text-center mb-3 pb-3" style="border-bottom: 2px solid rgba(196,168,87,0.2);">
                        @if($prof && $prof->foto_url)
                        <img src="{{ asset($prof->foto_url) }}" alt="Foto" class="rounded-circle" style="width:80px;height:80px;object-fit:cover;border:3px solid var(--istpet-dorado);">
                        @else
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;background:var(--istpet-azul);border:3px solid var(--istpet-dorado);">
                            <span style="font-family:'Oswald',sans-serif;font-size:1.8rem;color:var(--istpet-dorado);font-weight:700;">
                                {{ strtoupper(substr($prof->nombres ?? 'P', 0, 1)) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted" width="35%"><i class="bi bi-person me-1"></i>Nombre:</td>
                            <td><strong>{{ $prof->nombres ?? '' }} {{ $prof->apellidos ?? '' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-card-text me-1"></i>Cédula:</td>
                            <td>{{ $prof->cedula ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-envelope me-1"></i>Correo:</td>
                            <td>{{ $prof->correo ?? 'N/A' }}</td>
                        </tr>
                        @if($prof->especialidad)
                        <tr>
                            <td class="text-muted"><i class="bi bi-book me-1"></i>Especialidad:</td>
                            <td>{{ $prof->especialidad }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header" style="background:white; border-bottom:2px solid var(--istpet-dorado);">
                    <h5 class="mb-0" style="color:var(--istpet-azul);">
                        <i class="bi bi-info-circle me-2" style="color:var(--istpet-dorado);"></i>Información Importante
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0" style="padding-left:1.2rem;">
                        <li class="mb-2" style="color:#444;">Puedes registrar entrada y salida manualmente por cédula</li>
                        <li class="mb-2" style="color:#444;">Revisa los estudiantes en cada laboratorio en tiempo real</li>
                        <li class="mb-2" style="color:#444;">Marca ausentes a estudiantes que registraron pero no están presentes</li>
                        <li style="color:#444;">Consulta el historial completo de accesos desde el menú</li>
                    </ul>
                    <div class="mt-3 p-2 rounded" style="background:rgba(34,44,87,0.05);border-left:3px solid var(--istpet-dorado);">
                        <small class="text-muted"><i class="bi bi-clock me-1" style="color:var(--istpet-dorado);"></i>
                            Última actualización: {{ now()->format('H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
