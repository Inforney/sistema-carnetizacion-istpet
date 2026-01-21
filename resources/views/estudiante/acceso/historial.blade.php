@extends('layouts.app')

@section('title', 'Mi Historial de Accesos')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">
        <i class="bi bi-clock-history me-2"></i>Mi Historial de Accesos
    </h2>

    @if($accesos->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Laboratorio</th>
                            <th>Fecha</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Duración</th>
                            <th>Método</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accesos as $acceso)
                        <tr class="{{ $acceso->marcado_ausente ? 'table-danger' : '' }}">
                            <td>
                                <strong>{{ $acceso->laboratorio->nombre }}</strong>
                                <br>
                                <small class="text-muted">{{ $acceso->laboratorio->ubicacion }}</small>
                            </td>
                            <td>{{ date('d/m/Y', strtotime($acceso->fecha_entrada)) }}</td>
                            <td>
                                <strong>{{ date('H:i', strtotime($acceso->hora_entrada)) }}</strong>
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger">Marcado Ausente</span>
                                @elseif($acceso->hora_salida)
                                <strong>{{ date('H:i', strtotime($acceso->hora_salida)) }}</strong>
                                @else
                                <span class="badge bg-warning text-dark">En curso</span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger">Invalidado</span>
                                @elseif($acceso->duracion_formateada)
                                <span class="badge bg-info">{{ $acceso->duracion_formateada }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->metodo_registro === 'qr_estudiante')
                                <span class="badge bg-success">
                                    <i class="bi bi-qr-code"></i> QR
                                </span>
                                @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-person"></i> Manual
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger"
                                    data-bs-toggle="tooltip"
                                    title="{{ $acceso->nota_ausencia }}">
                                    <i class="bi bi-x-circle"></i> Ausente
                                </span>
                                @else
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Válido
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-3">
                {{ $accesos->links() }}
            </div>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $accesos->total() }}</h3>
                    <small class="text-muted">Total de Accesos</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $accesos->where('marcado_ausente', false)->count() }}</h3>
                    <small class="text-muted">Accesos Válidos</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $accesos->where('marcado_ausente', true)->count() }}</h3>
                    <small class="text-muted">Marcados Ausente</small>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <p class="text-muted mt-3">Aún no tienes accesos registrados</p>
            <a href="{{ route('estudiante.acceso.escanear') }}" class="btn btn-primary">
                <i class="bi bi-qr-code-scan me-2"></i>Registrar Mi Primer Acceso
            </a>
        </div>
    </div>
    @endif
</div>

<script>
    // Activar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection