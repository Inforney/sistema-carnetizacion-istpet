@extends('layouts.app')

@section('title', 'Detalle del Acceso')

@section('content')
<div class="container-fluid py-4">

    <div class="card shadow-sm">
        <div class="card-header" style="background: #1a2342;">
            <h4 class="mb-0 text-white">
                <i class="bi bi-info-circle me-2"></i>Detalles del Acceso
            </h4>
        </div>
        <div class="card-body">
            {{-- Alerta si está marcado ausente --}}
            @if($acceso->marcado_ausente)
            <div class="alert alert-danger" role="alert">
                <h5 class="alert-heading">
                    <i class="bi bi-exclamation-triangle me-2"></i>Estudiante Marcado como Ausente
                </h5>
                <p><strong>Motivo:</strong> {{ $acceso->nota_ausencia ?? 'Sin motivo especificado' }}</p>
                @if($acceso->profesor_valida_id)
                @php
                $profesorValida = \App\Models\Profesor::find($acceso->profesor_valida_id);
                @endphp
                <hr>
                <p class="mb-0 small">
                    Marcado por: <strong>{{ $profesorValida ? $profesorValida->nombres . ' ' . $profesorValida->apellidos : 'N/A' }}</strong>
                </p>
                @endif
            </div>
            @endif

            <div class="row">
                {{-- Información del Estudiante --}}
                <div class="col-md-6 mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Información del Estudiante</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted" width="40%">Nombre Completo:</td>
                            <td><strong>{{ $acceso->usuario->nombreCompleto }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Cédula:</td>
                            <td>{{ $acceso->usuario->cedula }}</td>
                        </tr>
                        @if($acceso->usuario->correo_institucional)
                        <tr>
                            <td class="text-muted">Email:</td>
                            <td>{{ $acceso->usuario->correo_institucional }}</td>
                        </tr>
                        @else
                        <tr>
                            <td class="text-muted">Email:</td>
                            <td class="text-danger">No registrado</td>
                        </tr>
                        @endif
                        @if($acceso->usuario->carrera)
                        <tr>
                            <td class="text-muted">Carrera:</td>
                            <td>{{ $acceso->usuario->carrera }}</td>
                        </tr>
                        @else
                        <tr>
                            <td class="text-muted">Carrera:</td>
                            <td class="text-danger">No registrada</td>
                        </tr>
                        @endif
                        @if($acceso->usuario->ciclo_nivel)
                        <tr>
                            <td class="text-muted">Semestre:</td>
                            <td>{{ $acceso->usuario->ciclo_nivel }}</td>
                        </tr>
                        @else
                        <tr>
                            <td class="text-muted">Semestre:</td>
                            <td class="text-danger">No registrado</td>
                        </tr>
                        @endif
                    </table>
                </div>

                {{-- Información del Acceso --}}
                <div class="col-md-6 mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Información del Acceso</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted" width="40%">Laboratorio:</td>
                            <td>
                                <strong>{{ $acceso->laboratorio->nombre }}</strong><br>
                                <small class="text-muted">{{ $acceso->laboratorio->ubicacion }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Fecha Entrada:</td>
                            <td>{{ \Carbon\Carbon::parse($acceso->fecha_entrada)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Hora Entrada:</td>
                            <td><strong>{{ \Carbon\Carbon::parse($acceso->hora_entrada)->format('H:i') }}</strong></td>
                        </tr>
                        @if($acceso->hora_salida)
                        <tr>
                            <td class="text-muted">Hora Salida:</td>
                            <td><strong>{{ \Carbon\Carbon::parse($acceso->hora_salida)->format('H:i') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Duración:</td>
                            <td><span class="badge bg-info">{{ $acceso->duracion_formateada ?? 'N/A' }}</span></td>
                        </tr>
                        @else
                        <tr>
                            <td class="text-muted">Estado:</td>
                            <td><span class="badge bg-warning">Sin salida registrada</span></td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Método:</td>
                            <td>
                                @if($acceso->metodo_registro == 'qr_estudiante')
                                <span class="badge bg-primary">QR Estudiante</span>
                                @else
                                <span class="badge bg-secondary">Manual Profesor</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('admin.accesos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection