<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Códigos QR - Laboratorios ISTPET</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .page-header {
            text-align: center;
            background: #1a2342;
            color: white;
            padding: 30px;
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .page-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .lab-container {
            page-break-after: always;
            padding: 40px;
            min-height: 100vh;
        }

        .lab-container:last-child {
            page-break-after: auto;
        }

        .lab-box {
            border: 3px solid #1a2342;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
        }

        .lab-header {
            background: #C4A857;
            color: #1a2342;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .lab-header h2 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .lab-header p {
            font-size: 14px;
            margin: 0;
        }

        .qr-code {
            margin: 30px auto;
            padding: 20px;
            background: white;
            border: 3px solid #C4A857;
            border-radius: 10px;
            display: inline-block;
        }

        .codigo {
            background: #1a2342;
            color: #C4A857;
            padding: 20px;
            border-radius: 10px;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 3px;
            margin: 20px 0;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin: 30px 0;
        }

        .info-cell {
            display: table-cell;
            width: 50%;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            vertical-align: top;
        }

        .info-cell:first-child {
            margin-right: 10px;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 18px;
            color: #000;
            font-weight: bold;
        }

        .instrucciones {
            background: #fff3cd;
            border-left: 5px solid #C4A857;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: left;
        }

        .instrucciones h3 {
            color: #856404;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .instrucciones ul {
            margin-left: 20px;
            color: #856404;
        }

        .instrucciones li {
            margin: 8px 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    @foreach($laboratorios as $index => $laboratorio)
    @if($index === 0)
    <div class="page-header">
        <h1>CÓDIGOS QR - LABORATORIOS</h1>
        <p>Instituto Superior Tecnológico Mayor Pedro Traversari</p>
        <p style="margin-top: 10px; font-size: 14px;">Generado el {{ date('d/m/Y H:i') }}</p>
    </div>
    @endif

    <div class="lab-container">
        <div class="lab-box">
            <div class="lab-header">
                <h2>{{ $laboratorio->nombre }}</h2>
                <p>{{ $laboratorio->ubicacion }}</p>
            </div>

            <div class="qr-code">
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(350)->generate($laboratorio->codigo_qr_lab)) }}"
                    style="width: 350px; height: 350px;">
            </div>

            <div class="codigo">
                {{ $laboratorio->codigo_qr_lab }}
            </div>

            <div class="info-grid">
                <div class="info-cell">
                    <div class="info-label">Ubicación</div>
                    <div class="info-value">{{ $laboratorio->ubicacion }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-label">Capacidad</div>
                    <div class="info-value">{{ $laboratorio->capacidad }} estudiantes</div>
                </div>
            </div>

            <div class="instrucciones">
                <h3>📱 Instrucciones para Estudiantes</h3>
                <ul>
                    <li><strong>Al ENTRAR:</strong> Escanea este código QR con tu celular desde tu perfil en el sistema</li>
                    <li><strong>Al SALIR:</strong> Vuelve a escanear el mismo código QR para registrar tu salida</li>
                    <li><strong>Importante:</strong> Si no tienes celular, el profesor puede registrarte manualmente</li>
                    <li><strong>Nota:</strong> Tu acceso quedará registrado automáticamente en el sistema</li>
                </ul>
            </div>
        </div>
    </div>
    @endforeach
</body>

</html>