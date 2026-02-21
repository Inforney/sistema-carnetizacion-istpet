<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carnet ISTPET - {{ $carnet->usuario->nombreCompleto }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: 85.6mm 135mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Arial', sans-serif;
            width: 85.6mm;
            height: 135mm;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .carnet {
            width: 100%;
            height: 100%;
            position: relative;
            background: linear-gradient(135deg, #1a2342 0%, #222C57 100%);
            border-radius: 8px;
            overflow: hidden;
            page-break-after: avoid;
            page-break-inside: avoid;
        }

        .header {
            background: #C4A857;
            padding: 6px;
            text-align: center;
            color: #1a2342;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .header p {
            font-size: 8px;
            margin: 2px 0 0 0;
        }

        .foto-container {
            text-align: center;
            padding: 10px;
            background: white;
            margin: 6px;
            border-radius: 6px;
        }

        .foto {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #C4A857;
        }

        .sin-foto {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #ddd;
            display: inline-block;
            border: 2px solid #C4A857;
        }

        .info {
            background: white;
            margin: 0 6px 6px 6px;
            padding: 10px;
            border-radius: 6px;
        }

        .info-row {
            margin-bottom: 5px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 7px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
        }

        .info-value {
            font-size: 10px;
            color: #000;
            font-weight: bold;
        }

        .qr-container {
            text-align: center;
            padding: 6px;
            background: white;
            margin: 0 6px 6px 6px;
            border-radius: 6px;
        }

        .qr-code {
            width: 65px;
            height: 65px;
        }

        .qr-text {
            font-size: 6px;
            color: #666;
            margin-top: 2px;
        }

        .footer {
            text-align: center;
            padding: 6px 6px 4px 6px;
            color: white;
            font-size: 7px;
            line-height: 1.3;
        }

        .estado {
            position: absolute;
            top: 8px;
            right: 8px;

            background: {
                    {
                    $carnet->estado ==='activo' ? '#28a745': '#dc3545'
                }
            }

            ;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="carnet">
        <span class="estado">{{ strtoupper($carnet->estado) }}</span>

        <div class="header">
            <h1>ISTPET</h1>
            <p>Instituto Superior Tecnológico Mayor Pedro Traversari</p>
        </div>

        <div class="foto-container">
            @if($carnet->usuario->foto_url && file_exists(public_path($carnet->usuario->foto_url)))
            <img src="{{ public_path($carnet->usuario->foto_url) }}" class="foto" alt="Foto">
            @else
            <div class="sin-foto"></div>
            @endif
        </div>

        <div class="info">
            <div class="info-row">
                <div class="info-label">Estudiante</div>
                <div class="info-value">{{ $carnet->usuario->nombreCompleto }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Cédula de Identidad</div>
                <div class="info-value">{{ $carnet->usuario->cedula }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Carrera</div>
                <div class="info-value">{{ $carnet->usuario->carrera ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Ciclo/Nivel</div>
                <div class="info-value">{{ $carnet->usuario->ciclo_nivel }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Correo</div>
                <div class="info-value" style="font-size: 8px;">{{ $carnet->usuario->correo_institucional }}</div>
            </div>
        </div>

        <div class="qr-container">
            <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(130)->generate($carnet->codigo_qr)) }}" class="qr-code">
            <div class="qr-text">{{ $carnet->codigo_qr }}</div>
        </div>

        <div class="footer">
            <p style="margin-bottom: 2px;">
                Emitido:
                @if(is_object($carnet->fecha_emision))
                {{ $carnet->fecha_emision->format('d/m/Y') }}
                @else
                {{ date('d/m/Y', strtotime($carnet->fecha_emision)) }}
                @endif
            </p>
            <p style="margin: 0;">
                Válido hasta:
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
</body>

</html>