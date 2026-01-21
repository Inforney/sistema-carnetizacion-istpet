<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Carnet ISTPET - {{ $usuario->nombreCompleto }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }

        .carnet-wrapper {
            width: 85.6mm;
            height: 53.98mm;
            position: relative;
            overflow: hidden;
        }

        /* Fondo con gradiente */
        .background {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a2342 0%, #222C57 50%, #2E3B6F 100%);
        }

        /* Patrón decorativo */
        .pattern {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0.05;
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 255, 255, .1) 10px, rgba(255, 255, 255, .1) 20px);
        }

        /* Contenido principal */
        .carnet-content {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 4mm;
            color: white;
            z-index: 1;
        }

        /* Header con logo */
        .header {
            text-align: center;
            padding: 2mm 0;
            border-bottom: 2px solid #C4A857;
            margin-bottom: 3mm;
        }

        .logo-container {
            display: inline-block;
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
            font-weight: normal;
        }

        /* Layout de 2 columnas */
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

        /* Cajas de información */
        .info-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
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

        /* Código QR */
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

        .qr-instruction {
            font-size: 4pt;
            color: #999;
            margin-top: 1mm;
            font-style: italic;
        }

        /* Badge de estado */
        .status-badge {
            position: absolute;
            top: 3mm;
            right: 3mm;

            background: {
                    {
                    $carnet->estado ==='activo' ? '#28A745': '#DC3545'
                }
            }

            ;
            color: white;
            padding: 1mm 3mm;
            border-radius: 10px;
            font-size: 6pt;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0 1mm 3mm rgba(0, 0, 0, 0.3);
            z-index: 10;
        }

        /* Footer */
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

        /* Decoración esquinas */
        .corner-decoration {
            position: absolute;
            width: 8mm;
            height: 8mm;
            border: 1px solid rgba(196, 168, 87, 0.3);
        }

        .corner-tl {
            top: 2mm;
            left: 2mm;
            border-right: none;
            border-bottom: none;
        }

        .corner-br {
            bottom: 2mm;
            right: 2mm;
            border-left: none;
            border-top: none;
        }
    </style>
</head>

<body>
    <div class="carnet-wrapper">
        <!-- Fondo -->
        <div class="background"></div>
        <div class="pattern"></div>

        <!-- Decoraciones de esquina -->
        <div class="corner-decoration corner-tl"></div>
        <div class="corner-decoration corner-br"></div>

        <!-- Badge de estado -->
        <div class="status-badge">{{ $carnet->estado === 'activo' ? 'ACTIVO' : 'BLOQUEADO' }}</div>

        <!-- Contenido -->
        <div class="carnet-content">
            <!-- Header -->
            <div class="header">
                <div class="logo-container">
                    <div class="logo">🎓</div>
                    <div class="institucion">ISTPET</div>
                    <div class="subtitulo">Instituto Superior Tecnológico Mayor Pedro Traversari</div>
                </div>
            </div>

            <!-- Layout de 2 columnas -->
            <div class="content-layout">
                <!-- Columna izquierda: Información -->
                <div class="left-column">
                    <div class="info-card">
                        <div class="info-label">Estudiante</div>
                        <div class="info-value">{{ strtoupper($usuario->nombres) }}</div>
                        <div class="info-value">{{ strtoupper($usuario->apellidos) }}</div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Cédula de Identidad</div>
                        <div class="info-value">{{ $usuario->cedula }}</div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Tipo de Usuario</div>
                        <div class="info-value">{{ strtoupper($usuario->tipo_usuario) }}</div>
                    </div>
                </div>

                <!-- Columna derecha: QR -->
                <div class="right-column">
                    <div class="qr-container">
                        <div class="qr-title">Código de Acceso</div>
                        <div class="qr-code-text">{{ $carnet->codigo_qr }}</div>
                        <div class="qr-instruction">Escanear para registro</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-dates">
                    <strong>EMITIDO:</strong> {{ $fechaEmision }} &nbsp;|&nbsp;
                    <strong>VÁLIDO HASTA:</strong> {{ $fechaVencimiento }}
                </div>
                <div class="footer-slogan">
                    Excelencia Académica - Atrévete a cambiar el mundo
                </div>
            </div>
        </div>
    </div>
</body>

</html>