@extends('layouts.app')

@section('title', 'Mi Carnet Digital')

@section('content')
<div class="container-fluid py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
            {{-- Botones de acción --}}
            <div class="text-center mb-4">
                <a href="{{ route('estudiante.carnet.descargar') }}" class="btn btn-light btn-lg shadow-sm me-2">
                    <i class="bi bi-download me-2"></i>Descargar PDF
                </a>
                <button onclick="window.print()" class="btn btn-outline-light btn-lg shadow-sm">
                    <i class="bi bi-printer me-2"></i>Imprimir
                </button>
            </div>

            {{-- Carnet Digital --}}
            <div class="carnet-wrapper">
                <div class="carnet-container">
                    {{-- Header azul institucional --}}
                    <div class="header">
                        <div class="card-type-badge">{{ ucfirst($usuario->tipo_usuario) }}</div>

                        <div class="header-content">
                            <div class="institute-logo">
                                <div class="logo-circle">IT</div>
                                <div class="logo-text">
                                    <div class="logo-title">ISTPET</div>
                                    <div class="logo-subtitle">Tecnológico</div>
                                </div>
                            </div>
                            <div class="institute-name">
                                Instituto Superior Tecnológico<br>
                                <strong>Mayor Pedro Traversari</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Barra dorada --}}
                    <div class="golden-bar"></div>

                    {{-- Contenido --}}
                    <div class="content">
                        {{-- Foto --}}
                        <div class="photo-section">
                            <div class="photo-container">
                                <div class="photo-ring">
                                    <div class="photo">
                                        @if($usuario->foto_url)
                                        <img src="{{ asset('storage/' . $usuario->foto_url) }}" alt="Foto de {{ $usuario->nombres }}">
                                        @else
                                        <div class="photo-placeholder">
                                            {{ strtoupper(substr($usuario->nombres, 0, 1) . substr($usuario->apellidos, 0, 1)) }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="status-dot"></div>
                            </div>
                        </div>

                        {{-- Info estudiante --}}
                        <div class="student-info">
                            <div class="student-name">
                                {{ $usuario->nombres }}<br>{{ $usuario->apellidos }}
                            </div>
                            <div class="student-id">CI: {{ $usuario->cedula }}</div>
                        </div>

                        {{-- Detalles --}}
                        <div class="details-grid">
                            <div class="detail-with-icon">
                                <div class="detail-icon">👤</div>
                                <div class="detail-content">
                                    <div class="detail-label">Tipo de Usuario</div>
                                    <div class="detail-value">{{ ucfirst($usuario->tipo_usuario) }}</div>
                                </div>
                            </div>

                            <div class="detail-with-icon">
                                <div class="detail-icon">📚</div>
                                <div class="detail-content">
                                    <div class="detail-label">Carrera</div>
                                    <div class="detail-value">{{ $usuario->carrera ?? 'No especificada' }}</div>
                                </div>
                            </div>

                            <div class="detail-with-icon">
                                <div class="detail-icon">🎓</div>
                                <div class="detail-content">
                                    <div class="detail-label">Ciclo/Nivel</div>
                                    <div class="detail-value">{{ $usuario->ciclo_nivel ?? $usuario->semestre ?? 'No especificado' }}</div>
                                </div>
                            </div>

                            <div class="detail-with-icon">
                                <div class="detail-icon">✓</div>
                                <div class="detail-content">
                                    <div class="detail-label">Estado</div>
                                    <div class="detail-value status-active">
                                        ● {{ ucfirst($carnet->estado) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Información del Carnet --}}
                        <div class="carnet-info">
                            <div class="info-item-small">
                                <span class="info-label-small">Código:</span>
                                <span class="info-value-small">{{ $carnet->codigo_qr }}</span>
                            </div>
                            <div class="info-item-small">
                                <span class="info-label-small">Emitido:</span>
                                <span class="info-value-small">{{ \Carbon\Carbon::parse($carnet->fecha_emision)->format('d/m/Y') }}</span>
                            </div>
                            <div class="info-item-small">
                                <span class="info-label-small">Vencimiento:</span>
                                <span class="info-value-small">{{ \Carbon\Carbon::parse($carnet->fecha_vencimiento)->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        {{-- QR Code --}}
                        <div class="qr-section">
                            <div class="qr-label">⚡ Código de Acceso Digital</div>
                            <div class="qr-code">
                                {!! QrCode::size(125)->generate($carnet->codigo_qr) !!}
                            </div>
                            <div class="qr-text">Escanea para verificar autenticidad</div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="footer">
                        <div class="footer-text">
                            <span class="footer-highlight">ISTPET {{ date('Y') }}</span> • Carnet Digital Oficial
                        </div>
                    </div>
                </div>
            </div>

            {{-- Instrucciones --}}
            <div class="text-center mt-4">
                <div class="alert alert-light shadow-sm" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Importante:</strong> Este carnet es personal e intransferible.
                    Preséntalo al ingresar a laboratorios y aulas.
                </div>
            </div>
        </div>
    </div>
</div>

<style>    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .carnet-wrapper {
        max-width: 400px;
        margin: 0 auto;
    }

    .carnet-container {
        width: 100%;
        max-width: 350px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        position: relative;
        margin: 0 auto;
    }

    .header {
        background: #1a2342;
        padding: 20px 20px 18px;
        position: relative;
        overflow: hidden;
    }

    .header::before {
        content: '';
        position: absolute;
        top: 0;
        right: -50px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(247, 147, 30, 0.1) 0%, transparent 70%);
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .institute-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .logo-circle {
        width: 50px;
        height: 50px;
        background: #F7931E;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 20px;
        margin-right: 12px;
        box-shadow: 0 4px 12px rgba(247, 147, 30, 0.4);
    }

    .logo-text {
        color: white;
        text-align: left;
    }

    .logo-title {
        font-size: 22px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .logo-subtitle {
        font-size: 9px;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-top: 2px;
    }

    .institute-name {
        text-align: center;
        color: white;
        font-size: 10px;
        line-height: 1.4;
        opacity: 0.95;
        margin-top: 10px;
    }

    .golden-bar {
        height: 8px;
        background: linear-gradient(90deg, #F7931E 0%, #FFB84D 50%, #F7931E 100%);
        box-shadow: 0 2px 8px rgba(247, 147, 30, 0.3);
    }

    .card-type-badge {
        position: absolute;
        top: 25px;
        right: 20px;
        background: rgba(247, 147, 30, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(247, 147, 30, 0.4);
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #F7931E;
        font-weight: 600;
        z-index: 2;
    }

    .content {
        padding: 0 22px 22px;
        position: relative;
    }

    .photo-section {
        text-align: center;
        margin-top: -45px;
        margin-bottom: 18px;
        position: relative;
        z-index: 3;
    }

    .photo-container {
        width: 110px;
        height: 110px;
        margin: 0 auto;
        border-radius: 50%;
        background: white;
        padding: 4px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        position: relative;
    }

    .photo-ring {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, #F7931E 0%, #FFB84D 100%);
        padding: 3px;
    }

    .photo {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        background: #e0e0e0;
    }

    .photo img,
    .photo-placeholder {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: #888;
        font-weight: bold;
        background: #e0e0e0;
    }

    .status-dot {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 18px;
        height: 18px;

        background: {
                {
                $carnet->estado ==='activo' ? '#28A745': '#DC3545'
            }
        }

        ;
        border: 3px solid white;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4);
    }

    .student-info {
        text-align: center;
        margin-bottom: 20px;
    }

    .student-name {
        font-size: 19px;
        font-weight: 700;
        color: #1a2342;
        margin-bottom: 8px;
        line-height: 1.25;
        text-transform: uppercase;
    }

    .student-id {
        display: inline-block;
        background: linear-gradient(135deg, #F7931E 0%, #FFB84D 100%);
        color: white;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
        box-shadow: 0 4px 12px rgba(247, 147, 30, 0.3);
    }

    .details-grid {
        background: #F8F9FA;
        border-radius: 12px;
        padding: 14px;
        margin-bottom: 16px;
    }

    .detail-with-icon {
        display: flex;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #dee2e6;
    }

    .detail-with-icon:last-child {
        border-bottom: none;
    }

    .detail-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #F7931E 0%, #FFB84D 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        margin-right: 12px;
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        font-size: 10px;
        color: #6C757D;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .detail-value {
        font-size: 13px;
        color: #1a2342;
        font-weight: 500;
    }

    .status-active {
        color: {
                {
                $carnet->estado ==='activo' ? '#28A745': '#DC3545'
            }
        }

        ;
    }

    .carnet-info {
        background: linear-gradient(135deg, #1a2342 0%, #2d4066 100%);
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 16px;
    }

    .info-item-small {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px solid rgba(247, 147, 30, 0.2);
    }

    .info-item-small:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-item-small:first-child {
        padding-top: 0;
    }

    .info-label-small {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value-small {
        font-size: 11px;
        color: #F7931E;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .qr-section {
        background: white;
        border: 2px solid #F7931E;
        border-radius: 12px;
        padding: 14px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(247, 147, 30, 0.1);
    }

    .qr-label {
        font-size: 10px;
        color: #1a2342;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .qr-code {
        margin: 0 auto 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qr-code svg {
        max-width: 125px;
        height: auto;
    }

    .qr-text {
        font-size: 10px;
        color: #6C757D;
        margin-top: 4px;
        letter-spacing: 0.5px;
    }

    .footer {
        background: #1a2342;
        padding: 12px;
        text-align: center;
    }

    .footer-text {
        font-size: 9px;
        color: rgba(255, 255, 255, 0.7);
        letter-spacing: 0.5px;
    }

    .footer-highlight {
        color: #F7931E;
        font-weight: 600;
    }

    @media (max-width: 480px) {
        .carnet-container {
            max-width: 100%;
        }

        .student-name {
            font-size: 17px;
        }

        .detail-value {
            font-size: 12px;
        }
    }

    @media print {
        body {
            background: white !important;
        }

        .btn,
        .alert {
            display: none !important;
        }

        .carnet-container {
            box-shadow: none;
            page-break-inside: avoid;
        }
    }
</style>
@endsection