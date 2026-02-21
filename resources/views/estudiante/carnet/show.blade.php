@extends('layouts.app')

@section('title', 'Mi Carnet Digital')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">
        <i class="bi bi-credit-card me-2"></i>Mi Carnet Digital
    </h2>
    <p class="text-muted mb-4">Carnet estudiantil ISTPET</p>

    @if($carnet)
    <div class="row justify-content-center">
        <div class="col-lg-5 mb-4">
            {{-- Carnet Digital --}}
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #1a2342 0%, #222C57 100%); border-radius: 20px; overflow: hidden;">
                {{-- Estado Badge --}}
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-{{ $carnet->estado === 'activo' ? 'success' : 'danger' }} fs-6">
                        {{ strtoupper($carnet->estado) }}
                    </span>
                </div>

                {{-- Header --}}
                <div class="text-center py-3" style="background: #C4A857;">
                    <h4 class="mb-0 fw-bold" style="color: #1a2342;">ISTPET</h4>
                    <p class="mb-0 small" style="color: #1a2342;">Instituto Superior Tecnológico</p>
                </div>

                {{-- Foto --}}
                <div class="text-center py-4">
                    @if(Auth::user()->foto_url)
                    <img src="{{ asset(Auth::user()->foto_url) }}"
                        class="rounded-circle border border-4 border-warning"
                        style="width: 150px; height: 150px; object-fit: cover;"
                        alt="Foto">
                    @else
                    <div class="rounded-circle border border-4 border-secondary mx-auto d-flex align-items-center justify-content-center"
                        style="width: 150px; height: 150px; background: white;">
                        <i class="bi bi-person display-3 text-secondary"></i>
                    </div>
                    @endif
                </div>

                {{-- Información del Estudiante --}}
                <div class="px-4 pb-3">
                    <div class="bg-white bg-opacity-10 text-white p-3 rounded mb-3">
                        <div class="mb-2">
                            <small class="d-block opacity-75">ESTUDIANTE</small>
                            <strong class="fs-5">{{ Auth::user()->nombreCompleto }}</strong>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6">
                                <small class="d-block opacity-75">CÉDULA</small>
                                <strong>{{ Auth::user()->cedula }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="d-block opacity-75">TIPO USUARIO</small>
                                <strong>{{ ucfirst(Auth::user()->tipo_usuario) }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Nueva sección: Carrera y Ciclo --}}
                    <div class="bg-white bg-opacity-10 text-white p-3 rounded mb-3">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <small class="d-block opacity-75">CARRERA</small>
                                <strong>{{ Auth::user()->carrera ?? 'No especificada' }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="d-block opacity-75">CICLO/NIVEL</small>
                                <strong>{{ Auth::user()->ciclo_nivel }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="d-block opacity-75">ESTADO</small>
                                <strong>{{ ucfirst($carnet->estado) }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Código QR --}}
                    <div class="bg-white p-3 rounded text-center mb-3">
                        <div class="mb-2">
                            {!! QrCode::size(200)->generate($carnet->codigo_qr) !!}
                        </div>
                        <p class="text-muted small mb-0">Código: {{ $carnet->codigo_qr }}</p>
                    </div>

                    {{-- Fechas --}}
                    <div class="text-white text-center small">
                        <p class="mb-1">
                            <strong>Emitido:</strong>
                            @if(is_object($carnet->fecha_emision))
                            {{ $carnet->fecha_emision->format('d/m/Y') }}
                            @else
                            {{ date('d/m/Y', strtotime($carnet->fecha_emision)) }}
                            @endif
                        </p>
                        <p class="mb-0">
                            <strong>Vencimiento:</strong>
                            @if($carnet->fecha_vencimiento)
                            @if(is_object($carnet->fecha_vencimiento))
                            {{ $carnet->fecha_vencimiento->format('d/m/Y') }}
                            @else
                            {{ date('d/m/Y', strtotime($carnet->fecha_vencimiento)) }}
                            @endif
                            @else
                            N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Botones de acción --}}
            <div class="d-grid gap-2 mt-3">
                <a href="{{ route('estudiante.carnet.descargar') }}"
                    class="btn btn-primary btn-lg">
                    <i class="bi bi-download me-2"></i>Descargar PDF
                </a>
                <a href="{{ route('estudiante.carnet.visualizar') }}"
                    class="btn btn-outline-primary btn-lg"
                    target="_blank">
                    <i class="bi bi-eye me-2"></i>Ver en Navegador
                </a>
            </div>
        </div>

        {{-- Información importante --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Información Importante
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Presenta tu código QR</strong> al ingresar a los laboratorios.
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-shield-check text-success me-2"></i>
                            <strong>No compartas tu carnet</strong> con otras personas. Es personal e intransferible.
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-phone text-success me-2"></i>
                            <strong>Guarda una copia digital</strong> de tu carnet en tu celular para acceso rápido.
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-calendar-check text-success me-2"></i>
                            <strong>Reporta inmediatamente</strong> si tu carnet está bloqueado. Contacta con administración.
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-clock-history text-success me-2"></i>
                            <strong>Tu carnet vence el</strong>
                            @if($carnet->fecha_vencimiento)
                            @if(is_object($carnet->fecha_vencimiento))
                            {{ $carnet->fecha_vencimiento->format('d/m/Y') }}
                            @else
                            {{ date('d/m/Y', strtotime($carnet->fecha_vencimiento)) }}
                            @endif
                            @else
                            N/A
                            @endif
                            . Deberás renovarlo antes de esa fecha.
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Mis Datos --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>Mis Datos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">NOMBRES COMPLETOS</h6>
                            <p class="mb-0"><strong>{{ Auth::user()->nombreCompleto }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">NACIONALIDAD</h6>
                            <p class="mb-0"><strong>{{ Auth::user()->nacionalidad ?? 'N/A' }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3 border-top pt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">TIPO DE DOCUMENTO</h6>
                            <p class="mb-0"><strong>{{ strtoupper(Auth::user()->tipo_documento ?? 'CEDULA') }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">NÚMERO DE DOCUMENTO</h6>
                            <p class="mb-0"><strong>{{ Auth::user()->cedula }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3 border-top pt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">CARRERA</h6>
                            <p class="mb-0"><strong>{{ Auth::user()->carrera ?? 'No especificada' }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">CICLO/NIVEL</h6>
                            <p class="mb-0"><strong>{{ Auth::user()->ciclo_nivel }}</strong></p>
                        </div>
                    </div>

                    <div class="row border-top pt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">CORREO INSTITUCIONAL</h6>
                            <p class="mb-0"><strong>{{ Auth::user()->correo_institucional }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">CELULAR</h6>
                            <p class="mb-0"><strong>{{ Auth::user()->celular }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>No tienes un carnet asignado.</strong> Por favor contacta con el área de administración del instituto.
    </div>
    @endif
</div>
@endsection