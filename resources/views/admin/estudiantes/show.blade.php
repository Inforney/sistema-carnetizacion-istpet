@extends('layouts.app')

@section('title', 'Detalles del Estudiante')

@section('content')
<div class="container py-4">


    <div class="row">
        {{-- Información del Estudiante --}}
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-person me-2"></i>Información del Estudiante
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.estudiantes.edit', $estudiante->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Editar
                        </a>
                        <form action="{{ route('admin.estudiantes.toggle', $estudiante->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-{{ $estudiante->estado === 'activo' ? 'danger' : 'success' }}">
                                <i class="bi bi-{{ $estudiante->estado === 'activo' ? 'lock' : 'unlock' }} me-2"></i>
                                {{ $estudiante->estado === 'activo' ? 'Bloquear' : 'Desbloquear' }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Foto del estudiante --}}
                    <div class="text-center mb-4 pb-3 border-bottom">
                        @if($estudiante->foto_url)
                        <img src="{{ asset($estudiante->foto_url) }}"
                            alt="Foto de {{ $estudiante->nombreCompleto }}"
                            class="img-thumbnail rounded-circle"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                        <div class="d-inline-block img-thumbnail rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <span style="font-size: 48px; color: white; font-weight: bold;">
                                {{ strtoupper(substr($estudiante->nombres, 0, 1) . substr($estudiante->apellidos, 0, 1)) }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted small">NOMBRES COMPLETOS</h6>
                            <p class="mb-0"><strong>{{ $estudiante->nombreCompleto }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">ESTADO</h6>
                            @if($estudiante->estado === 'activo')
                            <span class="badge bg-success">Activo</span>
                            @else
                            <span class="badge bg-danger">Bloqueado</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4 border-top pt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">TIPO DE DOCUMENTO</h6>
                            <p class="mb-0">{{ strtoupper($estudiante->tipo_documento ?? 'CEDULA') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">NÚMERO DE DOCUMENTO</h6>
                            <p class="mb-0"><strong>{{ $estudiante->cedula }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-4 border-top pt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">NACIONALIDAD</h6>
                            <p class="mb-0">{{ $estudiante->nacionalidad ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">CICLO/NIVEL</h6>
                            <p class="mb-0"><span class="badge bg-info">{{ $estudiante->ciclo_nivel }}</span></p>
                        </div>
                    </div>

                    <div class="row mb-4 border-top pt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">CORREO INSTITUCIONAL</h6>
                            <p class="mb-0">
                                <i class="bi bi-envelope me-2"></i>{{ $estudiante->correo_institucional }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">CELULAR</h6>
                            <p class="mb-0">
                                <i class="bi bi-phone me-2"></i>{{ $estudiante->celular }}
                            </p>
                        </div>
                    </div>

                    <div class="row border-top pt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">FECHA DE REGISTRO</h6>
                            <p class="mb-0">
                                @if($estudiante->created_at)
                                {{ $estudiante->created_at->format('d/m/Y H:i') }}
                                @else
                                N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Estadísticas de Accesos --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Estadísticas de Accesos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h2 class="text-primary">{{ $stats['total_accesos'] ?? 0 }}</h2>
                            <p class="text-muted mb-0">Total de Accesos</p>
                        </div>
                        <div class="col-md-4">
                            <h2 class="text-success">{{ $stats['accesos_mes'] ?? 0 }}</h2>
                            <p class="text-muted mb-0">Este Mes</p>
                        </div>
                        <div class="col-md-4">
                            @if(isset($stats['ultimo_acceso']) && $stats['ultimo_acceso'])
                            <h6 class="text-info">
                                @if(is_object($stats['ultimo_acceso']->fecha_entrada))
                                {{ $stats['ultimo_acceso']->fecha_entrada->format('d/m/Y') }}
                                @else
                                {{ date('d/m/Y', strtotime($stats['ultimo_acceso']->fecha_entrada)) }}
                                @endif
                            </h6>
                            <p class="text-muted mb-0">Último Acceso</p>
                            @else
                            <h6 class="text-muted">-</h6>
                            <p class="text-muted mb-0">Sin Accesos</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Carnet --}}
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-credit-card me-2"></i>Carnet
                    </h5>
                </div>
                <div class="card-body">
                    @if($estudiante->carnet)
                    <div class="text-center">
                        <div class="mb-3 p-3 bg-light rounded">
                            <div class="border border-2 border-dark d-inline-block p-3 bg-white">
                                <i class="bi bi-qr-code display-1"></i>
                            </div>
                        </div>
                        <p class="mb-2"><strong>Código QR</strong></p>
                        <p class="text-muted small">{{ $estudiante->carnet->codigo_qr }}</p>

                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Estado:</span>
                                <span class="badge bg-{{ $estudiante->carnet->estado === 'activo' ? 'success' : 'danger' }}">
                                    {{ ucfirst($estudiante->carnet->estado) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Emisión:</span>
                                <span>
                                    @if(is_object($estudiante->carnet->fecha_emision))
                                    {{ $estudiante->carnet->fecha_emision->format('d/m/Y') }}
                                    @else
                                    {{ date('d/m/Y', strtotime($estudiante->carnet->fecha_emision)) }}
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Vencimiento:</span>
                                <span>
                                    @if($estudiante->carnet->fecha_vencimiento)
                                    @if(is_object($estudiante->carnet->fecha_vencimiento))
                                    {{ $estudiante->carnet->fecha_vencimiento->format('d/m/Y') }}
                                    @else
                                    {{ date('d/m/Y', strtotime($estudiante->carnet->fecha_vencimiento)) }}
                                    @endif
                                    @else
                                    N/A
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('admin.carnets.show', $estudiante->carnet->id) }}" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-eye me-2"></i>Ver Carnet Completo
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-circle display-3 text-warning"></i>
                        <p class="text-muted mt-3">Este estudiante no tiene carnet asignado</p>
                        <a href="{{ route('admin.carnets.create') }}?estudiante={{ $estudiante->id }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Generar Carnet
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Acciones --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-gear me-2"></i>Acciones Administrativas
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.estudiantes.reset-password', $estudiante->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100" onclick="return confirm('¿Resetear la contraseña de este estudiante?\n\nSe generará una contraseña temporal.')">
                            <i class="bi bi-key me-2"></i>Resetear Contraseña
                        </button>
                    </form>

                    <hr>

                    <form action="{{ route('admin.estudiantes.destroy', $estudiante->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('⚠️ ATENCIÓN\n\n¿Estás seguro de eliminar este estudiante?\n\nEsta acción eliminará:\n• El estudiante\n• Su carnet\n• Todo su historial de accesos\n\nEsta acción NO se puede deshacer.')">
                            <i class="bi bi-trash me-2"></i>Eliminar Estudiante
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection