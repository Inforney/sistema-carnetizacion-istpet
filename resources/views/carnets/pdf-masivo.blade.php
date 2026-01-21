<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Carnets ISTPET - Impresión Masiva</title>
    <style>
        @page {
            margin: 5mm;
            size: letter portrait;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }

        .page-container {
            width: 100%;
        }

        .carnet-row {
            display: table;
            width: 100%;
            margin-bottom: 5mm;
        }

        .carnet-cell {
            display: table-cell;
            width: 50%;
            padding: 2mm;
        }

        .carnet-wrapper {
            width: 85.6mm;
            height: 53.98mm;
            position: relative;
            overflow: hidden;
        }

        .background {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a2342 0%, #222C57 50%, #2E3B6F 100%);
        }

        .pattern {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0.05;
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 255, 255, .1) 10px, rgba(255, 255, 255, .1) 20px);
        }

        .carnet-content {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 4mm;
            color: white;
            z-index: 1;
        }

        .header {
            text-align: center;
            padding: 2mm 0;
            border-bottom: 2px solid #C4A857;
            margin-bottom: 3mm;
        }

        .logo {
            font-size: 18pt;
            line-height: 1;
            margin-bottom: 1mm;
        }

        .institucion {
            font-size: 11pt;
            font-weight: bold;
            color: #C4A857;
            letter-spacing: 1px;
            margin-bottom: 0.5mm;
        }

        .subtitulo {
            font-size: 6pt;
            color: rgba(255, 255, 255, 0.9);
        }

        .content-layout {
            display: table;
            width: 100%;
            margin-top: 2mm;
        }

        .left-column {
            display: table-cell;
            width: 58%;
            vertical-align: top;
            padding-right: 2mm;
        }

        .right-column {
            display: table-cell;
            width: 42%;
            vertical-align: top;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.12);
            border-left: 3px solid #C4A857;
            padding: 2mm;
            margin-bottom: 2mm;
            border-radius: 0 2mm 2mm 0;
        }

        .info-label {
            font-size: 5pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.8;
            margin-bottom: 0.5mm;
            color: #C4A857;
        }

        .info-value {
            font-size: 8pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .qr-container {
            background: white;
            padding: 2.5mm;
            border-radius: 3mm;
            text-align: center;
            box-shadow: 0 2mm 4mm rgba(0, 0, 0, 0.3);
        }

        .qr-title {
            font-size: 5pt;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1mm;
        }

        .qr-code-text {
            font-size: 6pt;
            font-weight: bold;
            color: #222C57;
            word-wrap: break-word;
            line-height: 1.3;
            font-family: 'Courier New', monospace;
        }

        .status-badge {
            position: absolute;
            top: 3mm;
            right: 3mm;
            background: #28A745;
            color: white;
            padding: 1mm 3mm;
            border-radius: 10px;
            font-size: 6pt;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0 1mm 3mm rgba(0, 0, 0, 0.3);
            z-index: 10;
        }

        .footer {
            position: absolute;
            bottom: 2.5mm;
            left: 4mm;
            right: 4mm;
            text-align: center;
            border-top: 1px solid rgba(196, 168, 87, 0.5);
            padding-top: 1.5mm;
        }

        .footer-dates {
            font-size: 5pt;
            margin-bottom: 1mm;
        }

        .footer-dates strong {
            color: #C4A857;
        }

        .footer-slogan {
            font-size: 4.5pt;
            opacity: 0.7;
            font-style: italic;
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
                <div class="carnet-wrapper">
                    <div class="background"></div>
                    <div class="pattern"></div>

                    <div class="status-badge">{{ $item['carnet']->estado === 'activo' ? 'ACTIVO' : 'BLOQUEADO' }}</div>

                    <div class="carnet-content">
                        <div class="header">
                            <div class="logo">🎓</div>
                            <div class="institucion">ISTPET</div>
                            <div class="subtitulo">Instituto Superior Tecnológico Mayor Pedro Traversari</div>
                        </div>

                        <div class="content-layout">
                            <div class="left-column">
                                <div class="info-card">
                                    <div class="info-label">Estudiante</div>
                                    <div class="info-value">{{ strtoupper($item['usuario']->nombres) }}</div>
                                    <div class="info-value">{{ strtoupper($item['usuario']->apellidos) }}</div>
                                </div>

                                <div class="info-card">
                                    <div class="info-label">Cédula</div>
                                    <div class="info-value">{{ $item['usuario']->cedula }}</div>
                                </div>

                                <div class="info-card">
                                    <div class="info-label">Tipo</div>
                                    <div class="info-value">{{ strtoupper($item['usuario']->tipo_usuario) }}</div>
                                </div>
                            </div>

                            <div class="right-column">
                                <div class="qr-container">
                                    <div class="qr-title">Código de Acceso</div>
                                    <div class="qr-code-text">{{ $item['carnet']->codigo_qr }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="footer">
                            <div class="footer-dates">
                                <strong>EMITIDO:</strong> {{ $item['fechaEmision'] }} &nbsp;|&nbsp;
                                <strong>VÁLIDO HASTA:</strong> {{ $item['fechaVencimiento'] }}
                            </div>
                            <div class="footer-slogan">Excelencia Académica</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($index % 2 == 1 || $index == count($carnetes) - 1)
        </div>
        @endif

        @if(($index + 1) % 8 == 0 && $index + 1 < count($carnetes))
            <div class="page-break">
    </div>
    @endif
    @endforeach
    </div>
</body>

</html>