@extends('layouts.app')

@section('title', 'Mi Carnet Digital')

@section('content')
<div class="container-fluid py-4">

    {{-- ── PAGE HEADER ── --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <div>
            <h2 class="mb-1" style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">
                <i class="bi bi-credit-card-2-front me-2" style="color:var(--istpet-dorado);"></i>Mi Carnet Digital
            </h2>
            <p class="text-muted mb-0" style="font-size:0.88rem;">Carnet estudiantil ISTPET · Período académico {{ date('Y') }}</p>
        </div>
        @if($carnet)
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('estudiante.carnet.descargar') }}"
               class="btn btn-sm"
               style="background:var(--istpet-azul);color:#fff;">
                <i class="bi bi-file-earmark-pdf me-1"></i>Descargar PDF
            </a>
            <a href="{{ route('estudiante.carnet.visualizar') }}"
               class="btn btn-sm"
               style="background:var(--istpet-dorado);color:var(--istpet-azul);font-weight:700;"
               target="_blank">
                <i class="bi bi-eye me-1"></i>Ver en Navegador
            </a>
        </div>
        @endif
    </div>
    <div class="mb-4" style="height:3px;background:linear-gradient(90deg,var(--istpet-dorado) 0%,var(--istpet-azul) 60%,transparent 100%);border-radius:2px;"></div>

    @if($carnet)
    <div class="row g-4">

        {{-- ── COLUMNA IZQUIERDA: Vista previa del carnet ── --}}
        <div class="col-lg-4">
            <div class="card" style="border:none;box-shadow:0 4px 20px rgba(0,0,0,0.12);">
                <div class="card-header" style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
                    <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-eye me-2" style="color:var(--istpet-dorado);"></i>Vista Previa del Carnet
                    </h6>
                </div>
                <div class="card-body p-4" style="background:#f4f5f8;">
                    {{-- ── CARNET PREVIEW HTML ── --}}
                    <div style="max-width:280px;margin:0 auto;border-radius:10px;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,0.35);">

                        {{-- Header Navy --}}
                        <div style="background:#222C57;padding:12px 14px 0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:30px;height:30px;background:#fff;border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <img src="{{ asset('images/LogoISTPET.png') }}" alt="ISTPET"
                                             style="width:24px;height:24px;object-fit:contain;">
                                    </div>
                                    <div>
                                        <div style="color:#C4A857;font-family:'Oswald',sans-serif;font-weight:700;font-size:1rem;letter-spacing:2px;line-height:1;">ISTPET</div>
                                        <div style="color:rgba(255,255,255,0.65);font-size:0.6rem;line-height:1.2;margin-top:1px;">Inst. Superior Tecnológico<br>Mayor Pedro Traversari</div>
                                    </div>
                                </div>
                                @if($carnet->estado === 'activo')
                                <span class="badge" style="background:#28a745;font-size:0.6rem;padding:3px 8px;">ACTIVO</span>
                                @else
                                <span class="badge bg-danger" style="font-size:0.6rem;padding:3px 8px;">{{ strtoupper($carnet->estado) }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Barra dorada --}}
                        <div style="height:3px;background:#C4A857;"></div>

                        {{-- Área foto --}}
                        <div style="background:#1a2342;padding:18px 14px 12px;text-align:center;">
                            @if(Auth::user()->foto_url)
                            <img src="{{ asset(Auth::user()->foto_url) }}"
                                 style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #C4A857;display:inline-block;"
                                 alt="Foto">
                            @else
                            <div style="width:80px;height:80px;border-radius:50%;background:#2d3d72;border:3px solid #C4A857;display:inline-flex;align-items:center;justify-content:center;margin:0 auto;">
                                <img src="{{ asset('images/LogoISTPET.png') }}" style="width:44px;height:44px;object-fit:contain;opacity:0.55;" alt="">
                            </div>
                            @endif
                            <br>
                            <span style="display:inline-block;background:#C4A857;color:#222C57;font-size:0.6rem;font-weight:700;padding:2px 12px;border-radius:8px;text-transform:uppercase;letter-spacing:1px;margin-top:8px;">Estudiante</span>
                        </div>

                        {{-- Info blanca --}}
                        <div style="background:#fff;padding:12px 14px 10px;border-left:4px solid #C4A857;">
                            <div style="font-size:0.85rem;font-weight:700;color:#222C57;border-bottom:1px solid rgba(196,168,87,0.35);padding-bottom:5px;margin-bottom:8px;line-height:1.2;">
                                {{ Auth::user()->nombreCompleto }}
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:5px 8px;">
                                <div>
                                    <div style="font-size:0.55rem;color:#C4A857;text-transform:uppercase;font-weight:700;letter-spacing:0.5px;">{{ Auth::user()->tipo_documento === 'pasaporte' ? 'Pasaporte' : 'Cédula' }}</div>
                                    <div style="font-size:0.75rem;color:#222C57;font-weight:700;">{{ Auth::user()->cedula }}</div>
                                </div>
                                <div>
                                    <div style="font-size:0.55rem;color:#C4A857;text-transform:uppercase;font-weight:700;letter-spacing:0.5px;">Ciclo / Nivel</div>
                                    <div style="font-size:0.72rem;color:#222C57;font-weight:700;">{{ Auth::user()->ciclo_nivel }}</div>
                                </div>
                                <div style="grid-column:1/-1;">
                                    <div style="font-size:0.55rem;color:#C4A857;text-transform:uppercase;font-weight:700;letter-spacing:0.5px;">Carrera</div>
                                    <div style="font-size:0.75rem;color:#222C57;font-weight:700;">{{ Auth::user()->carrera ?? 'N/A' }}</div>
                                </div>
                                <div style="grid-column:1/-1;">
                                    <div style="font-size:0.55rem;color:#C4A857;text-transform:uppercase;font-weight:700;letter-spacing:0.5px;">Correo</div>
                                    <div style="font-size:0.65rem;color:#222C57;font-weight:700;">{{ Auth::user()->correo_institucional }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Barra dorada --}}
                        <div style="height:3px;background:#C4A857;"></div>

                        {{-- QR Footer --}}
                        <div style="background:#222C57;padding:10px 12px;">
                            <div class="d-flex align-items-center gap-2">
                                <div style="background:#fff;padding:5px;border-radius:4px;flex-shrink:0;">
                                    {!! QrCode::size(60)->generate($carnet->codigo_qr) !!}
                                </div>
                                <div>
                                    <div style="font-size:0.55rem;color:rgba(196,168,87,0.75);text-transform:uppercase;letter-spacing:0.5px;">Emisión</div>
                                    <div style="font-size:0.72rem;color:#fff;font-weight:700;margin-bottom:4px;">
                                        @if(is_object($carnet->fecha_emision))
                                        {{ $carnet->fecha_emision->format('d/m/Y') }}
                                        @else
                                        {{ date('d/m/Y', strtotime($carnet->fecha_emision)) }}
                                        @endif
                                    </div>
                                    <div style="font-size:0.55rem;color:rgba(196,168,87,0.75);text-transform:uppercase;letter-spacing:0.5px;">Válido hasta</div>
                                    <div style="font-size:0.72rem;color:#C4A857;font-weight:700;">
                                        @if($carnet->fecha_vencimiento)
                                            @if(is_object($carnet->fecha_vencimiento))
                                            {{ $carnet->fecha_vencimiento->format('d/m/Y') }}
                                            @else
                                            {{ date('d/m/Y', strtotime($carnet->fecha_vencimiento)) }}
                                            @endif
                                        @else
                                        N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div style="font-size:0.5rem;color:rgba(255,255,255,0.22);margin-top:6px;border-top:1px solid rgba(196,168,87,0.12);padding-top:4px;font-style:italic;text-align:center;word-break:break-all;">
                                {{ $carnet->codigo_qr }}
                            </div>
                        </div>

                    </div>{{-- /carnet-preview --}}

                    {{-- Botones --}}
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('estudiante.carnet.descargar') }}"
                           class="btn py-2"
                           style="background:var(--istpet-azul);color:#fff;font-family:'Oswald',sans-serif;letter-spacing:0.5px;">
                            <i class="bi bi-file-earmark-pdf me-2" style="color:var(--istpet-dorado);"></i>Descargar PDF
                        </a>
                        <a href="{{ route('estudiante.carnet.visualizar') }}"
                           class="btn py-2"
                           style="background:var(--istpet-dorado);color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:700;letter-spacing:0.5px;"
                           target="_blank">
                            <i class="bi bi-eye me-2"></i>Ver en Navegador (PDF)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── COLUMNA DERECHA: Info importante + Mis datos ── --}}
        <div class="col-lg-8">

            {{-- INFORMACIÓN IMPORTANTE --}}
            <div class="card mb-4" style="border:none;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
                <div class="card-header" style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
                    <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-info-circle me-2" style="color:var(--istpet-dorado);"></i>Información Importante
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background:rgba(34,44,87,0.04);border-left:3px solid var(--istpet-dorado);">
                                <i class="bi bi-qr-code-scan mb-2 d-block" style="color:var(--istpet-azul);font-size:1.3rem;"></i>
                                <strong style="color:var(--istpet-azul);font-size:0.88rem;">Presenta tu código QR</strong>
                                <p class="text-muted mb-0 mt-1" style="font-size:0.8rem;">Al ingresar a los laboratorios muestra el QR para validar tu acceso.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background:rgba(34,44,87,0.04);border-left:3px solid var(--istpet-dorado);">
                                <i class="bi bi-shield-lock mb-2 d-block" style="color:var(--istpet-azul);font-size:1.3rem;"></i>
                                <strong style="color:var(--istpet-azul);font-size:0.88rem;">Personal e intransferible</strong>
                                <p class="text-muted mb-0 mt-1" style="font-size:0.8rem;">No compartas tu carnet. Es único para tu identidad estudiantil.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background:rgba(34,44,87,0.04);border-left:3px solid var(--istpet-dorado);">
                                <i class="bi bi-phone mb-2 d-block" style="color:var(--istpet-azul);font-size:1.3rem;"></i>
                                <strong style="color:var(--istpet-azul);font-size:0.88rem;">Guarda una copia digital</strong>
                                <p class="text-muted mb-0 mt-1" style="font-size:0.8rem;">Descarga el PDF y guárdalo en tu celular para acceso rápido.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background:rgba(196,168,87,0.08);border-left:3px solid #dc3545;">
                                <i class="bi bi-clock-history mb-2 d-block" style="color:#dc3545;font-size:1.3rem;"></i>
                                <strong style="color:var(--istpet-azul);font-size:0.88rem;">Vencimiento del carnet</strong>
                                <p class="text-muted mb-0 mt-1" style="font-size:0.8rem;">
                                    Tu carnet vence el
                                    <strong style="color:#dc3545;">
                                        @if($carnet->fecha_vencimiento)
                                            @if(is_object($carnet->fecha_vencimiento))
                                            {{ $carnet->fecha_vencimiento->format('d/m/Y') }}
                                            @else
                                            {{ date('d/m/Y', strtotime($carnet->fecha_vencimiento)) }}
                                            @endif
                                        @else
                                        N/A
                                        @endif
                                    </strong>. Renuévalo antes de esa fecha.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MIS DATOS --}}
            <div class="card" style="border:none;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
                <div class="card-header" style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
                    <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-person-vcard me-2" style="color:var(--istpet-dorado);"></i>Mis Datos Académicos
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:rgba(34,44,87,0.04);border-left:3px solid var(--istpet-dorado);">
                                <small class="text-muted d-block" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Nombres Completos</small>
                                <strong style="color:var(--istpet-azul);">{{ Auth::user()->nombreCompleto }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:rgba(34,44,87,0.04);border-left:3px solid var(--istpet-dorado);">
                                <small class="text-muted d-block" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Nacionalidad</small>
                                <strong style="color:var(--istpet-azul);">{{ Auth::user()->nacionalidad ?? 'N/A' }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:rgba(34,44,87,0.04);border-left:3px solid var(--istpet-dorado);">
                                <small class="text-muted d-block" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Tipo de Documento</small>
                                <strong style="color:var(--istpet-azul);">{{ strtoupper(Auth::user()->tipo_documento ?? 'CÉDULA') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:rgba(34,44,87,0.04);border-left:3px solid var(--istpet-dorado);">
                                <small class="text-muted d-block" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Número de Documento</small>
                                <strong style="color:var(--istpet-azul);">{{ Auth::user()->cedula }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:rgba(34,44,87,0.04);border-left:3px solid var(--istpet-dorado);">
                                <small class="text-muted d-block" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Carrera</small>
                                <strong style="color:var(--istpet-azul);">{{ Auth::user()->carrera ?? 'No especificada' }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:rgba(34,44,87,0.04);border-left:3px solid var(--istpet-dorado);">
                                <small class="text-muted d-block" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Ciclo / Nivel</small>
                                <strong style="color:var(--istpet-azul);">{{ Auth::user()->ciclo_nivel }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:rgba(34,44,87,0.04);border-left:3px solid var(--istpet-dorado);">
                                <small class="text-muted d-block" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Correo Institucional</small>
                                <strong style="color:var(--istpet-azul);font-size:0.88rem;">{{ Auth::user()->correo_institucional }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background:rgba(34,44,87,0.04);border-left:3px solid var(--istpet-dorado);">
                                <small class="text-muted d-block" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;">Celular</small>
                                <strong style="color:var(--istpet-azul);">{{ Auth::user()->celular }}</strong>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded" style="background:var(--istpet-azul);">
                                <small class="d-block mb-1" style="font-size:0.7rem;color:rgba(196,168,87,0.8);text-transform:uppercase;letter-spacing:0.5px;">Código QR Único</small>
                                <code style="color:#C4A857;font-size:0.88rem;word-break:break-all;">{{ $carnet->codigo_qr }}</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /row --}}

    @else
    {{-- Sin carnet --}}
    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            <div class="card text-center py-5" style="border:none;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
                <div class="card-body">
                    <i class="bi bi-credit-card-2-front display-1 mb-3" style="color:rgba(34,44,87,0.15);"></i>
                    <h5 style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;">Sin Carnet Asignado</h5>
                    <p class="text-muted">No tienes un carnet asignado todavía. Por favor contacta con el área de administración del instituto.</p>
                    <a href="{{ route('estudiante.dashboard') }}" class="btn" style="background:var(--istpet-dorado);color:var(--istpet-azul);font-family:'Oswald',sans-serif;font-weight:700;">
                        <i class="bi bi-house me-2"></i>Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
