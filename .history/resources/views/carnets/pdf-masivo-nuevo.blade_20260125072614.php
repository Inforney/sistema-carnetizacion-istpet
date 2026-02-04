<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carnets ISTPET - Impresión Masiva</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: white;
        }

        .page-container {
            width: 100%;
        }

        .carnet-row {
            display: table;
            width: 100%;
            margin-bottom: 10mm;
            page-break-inside: avoid;
        }

        .carnet-cell {
            display: table-cell;
            width: 50%;
            padding: 5mm;
            vertical-align: top;
        }

        .carnet-container {
            width: 100%;
            max-width: 340px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            border: 1px solid #dee2e6;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: #1a2342;
            padding: 15px;
            position: relative;
        }

        .institute-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .logo-circle {
            width: 40px;
            height: 40px;
            background: #F7931E;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 16px;
            margin-right: 10px;
        }

        .logo-text {
            color: white;
            text-align: left;
        }

        .logo-title {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .logo-subtitle {
            font-size: 8px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .institute-name {
            text-align: center;
            color: white;
            font-size: 9px;
            line-height: 1.3;
            opacity: 0.95;
        }

        .card-type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(247, 147, 30, 0.2);
            border: 1px solid rgba(247, 147, 30, 0.4);
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #F7931E;
            font-weight: 600;
        }

        /* Barra dorada */
        .golden-bar {
            height: 6px;
            background: linear-gradient(90deg, #F7931E 0%, #FFB84D 50%, #F7931E 100%);
        }

        /* Contenido */
        .content {
            padding: 0 18px 18px;
            position: relative;
        }

        .photo-section {
            text-align: center;
            margin-top: -35px;
            margin-bottom: 12px;
            position: relative;
            z-index: 3;
        }

        .photo-container {
            width: 90px;
            height: 90px;
            margin: 0 auto;
            border-radius: 50%;
            background: white;
            padding: 3px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #888;
            font-weight: bold;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .status-dot {
            position: absolute;
            bottom: 3px;
            right: 3px;
            width: 14px;
            height: 14px;
            background: #28A745;
            border: 2px solid white;
            border-radius: 50%;
        }

        .student-info {
            text-align: center;
            margin-bottom: 14px;
        }

        .student-name {
            font-size: 15px;
            font-weight: 700;
            color: #1a2342;
            margin-bottom: 6px;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .student-id {
            display: inline-block;
            background: linear-gradient(135deg, #F7931E 0%, #FFB84D 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .details-grid {
            background: #F8F9FA;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 12px;
        }

        .detail-with-icon {
            display: flex;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .detail-with-icon:last-child {
            border-bottom: none;
        }

        .detail-icon {
            width: 26px;
            height: 26px;
            background: linear-gradient(135deg, #F7931E 0%, #FFB84D 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 13px;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 8px;
            color: #6C757D;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 1px;
        }

        .detail-value {
            font-size: 11px;
            color: #1a2342;
            font-weight: 500;
            line-height: 1.2;
        }

        .carnet-info {
            background: linear-gradient(135deg, #1a2342 0%, #2d4066 100%);
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 12px;
        }

        .info-item-small {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
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
            font-size: 8px;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .info-value-small {
            font-size: 9px;
            color: #F7931E;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .qr-section {
            background: white;
            border: 2px solid #F7931E;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
        }

        .qr-label {
            font-size: 9px;
            color: #1a2342;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            margin: 0 auto 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-text {
            font-size: 8px;
            color: #6C757D;
            letter-spacing: 0.3px;
        }

        .footer {
            background: #1a2342;
            padding: 8px;
            text-align: center;
        }

        .footer-text {
            font-size: 8px;
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: 0.3px;
        }

        .footer-highlight {
            color: #F7931E;
            font-weight: 600;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="page-container">
        @foreach($carnetes as $index => $item)
        @if($index % 2 == 0)
        <div class="carnet-row">
            @endif

            <div class="carnet-cell">
                <div class="carnet-container">
                    <!-- Header -->
                    <div class="header">
                        <div class="card-type-badge">{{ ucfirst($item['usuario']->tipo_usuario) }}</div>

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

                    <!-- Barra dorada -->
                    <div class="golden-bar"></div>

                    <!-- Contenido -->
                    <div class="content">
                        <!-- Foto -->
                        <div class="photo-section">
                            <div class="photo-container">
                                <div class="photo-ring">
                                    <div class="photo">
                                        @if($item['usuario']->foto_url)
                                        <img src="{{ public_path('storage/' . $item['usuario']->foto_url) }}" alt="Foto">
                                        @else
                                        {{ strtoupper(substr($item['usuario']->nombres, 0, 1) . substr($item['usuario']->apellidos, 0, 1)) }}
                                        @endif
                                    </div>
                                </div>
                                <div class="status-dot" style="background: {{ $item['carnet']->estado === 'activo' ? '#28A745' : '#DC3545' }};"></div>
                            </div>
                        </div>

                        <!-- Info estudiante -->
                        <div class="student-info">
                            <div class="student-name">
                                {{ $item['usuario']->nombres }}<br>{{ $item['usuario']->apellidos }}
                            </div>
                            <div class="student-id">CI: {{ $item['usuario']->cedula }}</div>
                        </div>

                        <!-- Detalles -->
                        <div class="details-grid">
                            <div class="detail-with-icon">
                                <div class="detail-icon">👤</div>
                                <div class="detail-content">
                                    <div class="detail-label">Tipo</div>
                                    <div class="detail-value">{{ ucfirst($item['usuario']->tipo_usuario) }}</div>
                                </div>
                            </div>

                            <div class="detail-with-icon">
                                <div class="detail-icon">📚</div>
                                <div class="detail-content">
                                    <div class="detail-label">Carrera</div>
                                    <div class="detail-value">{{ $item['usuario']->carrera ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="detail-with-icon">
                                <div class="detail-icon">🎓</div>
                                <div class="detail-content">
                                    <div class="detail-label">Nivel</div>
                                    <div class="detail-value">{{ $item['usuario']->ciclo_nivel ?? $item['usuario']->semestre ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="detail-with-icon">
                                <div class="detail-icon">✓</div>
                                <div class="detail-content">
                                    <div class="detail-label">Estado</div>
                                    <div class="detail-value" style="color: {{ $item['carnet']->estado === 'activo' ? '#28A745' : '#DC3545' }};">
                                        ● {{ ucfirst($item['carnet']->estado) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info del carnet -->
                        <div class="carnet-info">
                            <div class="info-item-small">
                                <span class="info-label-small">Código:</span>
                                <span class="info-value-small">{{ $item['carnet']->codigo_qr }}</span>
                            </div>
                            <div class="info-item-small">
                                <span class="info-label-small">Emitido:</span>
                                <span class="info-value-small">{{ $item['fechaEmision'] }}</span>
                            </div>
                            <div class="info-item-small">
                                <span class="info-label-small">Vence:</span>
                                <span class="info-value-small">{{ $item['fechaVencimiento'] }}</span>
                            </div>
                        </div>

                        <!-- QR -->
                        <div class="qr-section">
                            <div class="qr-label">⚡ Código Digital</div>
                            <div class="qr-code">
                                {!! QrCode::size(100)->generate($item['carnet']->codigo_qr) !!}
                            </div>
                            <div class="qr-text">Escanea para verificar</div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="footer">
                        <div class="footer-text">
                            <span class="footer-highlight">ISTPET {{ date('Y') }}</span> • Carnet Oficial
                        </div>
                    </div>
                </div>
            </div>

            @if($index % 2 == 1 || $index == count($carnetes) - 1)
        </div> {{-- Cerrar carnet-row --}}
        @endif

        {{-- Salto de página cada 4 carnets (2 filas) --}}
        @if(($index + 1) % 4 == 0 && $index + 1 < count($carnetes))
            <div class="page-break">
    </div>
    @endif
    @endforeach
    </div>
</body>

</html>