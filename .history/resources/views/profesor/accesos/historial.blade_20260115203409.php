@extends('layouts.app')

@section('title', 'Historial de Accesos')

@section('content')
<div class="container-fluid py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('profesor.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('profesor.accesos.index') }}">Accesos</a>
            </li>
            <li class="breadcrumb-item active">Historial</li>
        </ol>
    </nav>

    <h2 class="mb-4">
        <i class="bi bi-clock-history me-2"></i>Historial de Accesos
    </h2>

    {{-- Tabla de historial --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Todos mis registros</h5>
        </div>
        <div class="card-body">
            @if($accesos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Estudiante</th>
                            <th>Laboratorio</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Duración</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accesos as $acceso)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($acceso->fecha_entrada)->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $acceso->usuario->nombreCompleto }}</strong>
                                <br>
                                <small class="text-muted">{{ $acceso->usuario->cedula }}</small>
                            </td>
                            <td>
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $acceso->laboratorio->nombre }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($acceso->hora_entrada)->format('H:i') }}</td>
                            <td>
                                @if($acceso->hora_salida)
                                {{ \Carbon\Carbon::parse($acceso->hora_salida)->format('H:i') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger">Marcado Ausente</span>
                                @elseif($acceso->duracion_formateada)
                                {{ $acceso->duracion_formateada }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-triangle"></i> Ausente
                                </span>
                                @elseif($acceso->estaEnLaboratorio())
                                <span class="badge bg-success">
                                    <i class="bi bi-circle-fill"></i> Activo
                                </span>
                                @else
                                <span class="badge bg-secondary">Finalizado</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('profesor.accesos.detalle', $acceso->id) }}"
                                    class="btn btn-sm btn-info"
                                    title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
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
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="text-muted mt-3">No hay accesos registrados</p>
                <a href="{{ route('profesor.accesos.index') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-circle me-2"></i>Registrar Primer Acceso
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection