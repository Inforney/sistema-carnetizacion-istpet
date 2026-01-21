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
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-person me-2"></i>Información del Estudiante
                    </h5>
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
                        @endif
                        @if($acceso->usuario->carrera)
                        <tr>
                            <td class="text-muted">Carrera:</td>
                            <td>{{ $acceso->usuario->carrera }}</td>
                        </tr>
                        @endif
                        @if($acceso->usuario->ciclo_nivel)
                        <tr>
                            <td class="text-muted">Semestre:</td>
                            <td>{{ $acceso->usuario->ciclo_nivel }}</td>
                        </tr>
                        @endif
                        @if($acceso->usuario->celular)
                        <tr>
                            <td class="text-muted">Celular:</td>
                            <td>{{ $acceso->usuario->celular }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                {{-- Información del Laboratorio --}}
                <div class="col-md-6 mb-4">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-building me-2"></i>Información del Laboratorio
                    </h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted" width="40%">Laboratorio:</td>
                            <td><strong>{{ $acceso->laboratorio->nombre }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ubicación:</td>
                            <td>{{ $acceso->laboratorio->ubicacion }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Capacidad:</td>
                            <td>{{ $acceso->laboratorio->capacidad }} estudiantes</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estado:</td>
                            <td>
                                @if($acceso->laboratorio->estado === 'activo')
                                <span class="badge bg-success">Activo</span>
                                @else
                                <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Detalles del Acceso --}}
            <div class="row">
                <div class="col-12 mb-4">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-clock-history me-2"></i>Detalles del Acceso
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Fecha de Entrada:</td>
                                    <td><strong>{{ \Carbon\Carbon::parse($acceso->fecha_entrada)->format('d/m/Y') }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Hora de Entrada:</td>
                                    <td><strong class="text-success">{{ \Carbon\Carbon::parse($acceso->hora_entrada)->format('H:i') }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Método de Registro:</td>
                                    <td>
                                        @if($acceso->metodo_registro === 'qr_estudiante')
                                        <span class="badge bg-success">
                                            <i class="bi bi-qr-code"></i> QR Escaneado
                                        </span>
                                        @elseif($acceso->metodo_registro === 'manual_profesor')
                                        <span class="badge bg-info">
                                            <i class="bi bi-person"></i> Registro Manual (Profesor)
                                        </span>
                                        @else
                                        <span class="badge bg-secondary">Otro</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Fecha de Salida:</td>
                                    <td>
                                        @if($acceso->fecha_salida)
                                        <strong>{{ \Carbon\Carbon::parse($acceso->fecha_salida)->format('d/m/Y') }}</strong>
                                        @else
                                        <span class="badge bg-warning text-dark">Aún en el laboratorio</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Hora de Salida:</td>
                                    <td>
                                        @if($acceso->hora_salida)
                                        <strong class="text-danger">{{ \Carbon\Carbon::parse($acceso->hora_salida)->format('H:i') }}</strong>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Duración:</td>
                                    <td>
                                        @if($acceso->duracion_formateada)
                                        <span class="badge bg-info">{{ $acceso->duracion_formateada }}</span>
                                        @else
                                        <span class="text-muted">En curso</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información de Validación --}}
            @if($acceso->profesor)
            <div class="row">
                <div class="col-12 mb-4">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-person-check me-2"></i>Información de Validación
                    </h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted" width="20%">Profesor Validador:</td>
                            <td>
                                <strong>{{ $acceso->profesor->nombres }} {{ $acceso->profesor->apellidos }}</strong>
                            </td>
                        </tr>
                        @if($acceso->profesor->correo_institucional)
                        <tr>
                            <td class="text-muted">Email Profesor:</td>
                            <td>{{ $acceso->profesor->correo_institucional }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            @endif

            {{-- Estado del Acceso --}}
            <div class="row">
                <div class="col-12">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-shield-check me-2"></i>Estado del Acceso
                    </h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted" width="20%">Estado General:</td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle"></i> Marcado Ausente
                                </span>
                                @elseif($acceso->hora_salida)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Completado
                                </span>
                                @else
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock"></i> En Curso
                                </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Registro Creado:</td>
                            <td>{{ \Carbon\Carbon::parse($acceso->created_at)->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Última Actualización:</td>
                            <td>{{ \Carbon\Carbon::parse($acceso->updated_at)->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('profesor.dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver al Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection