@extends('layouts.app')

@section('title', 'Detalle del Carnet')

@section('content')
<div class="container py-4">


    <div class="row">
        {{-- Carnet Digital --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">
                    <h4 class="mb-0">Carnet Digital ISTPET</h4>
                </div>
                <div class="card-body p-4">
                    {{-- Estado --}}
                    <div class="text-center mb-3">
                        <span class="badge bg-{{ $carnet->estado === 'activo' ? 'success' : 'danger' }} fs-6">
                            {{ strtoupper($carnet->estado) }}
                        </span>
                    </div>

                    {{-- Foto --}}
                    <div class="text-center mb-3">
                        @if($carnet->usuario->foto_url)
                        <img src="{{ asset('storage/' . $carnet->usuario->foto_url) }}"
                            class="rounded-circle border border-3 border-primary"
                            style="width: 150px; height: 150px; object-fit: cover;"
                            alt="Foto">
                        @else
                        <div class="rounded-circle border border-3 border-secondary mx-auto d-flex align-items-center justify-content-center bg-light"
                            style="width: 150px; height: 150px;">
                            <i class="bi bi-person display-3 text-secondary"></i>
                        </div>
                        @endif
                    </div>

                    {{-- Información --}}
                    <div class="bg-light p-3 rounded mb-3">
                        <h5 class="text-center mb-3">{{ $carnet->usuario->nombreCompleto }}</h5>

                        <div class="mb-2">
                            <small class="text-muted d-block">CÉDULA</small>
                            <strong>{{ $carnet->usuario->cedula }}</strong>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">CARRERA</small>
                            <strong>{{ $carnet->usuario->carrera ?? 'No especificada' }}</strong>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">CICLO/NIVEL</small>
                            <strong>{{ $carnet->usuario->ciclo_nivel }}</strong>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">CORREO INSTITUCIONAL</small>
                            <strong style="font-size: 0.85em;">{{ $carnet->usuario->correo_institucional }}</strong>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">TIPO USUARIO</small>
                            <strong>{{ ucfirst($carnet->usuario->tipo_usuario) }}</strong>
                        </div>
                    </div>

                    {{-- Código QR --}}
                    <div class="text-center mb-3 p-3 bg-white border rounded">
                        <h6 class="mb-3">Código QR</h6>
                        <div class="d-inline-block p-3 bg-light rounded">
                            <i class="bi bi-qr-code display-1"></i>
                        </div>
                        <p class="text-muted small mt-2 mb-0">{{ $carnet->codigo_qr }}</p>
                    </div>

                    {{-- Fechas --}}
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted d-block">EMITIDO</small>
                            <strong>
                                @if(is_object($carnet->fecha_emision))
                                {{ $carnet->fecha_emision->format('d/m/Y') }}
                                @else
                                {{ date('d/m/Y', strtotime($carnet->fecha_emision)) }}
                                @endif
                            </strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">VÁLIDO HASTA</small>
                            <strong>
                                @if($carnet->fecha_vencimiento)
                                @if(is_object($carnet->fecha_vencimiento))
                                {{ $carnet->fecha_vencimiento->format('d/m/Y') }}
                                @else
                                {{ date('d/m/Y', strtotime($carnet->fecha_vencimiento)) }}
                                @endif
                                @else
                                N/A
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acciones y Detalles --}}
        <div class="col-lg-7">
            {{-- Acciones --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-gear me-2"></i>Acciones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('admin.carnets.descargar', $carnet->id) }}"
                                class="btn btn-primary w-100">
                                <i class="bi bi-download me-2"></i>Descargar PDF
                            </a>
                        </div>

                        <div class="col-md-6">
                            <form action="{{ route('admin.carnets.toggle', $carnet->id) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn btn-{{ $carnet->estado === 'activo' ? 'danger' : 'success' }} w-100">
                                    <i class="bi bi-{{ $carnet->estado === 'activo' ? 'lock' : 'unlock' }} me-2"></i>
                                    {{ $carnet->estado === 'activo' ? 'Bloquear' : 'Activar' }} Carnet
                                </button>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <form action="{{ route('admin.carnets.renovar', $carnet->id) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn btn-warning w-100"
                                    onclick="return confirm('¿Renovar este carnet por 4 años más?')">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Renovar Carnet
                                </button>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ route('admin.estudiantes.show', $carnet->usuario->id) }}"
                                class="btn btn-info w-100">
                                <i class="bi bi-person me-2"></i>Ver Estudiante
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información Adicional --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Información Adicional
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">ID DEL CARNET</h6>
                            <p class="mb-0"><strong>#{{ $carnet->id }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">NACIONALIDAD</h6>
                            <p class="mb-0"><strong>{{ $carnet->usuario->nacionalidad ?? 'N/A' }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3 border-top pt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">CELULAR</h6>
                            <p class="mb-0"><strong>{{ $carnet->usuario->celular }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">ESTADO DEL USUARIO</h6>
                            <p class="mb-0">
                                <span class="badge bg-{{ $carnet->usuario->estado === 'activo' ? 'success' : 'danger' }}">
                                    {{ strtoupper($carnet->usuario->estado) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row border-top pt-3">
                        <div class="col-12">
                            <h6 class="text-muted small">CÓDIGO QR COMPLETO</h6>
                            <p class="mb-0">
                                <code>{{ $carnet->codigo_qr }}</code>
                            </p>
                            <small class="text-muted">Este código es único y permanente para el estudiante durante todo su período académico.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection