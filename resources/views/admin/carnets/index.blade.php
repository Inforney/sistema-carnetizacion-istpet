@extends('layouts.app')

@section('title', 'Gestión de Carnets')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <div>
            <h2 class="mb-1" style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">
                <i class="bi bi-credit-card me-2" style="color:var(--istpet-dorado);"></i>Gestión de Carnets
            </h2>
            <p class="text-muted mb-0" style="font-size:0.88rem;">Administra los carnets estudiantiles del instituto</p>
        </div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Carnet
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.carnets.create') }}">
                        <i class="bi bi-person-plus me-2"></i>Crear Carnet Individual
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('admin.carnets.generar-masivo') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item" onclick="return confirm('¿Generar carnets para todos los estudiantes sin carnet?')">
                            <i class="bi bi-lightning me-2"></i>Generar Carnets Masivos
                        </button>
                    </form>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.carnets.descargar-masivo') }}">
                        <i class="bi bi-download me-2"></i>Descargar Todos (PDF)
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="mb-4" style="height:3px;background:linear-gradient(90deg,var(--istpet-dorado) 0%,var(--istpet-azul) 60%,transparent 100%);border-radius:2px;"></div>

    {{-- Estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Carnets Activos</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-azul);">{{ $stats['activos'] }}</h2>
                        </div>
                        <div class="stat-icon"><i class="bi bi-credit-card-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card" style="border-top-color:#dc3545!important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Bloqueados</h6>
                            <h2 class="mb-0 fw-bold" style="color:#dc3545;">{{ $stats['bloqueados'] }}</h2>
                        </div>
                        <div class="stat-icon" style="color:#dc3545;background:rgba(220,53,69,0.08);"><i class="bi bi-lock-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card" style="border-top-color:var(--istpet-dorado)!important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Por Vencer (30d)</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-dorado);">{{ $stats['por_vencer'] }}</h2>
                        </div>
                        <div class="stat-icon" style="color:var(--istpet-dorado);background:rgba(196,168,87,0.1);"><i class="bi bi-clock-history"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Total</h6>
                            <h2 class="mb-0 fw-bold" style="color:var(--istpet-azul);">{{ $stats['total'] }}</h2>
                        </div>
                        <div class="stat-icon"><i class="bi bi-collection"></i></div>
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
            <form method="GET" action="{{ route('admin.carnets.index') }}">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Buscar estudiante</label>
                        <input type="text"
                            name="buscar"
                            class="form-control"
                            placeholder="Nombre, apellido o cédula"
                            value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                            <option value="bloqueado" {{ request('estado') == 'bloqueado' ? 'selected' : '' }}>Bloqueados</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Por vencer</label>
                        <select name="por_vencer" class="form-select">
                            <option value="">Todos</option>
                            <option value="1" {{ request('por_vencer') == '1' ? 'selected' : '' }}>Próximos 30 días</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Filtrar
                            </button>
                            <a href="{{ route('admin.carnets.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Lista de Carnets --}}
    <div class="card">
        <div class="card-header bg-istpet">
            <h5 class="mb-0 text-white">
                <i class="bi bi-list-ul me-2" style="color:var(--istpet-dorado);"></i>
                Lista de Carnets
                <span class="badge ms-2" style="background:var(--istpet-dorado);color:var(--istpet-azul);font-size:0.8rem;">{{ $carnets->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($carnets->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background:rgba(34,44,87,0.05);">
                        <tr>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">#</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Estudiante</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Documento</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Código QR</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Emisión</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Vencimiento</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Estado</th>
                            <th style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:600;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carnets as $carnet)
                        @php
                            $porVencer = $carnet->fecha_vencimiento && \Carbon\Carbon::parse($carnet->fecha_vencimiento)->lte(\Carbon\Carbon::now()->addDays(30)) && $carnet->estado === 'activo';
                        @endphp
                        <tr class="{{ $porVencer ? 'table-warning' : '' }}">
                            <td><strong>#{{ $carnet->id }}</strong></td>
                            <td>
                                <strong>{{ $carnet->usuario->nombreCompleto }}</strong>
                                <br>
                                <small class="text-muted">{{ $carnet->usuario->ciclo_nivel }}</small>
                            </td>
                            <td>{{ $carnet->usuario->cedula }}</td>
                            <td><code>{{ $carnet->codigo_qr }}</code></td>
                            <td>
                                @if(is_object($carnet->fecha_emision))
                                {{ $carnet->fecha_emision->format('d/m/Y') }}
                                @else
                                {{ date('d/m/Y', strtotime($carnet->fecha_emision)) }}
                                @endif
                            </td>
                            <td>
                                @if($carnet->fecha_vencimiento)
                                @php $fechaVenc = is_object($carnet->fecha_vencimiento) ? $carnet->fecha_vencimiento : \Carbon\Carbon::parse($carnet->fecha_vencimiento); @endphp
                                <span class="{{ $porVencer ? 'text-warning fw-bold' : '' }}">
                                    {{ $fechaVenc->format('d/m/Y') }}
                                    @if($porVencer)
                                    <br><small class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Vence pronto</small>
                                    @endif
                                </span>
                                @else
                                N/A
                                @endif
                            </td>
                            <td>
                                @if($carnet->estado === 'activo')
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
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.carnets.show', $carnet->id) }}"
                                        class="btn" style="background:var(--istpet-azul);color:white;border:none;"
                                        title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.carnets.descargar', $carnet->id) }}"
                                        class="btn" style="background:var(--istpet-dorado);color:var(--istpet-azul);border:none;font-weight:700;"
                                        title="Descargar PDF">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <form action="{{ route('admin.carnets.toggle', $carnet->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Confirmas {{ $carnet->estado === 'activo' ? 'bloquear' : 'activar' }} este carnet?')">
                                        @csrf
                                        <button type="submit"
                                            class="btn"
                                            style="background:{{ $carnet->estado === 'activo' ? '#dc3545' : '#198754' }};color:white;border:none;"
                                            title="{{ $carnet->estado === 'activo' ? 'Bloquear' : 'Activar' }}">
                                            <i class="bi bi-{{ $carnet->estado === 'activo' ? 'lock' : 'unlock' }}"></i>
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
                {{ $carnets->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="text-muted mt-3">No hay carnets que coincidan con los filtros</p>
                <a href="{{ route('admin.carnets.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                </a>
                <a href="{{ route('admin.carnets.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Primer Carnet
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
