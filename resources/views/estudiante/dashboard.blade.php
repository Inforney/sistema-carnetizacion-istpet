@extends('layouts.app')

@section('title', 'Dashboard Estudiante')

@section('content')
<div class="container py-4">

    {{-- Encabezado con separador de marca --}}
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h2 class="mb-1" style="font-family:'Oswald',sans-serif; color:var(--istpet-azul); font-size:1.6rem;">
                    <i class="bi bi-mortarboard me-2" style="color:var(--istpet-dorado);"></i>
                    Bienvenido, {{ Auth::user()->nombres }}
                </h2>
                <p class="text-muted mb-0" style="font-size:0.88rem;">Panel de control del estudiante &mdash; {{ date('l, d \d\e F \d\e Y') }}</p>
            </div>
            <span class="badge px-3 py-2" style="background:var(--istpet-azul); font-family:'Oswald',sans-serif; font-size:0.8rem; letter-spacing:0.5px;">
                <i class="bi bi-person-badge me-1" style="color:var(--istpet-dorado);"></i>ESTUDIANTE
            </span>
        </div>
        {{-- Separador de marca --}}
        <div class="mt-3" style="height:3px; background:linear-gradient(90deg, var(--istpet-dorado) 0%, var(--istpet-azul) 60%, transparent 100%); border-radius:2px;"></div>
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
    <div class="alert mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2"
        style="background:rgba(196,168,87,0.1); border:1px solid var(--istpet-dorado); border-left:4px solid var(--istpet-dorado); border-radius:10px;">
        <div>
            <h6 class="mb-1" style="color:var(--istpet-azul); font-family:'Oswald',sans-serif;">
                <i class="bi bi-clock-fill me-2" style="color:var(--istpet-dorado);"></i>Tienes un acceso activo
            </h6>
            <p class="mb-0 text-muted small">
                <strong>Laboratorio:</strong> {{ $accesoActivo->laboratorio->nombre }} &nbsp;|&nbsp;
                <strong>Entrada:</strong> {{ date('H:i', strtotime($accesoActivo->hora_entrada)) }}
            </p>
        </div>
        <a href="{{ route('estudiante.acceso.escanear') }}" class="btn btn-sm px-4" style="background:var(--istpet-dorado);color:var(--istpet-azul);font-weight:700;border:none;">
            <i class="bi bi-qr-code-scan me-1"></i>Registrar Salida
        </a>
    </div>
    @endif

    {{-- Botón Principal: Registrar Acceso --}}
    <div class="card mb-4" style="background:linear-gradient(135deg, var(--istpet-azul) 0%, #1a2342 100%); border:none; border-radius:14px; overflow:hidden;">
        <div class="card-body text-white text-center py-5 position-relative">
            <div class="position-absolute" style="top:0;right:0;width:120px;height:120px;background:rgba(196,168,87,0.06);border-radius:0 14px 0 100%;"></div>
            <div class="position-absolute" style="bottom:0;left:0;width:80px;height:80px;background:rgba(196,168,87,0.04);border-radius:0 100% 0 14px;"></div>
            <i class="bi bi-qr-code-scan mb-3 d-block" style="font-size:3.5rem; color:var(--istpet-dorado);"></i>
            <h3 class="mb-2" style="font-family:'Oswald',sans-serif; font-size:1.5rem;">Registrar Acceso al Laboratorio</h3>
            <p class="mb-4 opacity-75" style="font-size:0.9rem;">Escanea el código QR del laboratorio para registrar tu entrada o salida</p>
            <a href="{{ route('estudiante.acceso.escanear') }}" class="btn btn-lg px-5 fw-bold" style="background:var(--istpet-dorado); color:var(--istpet-azul); border:none; border-radius:9px; font-family:'Oswald',sans-serif; letter-spacing:0.5px;">
                <i class="bi bi-camera-fill me-2"></i>Escanear Código QR
            </a>
        </div>
    </div>

    {{-- Cards de Accesos Rápidos --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card h-100 shadow-sm" style="border-top:3px solid var(--istpet-azul); border-radius:10px; transition:all 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(34,44,87,0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                <div class="card-body text-center py-4">
                    <div class="mb-3" style="width:56px;height:56px;border-radius:12px;background:rgba(34,44,87,0.08);display:inline-flex;align-items:center;justify-content:center;">
                        <i class="bi bi-credit-card" style="font-size:1.6rem;color:var(--istpet-azul);"></i>
                    </div>
                    <h5 style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">Mi Carnet Digital</h5>
                    <p class="text-muted small mb-3">Visualiza y descarga tu carnet estudiantil</p>
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <a href="{{ route('estudiante.carnet.show') }}" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-credit-card me-1"></i>Ver Carnet
                        </a>
                        <a href="{{ route('estudiante.cambiar-password.form') }}" class="btn btn-sm px-3" style="background:rgba(34,44,87,0.08);color:var(--istpet-azul);border:1px solid rgba(34,44,87,0.2);">
                            <i class="bi bi-shield-lock me-1"></i>Cambiar Contraseña
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 shadow-sm" style="border-top:3px solid var(--istpet-dorado); border-radius:10px; transition:all 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(196,168,87,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                <div class="card-body text-center py-4">
                    <div class="mb-3" style="width:56px;height:56px;border-radius:12px;background:rgba(196,168,87,0.1);display:inline-flex;align-items:center;justify-content:center;">
                        <i class="bi bi-clock-history" style="font-size:1.6rem;color:var(--istpet-dorado);"></i>
                    </div>
                    <h5 style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">Mi Historial</h5>
                    <p class="text-muted small mb-3">Revisa tus accesos a los laboratorios</p>
                    <a href="{{ route('estudiante.acceso.historial') }}" class="btn btn-sm px-4" style="background:var(--istpet-dorado);color:var(--istpet-azul);font-weight:700;border:none;">
                        <i class="bi bi-clock-history me-1"></i>Ver Historial
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Información del Estudiante --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-istpet">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-person me-2" style="color:var(--istpet-dorado);"></i>Mi Información
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Foto del estudiante --}}
                    <div class="text-center mb-3 pb-3" style="border-bottom:2px solid rgba(196,168,87,0.2);">
                        @if(Auth::user()->foto_url)
                        <img src="{{ asset(Auth::user()->foto_url) }}"
                            alt="Mi foto"
                            class="rounded-circle"
                            style="width:100px; height:100px; object-fit:cover; border:3px solid var(--istpet-dorado); box-shadow:0 4px 12px rgba(196,168,87,0.25);">
                        @else
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width:100px; height:100px; background:var(--istpet-azul); border:3px solid var(--istpet-dorado); box-shadow:0 4px 12px rgba(34,44,87,0.2);">
                            <span style="font-family:'Oswald',sans-serif; font-size:2rem; color:var(--istpet-dorado); font-weight:700;">
                                {{ strtoupper(substr(Auth::user()->nombres, 0, 1) . substr(Auth::user()->apellidos, 0, 1)) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted" width="40%"><i class="bi bi-person me-1"></i>Nombre:</td>
                            <td><strong>{{ Auth::user()->nombreCompleto }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-card-text me-1"></i>Cédula:</td>
                            <td><strong>{{ Auth::user()->cedula }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-book me-1"></i>Carrera:</td>
                            <td>{{ Auth::user()->carrera ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-layers me-1"></i>Ciclo:</td>
                            <td>{{ Auth::user()->ciclo_nivel ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-envelope me-1"></i>Correo:</td>
                            <td>{{ Auth::user()->correo_institucional }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header" style="background:white; border-bottom:2px solid var(--istpet-dorado);">
                    <h5 class="mb-0" style="color:var(--istpet-azul);">
                        <i class="bi bi-info-circle me-2" style="color:var(--istpet-dorado);"></i>Información Importante
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0" style="padding-left:1.2rem;">
                        <li class="mb-2" style="color:#444;">Escanea el QR del laboratorio al <strong>entrar y salir</strong></li>
                        <li class="mb-2" style="color:#444;">Si no tienes celular, el profesor puede registrarte manualmente</li>
                        <li class="mb-2" style="color:#444;">Tu carnet digital es válido por <strong>1 año</strong></li>
                        <li class="mb-2" style="color:#444;">Mantén tu contraseña segura y no la compartas</li>
                        <li style="color:#444;">Revisa tu historial regularmente</li>
                    </ul>
                    <div class="mt-3 p-2 rounded" style="background:rgba(34,44,87,0.05);border-left:3px solid var(--istpet-dorado);">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1" style="color:var(--istpet-dorado);"></i>
                            Instituto Superior Tecnológico Mayor Pedro Traversari
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
