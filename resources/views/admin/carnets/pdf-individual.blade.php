<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carnet ISTPET - {{ $carnet->usuario->nombreCompleto }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: A4 portrait;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            font-family: Arial, Helvetica, sans-serif;
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            background: #eef0f5;
            overflow: hidden;
        }
    </style>
</head>
<body>

{{-- Página A4: header 20mm + gold 2mm + content 253mm + gold 2mm + footer 20mm = 297mm --}}
<table width="100%" style="width:210mm; height:297mm; border-collapse:collapse; table-layout:fixed;" cellpadding="0" cellspacing="0">

    {{-- ══ ENCABEZADO (20mm) ══ --}}
    <tr>
        <td style="height:20mm; background-color:#222C57; padding:4mm 12mm 0 12mm; vertical-align:middle;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="vertical-align:middle;">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="vertical-align:middle; padding-right:8px; width:42px;">
                                    <div style="width:34px;height:34px;background:#fff;border-radius:6px;text-align:center;padding:3px;">
                                        <img src="{{ public_path('images/LogoISTPET.png') }}" width="28" height="28" style="display:block;object-fit:contain;">
                                    </div>
                                </td>
                                <td style="vertical-align:middle;">
                                    <div style="color:#C4A857;font-size:18px;font-weight:bold;letter-spacing:3px;line-height:1;">ISTPET</div>
                                    <div style="color:rgba(255,255,255,0.70);font-size:7.5px;line-height:1.3;margin-top:2px;">Instituto Superior Tecnológico Público Mayor Pedro Traversari · Sistema de Carnetización</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="vertical-align:middle; text-align:right;">
                        <div style="color:rgba(196,168,87,0.6);font-size:6.5px;text-transform:uppercase;letter-spacing:0.8px;">Generado el</div>
                        <div style="color:#fff;font-size:8.5px;font-weight:bold;">{{ now()->format('d/m/Y H:i') }}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- ══ BARRA DORADA (2mm) ══ --}}
    <tr>
        <td style="height:2mm;background-color:#C4A857;padding:0;font-size:0;line-height:0;"></td>
    </tr>

    {{-- ══ ÁREA CENTRAL (253mm) ══ --}}
    <tr>
        <td style="height:253mm; background-color:#eef0f5; vertical-align:middle; text-align:center; padding:0;">

            {{-- Etiqueta --}}
            <div style="margin-bottom:4mm;text-align:center;">
                <span style="display:inline-block;background:#222C57;color:#C4A857;font-size:8px;font-weight:bold;padding:3px 18px;border-radius:12px;letter-spacing:2px;text-transform:uppercase;">
                    Carnet Estudiantil
                </span>
            </div>

            {{-- ══ CARNET GRANDE: 130mm × 218mm ══ --}}
            {{-- Secciones: Header 33mm + Gold 2mm + Photo 53mm + Info 84mm + Gold 2mm + QR 44mm = 218mm --}}
            <table width="130mm" style="width:130mm; height:218mm; border-collapse:collapse; table-layout:fixed; box-shadow:0 3mm 12mm rgba(0,0,0,0.40); margin:0 auto;" cellpadding="0" cellspacing="0">

                {{-- Header Navy (33mm) --}}
                <tr>
                    <td style="height:33mm; background-color:#222C57; padding:7px 12px 6px 12px; vertical-align:middle;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="vertical-align:middle; width:72%;">
                                    <table cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="vertical-align:middle; padding-right:8px; width:44px;">
                                                <div style="width:36px;height:36px;background:#fff;border-radius:6px;text-align:center;padding:3px;">
                                                    <img src="{{ public_path('images/LogoISTPET.png') }}" width="30" height="30" style="display:block;object-fit:contain;">
                                                </div>
                                            </td>
                                            <td style="vertical-align:middle;">
                                                <div style="color:#C4A857;font-size:24px;font-weight:bold;letter-spacing:3px;line-height:1;">ISTPET</div>
                                                <div style="color:rgba(255,255,255,0.70);font-size:8px;line-height:1.4;margin-top:3px;">Inst. Superior Tecnológico<br>Mayor Pedro Traversari</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="vertical-align:top; text-align:right; padding-top:3px; width:28%;">
                                    @if($carnet->estado === 'activo')
                                    <span style="display:inline-block;background:#28a745;color:#fff;font-size:8.5px;font-weight:bold;padding:3px 10px;border-radius:10px;text-transform:uppercase;letter-spacing:0.5px;">ACTIVO</span>
                                    @else
                                    <span style="display:inline-block;background:#dc3545;color:#fff;font-size:8.5px;font-weight:bold;padding:3px 10px;border-radius:10px;text-transform:uppercase;letter-spacing:0.5px;">{{ strtoupper($carnet->estado) }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Barra dorada (2mm) --}}
                <tr><td style="height:2mm;background-color:#C4A857;padding:0;font-size:0;line-height:0;"></td></tr>

                {{-- Foto Navy (53mm) --}}
                <tr>
                    <td style="height:53mm;background-color:#1a2342;padding:10px 8px 8px 8px;text-align:center;vertical-align:middle;">
                        @if($carnet->usuario->foto_url && file_exists(public_path($carnet->usuario->foto_url)))
                        <img src="{{ public_path($carnet->usuario->foto_url) }}" width="100" height="100" style="border-radius:50%;object-fit:cover;border:4px solid #C4A857;display:inline-block;">
                        @else
                        <div style="width:100px;height:100px;border-radius:50%;background:#2d3d72;border:4px solid #C4A857;display:inline-block;text-align:center;">
                            <img src="{{ public_path('images/LogoISTPET.png') }}" width="54" height="54" style="margin-top:19px;opacity:0.55;object-fit:contain;display:block;margin-left:auto;margin-right:auto;">
                        </div>
                        @endif
                        <br>
                        <span style="display:inline-block;background:#C4A857;color:#222C57;font-size:8.5px;font-weight:bold;padding:3px 16px;border-radius:10px;text-transform:uppercase;letter-spacing:1.2px;margin-top:7px;">Estudiante</span>
                    </td>
                </tr>

                {{-- Info Blanca (84mm) --}}
                <tr>
                    <td style="height:84mm;background:#fff;vertical-align:top;border-left:6px solid #C4A857;padding:10px 14px 8px 14px;">
                        <div style="font-size:15px;font-weight:bold;color:#222C57;line-height:1.25;margin-bottom:7px;padding-bottom:6px;border-bottom:1px solid rgba(196,168,87,0.4);">{{ $carnet->usuario->nombreCompleto }}</div>
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="width:50%;padding-right:6px;padding-bottom:6px;vertical-align:top;">
                                    <div style="font-size:7.5px;color:#C4A857;text-transform:uppercase;font-weight:bold;letter-spacing:0.5px;line-height:1;margin-bottom:2px;">{{ $carnet->usuario->tipo_documento === 'pasaporte' ? 'Pasaporte' : 'Cédula de Identidad' }}</div>
                                    <div style="font-size:13px;color:#222C57;font-weight:bold;line-height:1.2;">{{ $carnet->usuario->cedula }}</div>
                                </td>
                                <td style="width:50%;padding-bottom:6px;vertical-align:top;">
                                    <div style="font-size:7.5px;color:#C4A857;text-transform:uppercase;font-weight:bold;letter-spacing:0.5px;line-height:1;margin-bottom:2px;">Ciclo / Nivel</div>
                                    <div style="font-size:12px;color:#222C57;font-weight:bold;line-height:1.2;">{{ $carnet->usuario->ciclo_nivel }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding-bottom:6px;vertical-align:top;">
                                    <div style="font-size:7.5px;color:#C4A857;text-transform:uppercase;font-weight:bold;letter-spacing:0.5px;line-height:1;margin-bottom:2px;">Carrera</div>
                                    <div style="font-size:13px;color:#222C57;font-weight:bold;line-height:1.2;">{{ $carnet->usuario->carrera ?? 'N/A' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding-bottom:6px;vertical-align:top;">
                                    <div style="font-size:7.5px;color:#C4A857;text-transform:uppercase;font-weight:bold;letter-spacing:0.5px;line-height:1;margin-bottom:2px;">Correo Institucional</div>
                                    <div style="font-size:11px;color:#222C57;font-weight:bold;line-height:1.2;">{{ $carnet->usuario->correo_institucional }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:50%;padding-right:6px;vertical-align:top;">
                                    <div style="font-size:7.5px;color:#C4A857;text-transform:uppercase;font-weight:bold;letter-spacing:0.5px;line-height:1;margin-bottom:2px;">Nacionalidad</div>
                                    <div style="font-size:12px;color:#222C57;font-weight:bold;line-height:1.2;">{{ $carnet->usuario->nacionalidad ?? 'N/A' }}</div>
                                </td>
                                <td style="width:50%;vertical-align:top;">
                                    <div style="font-size:7.5px;color:#C4A857;text-transform:uppercase;font-weight:bold;letter-spacing:0.5px;line-height:1;margin-bottom:2px;">Celular</div>
                                    <div style="font-size:12px;color:#222C57;font-weight:bold;line-height:1.2;">{{ $carnet->usuario->celular }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Barra dorada (2mm) --}}
                <tr><td style="height:2mm;background-color:#C4A857;padding:0;font-size:0;line-height:0;"></td></tr>

                {{-- Footer QR Navy (44mm) --}}
                <tr>
                    <td style="height:44mm;background-color:#222C57;padding:9px 12px;vertical-align:middle;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="width:82px;vertical-align:middle;text-align:center;">
                                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(200)->generate($carnet->codigo_qr)) }}" width="76" height="76" style="display:block;margin:0 auto;">
                                    <div style="font-size:6px;color:rgba(196,168,87,0.55);margin-top:3px;text-align:center;word-break:break-all;">{{ substr($carnet->codigo_qr, 0, 22) }}</div>
                                </td>
                                <td style="padding-left:10px;vertical-align:middle;border-left:1px solid rgba(196,168,87,0.25);">
                                    <div style="font-size:7px;color:rgba(196,168,87,0.75);text-transform:uppercase;letter-spacing:0.5px;line-height:1;">Fecha de Emisión</div>
                                    <div style="font-size:12px;color:#fff;font-weight:bold;line-height:1.3;margin-bottom:6px;">
                                        @if(is_object($carnet->fecha_emision))
                                        {{ $carnet->fecha_emision->format('d/m/Y') }}
                                        @else
                                        {{ date('d/m/Y', strtotime($carnet->fecha_emision)) }}
                                        @endif
                                    </div>
                                    <div style="font-size:7px;color:rgba(196,168,87,0.75);text-transform:uppercase;letter-spacing:0.5px;line-height:1;">Válido Hasta</div>
                                    <div style="font-size:12px;color:#C4A857;font-weight:bold;line-height:1.3;margin-bottom:7px;">
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
                                    <div style="font-size:6.5px;color:rgba(255,255,255,0.30);font-style:italic;border-top:1px solid rgba(196,168,87,0.15);padding-top:4px;">Excelencia Académica · Traversari</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>{{-- /carnet --}}

            {{-- Instrucción recorte --}}
            <div style="margin-top:4mm;text-align:center;">
                <span style="font-size:8px;color:#aaa;letter-spacing:0.5px;">✂ Recortar por los bordes · {{ $carnet->usuario->cedula }}</span>
            </div>

        </td>
    </tr>

    {{-- ══ BARRA DORADA (2mm) ══ --}}
    <tr>
        <td style="height:2mm;background-color:#C4A857;padding:0;font-size:0;line-height:0;"></td>
    </tr>

    {{-- ══ PIE DE PÁGINA (20mm) ══ --}}
    <tr>
        <td style="height:20mm; background-color:#222C57; padding:4mm 12mm; vertical-align:middle;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="vertical-align:middle;">
                        <div style="color:rgba(196,168,87,0.7);font-size:7.5px;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:2px;">Instituto Superior Tecnológico Público Mayor Pedro Traversari</div>
                        <div style="color:rgba(255,255,255,0.40);font-size:6.5px;font-style:italic;">Excelencia Académica · Atrévete a cambiar el mundo</div>
                    </td>
                    <td style="vertical-align:middle;text-align:right;">
                        <div style="color:rgba(255,255,255,0.30);font-size:6.5px;">Documento generado automáticamente<br>por el Sistema de Carnetización ISTPET</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

</table>
</body>
</html>
