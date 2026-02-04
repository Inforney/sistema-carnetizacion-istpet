@extends('layouts.app')

@section('title', 'Dashboard Estudiante')

@section('content')
<div class="container py-4">
    {{-- Saludo --}}
    <div class="mb-4">
        <h2>¡Bienvenido, {{ Auth::user()->nombres }}! </h2>
        <p class="text-muted">Panel de control del estudiante</p>
    </div>

    {{-- Alerta de Acceso Activo --}}
    @php
    $accesoActivo = \App\Models\Acceso::where('usuario_id', Auth::user()->id)
    ->whereNull('hora_salida')
    ->where('marcado_ausente', false)
    ->whereDate('fecha_entrada', today())
    ->with('laboratorio')
    ->first();
    @endphp

    @if($accesoActivo)
    <div class="alert alert-warning mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="alert-heading mb-2">
                    <i class="bi bi-clock-fill me-2"></i>Tienes un acceso activo
                </h5>
                <p class="mb-0">
                    <strong>Laboratorio:</strong> {{ $accesoActivo->laboratorio->nombre }}<br>
                    <strong>Entrada:</strong> {{ date('H:i', strtotime($accesoActivo->hora_entrada)) }}
                </p>
            </div>
            <a href="{{ route('estudiante.acceso.escanear') }}" class="btn btn-warning">
                <i class="bi bi-qr-code-scan me-2"></i>Registrar Salida
            </a>
        </div>
    </div>
    @endif

    {{-- Botón Principal: Registrar Acceso --}}
    <div class="card mb-4" style="background: linear-gradient(135deg, #1a2342 0%, #222C57 100%);">
        <div class="card-body text-white text-center py-5">
            <i class="bi bi-qr-code-scan display-1 mb-3" style="color: #C4A857;"></i>
            <h3 class="mb-3">Registrar Acceso al Laboratorio</h3>
            <p class="mb-4">Escanea el código QR del laboratorio para registrar tu entrada o salida</p>
            <a href="{{ route('estudiante.acceso.escanear') }}" class="btn btn-lg text-dark fw-bold" style="background: #C4A857;">
                <i class="bi bi-camera-fill me-2"></i>Escanear Código QR
            </a>
        </div>
    </div>

    {{-- Cards de Accesos Rápidos --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-credit-card display-4 text-primary mb-3"></i>
                    <h5>Mi Carnet Digital</h5>
                    <p class="text-muted small">Visualiza y descarga tu carnet estudiantil</p>
                    <a href="{{ route('estudiante.carnet.show') }}" class="btn btn-outline-primary btn-sm">
                        Ver Carnet
                    </a>
                    <a href="{{ route('estudiante.cambiar-password.form') }}" class="btn btn-warning">
                        <i class="bi bi-shield-lock me-2"></i>Cambiar Contraseña
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history display-4 text-success mb-3"></i>
                    <h5>Mi Historial</h5>
                    <p class="text-muted small">Revisa tus accesos a los laboratorios</p>
                    <a href="{{ route('estudiante.acceso.historial') }}" class="btn btn-outline-success btn-sm">
                        Ver Historial
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Información del Estudiante --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #C4A857;">
                    <h5 class="mb-0 text-dark">
                        <i class="bi bi-person me-2"></i>Mi Información
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Foto del estudiante --}}
                    <div class="text-center mb-3 pb-3 border-bottom">
                        @if(Auth::user()->foto_url)
                        <img src="{{ asset(Auth::user()->foto_url) }}"
                            alt="Mi foto"
                            class="img-thumbnail rounded-circle"
                            style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                        <div class="d-inline-block img-thumbnail rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <span style="font-size: 40px; color: white; font-weight: bold;">
                                {{ strtoupper(substr(Auth::user()->nombres, 0, 1) . substr(Auth::user()->apellidos, 0, 1)) }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted" width="40%">Nombre Completo:</td>
                            <td><strong>{{ Auth::user()->nombreCompleto }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Cédula:</td>
                            <td><strong>{{ Auth::user()->cedula }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Carrera:</td>
                            <td>{{ Auth::user()->carrera ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ciclo:</td>
                            <td>{{ Auth::user()->ciclo_nivel ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Correo:</td>
                            <td>{{ Auth::user()->correo_institucional }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightbulb me-2"></i>Información Importante
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">Escanea el QR del laboratorio al <strong>entrar y salir</strong></li>
                        <li class="mb-2">Si no tienes celular, el profesor puede registrarte manualmente</li>
                        <li class="mb-2">Tu carnet digital es válido por <strong>1 año (2 periodos académicos)</strong></li>
                        <li class="mb-2">Mantén tu contraseña segura</li>
                        <li class="mb-0">Revisa tu historial regularmente</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection