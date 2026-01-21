@extends('layouts.app')

@section('title', 'Gestión de Carnets')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>
                <i class="bi bi-credit-card me-2"></i>Gestión de Carnets
            </h2>
            <p class="text-muted mb-0">Administra los carnets estudiantiles del instituto</p>
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

    {{-- Estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted">CARNETS ACTIVOS</h6>
                    <h2 class="mb-0">{{ $stats['activos'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-danger border-4">
                <div class="card-body">
                    <h6 class="text-muted">CARNETS BLOQUEADOS</h6>
                    <h2 class="mb-0">{{ $stats['bloqueados'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted">POR VENCER (30 días)</h6>
                    <h2 class="mb-0">{{ $stats['por_vencer'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted">TOTAL</h6>
                    <h2 class="mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Lista de Carnets --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lista de Carnets ({{ $carnets->total() }})</h5>
        </div>
        <div class="card-body">
            @if($carnets->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Documento</th>
                            <th>Código QR</th>
                            <th>Emisión</th>
                            <th>Vencimiento</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carnets as $carnet)
                        <tr>
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
                                @if(is_object($carnet->fecha_vencimiento))
                                {{ $carnet->fecha_vencimiento->format('d/m/Y') }}
                                @else
                                {{ date('d/m/Y', strtotime($carnet->fecha_vencimiento)) }}
                                @endif
                                @else
                                N/A
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $carnet->estado === 'activo' ? 'success' : 'danger' }}">
                                    {{ ucfirst($carnet->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.carnets.show', $carnet->id) }}"
                                        class="btn btn-info"
                                        title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.carnets.descargar', $carnet->id) }}"
                                        class="btn btn-primary"
                                        title="Descargar PDF">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <form action="{{ route('admin.carnets.toggle', $carnet->id) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-{{ $carnet->estado === 'activo' ? 'danger' : 'success' }}"
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
                <p class="text-muted mt-3">No hay carnets registrados</p>
                <a href="{{ route('admin.carnets.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Primer Carnet
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection