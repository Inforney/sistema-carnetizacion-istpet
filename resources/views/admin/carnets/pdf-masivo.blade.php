<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carnets ISTPET - Descarga Masiva</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
        }

        .page-break {
            page-break-after: always;
        }

        .carnet-container {
            width: 85.6mm;
            height: 135mm;
            margin: 10mm auto;
        }

        .carnet {
            width: 100%;
            height: 100%;
            position: relative;
            background: linear-gradient(135deg, #1a2342 0%, #222C57 100%);
            border-radius: 10px;
            overflow: hidden;
        }

        .header {
            background: #C4A857;
            padding: 10px;
            text-align: center;
            color: #1a2342;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .header p {
            font-size: 9px;
            margin: 2px 0 0 0;
        }

        .foto-container {
            text-align: center;
            padding: 15px;
            background: white;
            margin: 10px;
            border-radius: 8px;
        }

        .foto {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #C4A857;
        }

        .sin-foto {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #ddd;
            display: inline-block;
            border: 3px solid #C4A857;
        }

        .info {
            background: white;
            margin: 0 10px 10px 10px;
            padding: 15px;
            border-radius: 8px;
        }

        .info-row {
            margin-bottom: 8px;
        }

        .info-label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
        }

        .info-value {
            font-size: 11px;
            color: #000;
            font-weight: bold;
        }

        .qr-container {
            text-align: center;
            padding: 10px;
            background: white;
            margin: 0 10px 10px 10px;
            border-radius: 8px;
        }

        .qr-code {
            width: 80px;
            height: 80px;
        }

        .qr-text {
            font-size: 7px;
            color: #666;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            padding: 10px;
            color: white;
            font-size: 8px;
        }

        .estado {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    @foreach($carnets as $index => $carnet)
    <div class="carnet-container">
        <div class="carnet">
            <span class="estado">ACTIVO</span>

            <div class="header">
                <h1>ISTPET</h1>
                <p>Instituto Superior Tecnológico Mayor Pedro Traversari</p>
            </div>

            <div class="foto-container">
                @if($carnet->usuario->foto_url && file_exists(public_path('storage/' . $carnet->usuario->foto_url)))
                <img src="{{ public_path('storage/' . $carnet->usuario->foto_url) }}" class="foto" alt="Foto">
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
                    <div class="info-label">Cédula</div>
                    <div class="info-value">{{ $carnet->usuario->cedula }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Carrera</div>
                    <div class="info-value">{{ $carnet->usuario->carrera ?? 'N/A' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Ciclo</div>
                    <div class="info-value">{{ $carnet->usuario->ciclo_nivel }}</div>
                </div>
            </div>

            <div class="qr-container">
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(200)->generate($carnet->codigo_qr)) }}" class="qr-code">
                <div class="qr-text">{{ $carnet->codigo_qr }}</div>
            </div>

            <div class="footer">
                <p>
                    Emitido:
                    @if(is_object($carnet->fecha_emision))
                    {{ $carnet->fecha_emision->format('d/m/Y') }}
                    @else
                    {{ date('d/m/Y', strtotime($carnet->fecha_emision)) }}
                    @endif
                </p>
                <p>
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
    </div>

    @if($index < count($carnets) - 1)
        <div class="page-break">
        </div>
        @endif
        @endforeach
</body>

</html>