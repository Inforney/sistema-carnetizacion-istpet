<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QR - {{ $laboratorio->nombre }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        .qr-container {
            text-align: center;
            border: 3px solid #1a2342;
            border-radius: 15px;
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            background: #1a2342;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            margin: 0;
            opacity: 0.9;
        }

        .qr-code {
            margin: 30px auto;
            padding: 20px;
            background: white;
            border: 2px solid #C4A857;
            border-radius: 10px;
            display: inline-block;
        }

        .info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }

        .info h2 {
            color: #1a2342;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .info-row {
            margin: 10px 0;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
        }

        .info-value {
            font-size: 16px;
            color: #000;
            margin-top: 5px;
        }

        .codigo {
            background: #1a2342;
            color: #C4A857;
            padding: 15px;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 20px;
        }

        .instrucciones {
            margin-top: 30px;
            padding: 20px;
            background: #fff3cd;
            border-left: 4px solid #C4A857;
            border-radius: 5px;
        }

        .instrucciones h3 {
            color: #856404;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .instrucciones ol {
            margin-left: 20px;
            color: #856404;
        }

        .instrucciones li {
            margin: 5px 0;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="qr-container">
        <div class="header">
            <h1>ISTPET</h1>
            <p>Instituto Superior Tecnológico Mayor Pedro Traversari</p>
        </div>

        <div class="info">
            <h2>{{ $laboratorio->nombre }}</h2>

            <div class="qr-code">
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(300)->generate($laboratorio->codigo_qr_lab)) }}"
                    style="width: 300px; height: 300px;">
            </div>

            <div class="codigo">
                {{ $laboratorio->codigo_qr_lab }}
            </div>

            <div class="info-row">
                <div class="info-label">Ubicación</div>
                <div class="info-value">{{ $laboratorio->ubicacion }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Capacidad</div>
                <div class="info-value">{{ $laboratorio->capacidad }} estudiantes</div>
            </div>
        </div>

        <div class="instrucciones">
            <h3>¿Cómo usar este código QR?</h3>
            <ol>
                <li>Imprime esta página en tamaño A4</li>
                <li>Pégala en un lugar visible cerca de la puerta del laboratorio</li>
                <li>Los estudiantes deben escanear este QR con su celular al entrar y salir</li>
                <li>El sistema registrará automáticamente los accesos</li>
            </ol>
        </div>
    </div>
</body>

</html>