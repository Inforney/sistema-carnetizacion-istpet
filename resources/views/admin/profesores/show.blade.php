@extends('layouts.app')

@section('title', 'Detalle del Profesor')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-person-badge me-2"></i>Detalle del Profesor
                        </h5>
                        <div>
                            <a href="{{ route('admin.profesores.edit', $profesor->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil me-1"></i>Editar
                            </a>
                            <a href="{{ route('admin.profesores.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i>Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        {{-- Columna Izquierda: Información --}}
                        <div class="col-lg-8">
                            <h5 class="border-bottom pb-2 mb-3">Datos Personales</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted small">NOMBRES COMPLETOS</h6>
                                    <p class="mb-0"><strong>{{ $profesor->nombreCompleto }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted small">CÉDULA</h6>
                                    <p class="mb-0"><strong>{{ $profesor->cedula }}</strong></p>
                                </div>
                            </div>

                            <div class="row mb-3 border-top pt-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted small">CORREO ELECTRÓNICO</h6>
                                    <p class="mb-0"><strong>{{ $profesor->correo }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted small">CELULAR</h6>
                                    <p class="mb-0"><strong>{{ $profesor->celular }}</strong></p>
                                </div>
                            </div>

                            <h5 class="border-bottom pb-2 mb-3 mt-4">Información Académica</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted small">ESPECIALIDAD</h6>
                                    <p class="mb-0"><strong>{{ $profesor->especialidad ?? 'No especificada' }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted small">DEPARTAMENTO</h6>
                                    <p class="mb-0"><strong>{{ $profesor->departamento ?? 'No especificado' }}</strong></p>
                                </div>
                            </div>

                            <div class="row mb-3 border-top pt-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted small">FECHA DE INGRESO</h6>
                                    <p class="mb-0">
                                        <strong>
                                            @if($profesor->fecha_ingreso)
                                            {{ date('d/m/Y', strtotime($profesor->fecha_ingreso)) }}
                                            @else
                                            No especificada
                                            @endif
                                        </strong>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted small">HORARIO DE TRABAJO</h6>
                                    <p class="mb-0"><strong>{{ $profesor->horario ?? 'No especificado' }}</strong></p>
                                </div>
                            </div>

                            <h5 class="border-bottom pb-2 mb-3 mt-4">Estado y Estadísticas</h5>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <h6 class="text-muted small">ESTADO</h6>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ $profesor->estado === 'activo' ? 'success' : 'danger' }} fs-6">
                                            {{ strtoupper($profesor->estado) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted small">ACCESOS VALIDADOS</h6>
                                    <p class="mb-0"><strong class="text-primary fs-5">{{ $accesosValidados }}</strong></p>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted small">REGISTRADO DESDE</h6>
                                    <p class="mb-0"><strong>{{ $profesor->created_at->format('d/m/Y') }}</strong></p>
                                </div>
                            </div>

                            <h5 class="border-bottom pb-2 mb-3 mt-4">Acciones</h5>

                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('admin.profesores.toggle', $profesor->id) }}"
                                        method="POST"
                                        class="d-inline me-2">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-{{ $profesor->estado === 'activo' ? 'danger' : 'success' }}">
                                            <i class="bi bi-{{ $profesor->estado === 'activo' ? 'lock' : 'unlock' }} me-2"></i>
                                            {{ $profesor->estado === 'activo' ? 'Desactivar Profesor' : 'Activar Profesor' }}
                                        </button>
                                    </form>

                                    <button type="button"
                                        class="btn btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resetPasswordModal">
                                        <i class="bi bi-key me-2"></i>Resetear Contraseña
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Columna Derecha: Foto --}}
                        <div class="col-lg-4">
                            <h5 class="border-bottom pb-2 mb-3">Fotografía</h5>

                            <div class="text-center mb-3">
                                @if($profesor->foto_url && file_exists(public_path($profesor->foto_url)))
                                <img src="{{ asset($profesor->foto_url) }}"
                                    class="img-thumbnail rounded-circle"
                                    style="width: 250px; height: 250px; object-fit: cover;"
                                    alt="Foto del profesor">
                                @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 250px; height: 250px;">
                                    <i class="bi bi-person display-1"></i>
                                </div>
                                <p class="text-muted mt-2">Sin fotografía</p>
                                @endif
                            </div>

                            <div class="alert alert-info">
                                <small>
                                    <strong>Información del Sistema:</strong><br>
                                    <i class="bi bi-calendar-plus me-1"></i> Creado: {{ $profesor->created_at->format('d/m/Y H:i') }}<br>
                                    <i class="bi bi-calendar-check me-1"></i> Última actualización: {{ $profesor->updated_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para Resetear Contraseña --}}
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resetear Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas resetear la contraseña de <strong>{{ $profesor->nombreCompleto }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    La nueva contraseña será: <strong>{{ $profesor->cedula }}</strong> (su número de cédula)
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.profesores.reset-password', $profesor->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key me-2"></i>Resetear Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
