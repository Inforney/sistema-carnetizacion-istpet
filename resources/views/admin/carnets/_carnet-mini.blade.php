{{-- Partial: mini carnet para impresión masiva en A4 --}}
{{-- Se usa dentro de pdf-masivo.blade.php --}}
{{-- Variables: $carnet (objeto Carnet con usuario cargado) --}}

<table width="85.6mm" style="width:85.6mm;height:135mm;border-collapse:collapse;table-layout:fixed;box-shadow:0 2mm 8mm rgba(0,0,0,0.3);" cellpadding="0" cellspacing="0">

    {{-- Header Navy (21mm) --}}
    <tr>
        <td style="height:21mm;background-color:#222C57;padding:5px 8px 4px 8px;vertical-align:middle;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="vertical-align:middle;width:74%;">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="vertical-align:middle;padding-right:5px;width:28px;">
                                    <div style="width:22px;height:22px;background:#fff;border-radius:4px;text-align:center;padding:2px;">
                                        <img src="{{ public_path('images/LogoISTPET.png') }}" width="18" height="18" style="display:block;object-fit:contain;">
                                    </div>
                                </td>
                                <td style="vertical-align:middle;">
                                    <div style="color:#C4A857;font-size:15px;font-weight:bold;letter-spacing:2px;line-height:1;">ISTPET</div>
                                    <div style="color:rgba(255,255,255,0.7);font-size:5.5px;line-height:1.3;margin-top:2px;">Inst. Superior Tecnológico<br>Mayor Pedro Traversari</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="vertical-align:top;text-align:right;padding-top:2px;width:26%;">
                        @if($carnet->estado === 'activo')
                        <span style="display:inline-block;background:#28a745;color:#fff;font-size:6px;font-weight:bold;padding:2px 7px;border-radius:8px;text-transform:uppercase;letter-spacing:0.5px;">ACTIVO</span>
                        @else
                        <span style="display:inline-block;background:#dc3545;color:#fff;font-size:6px;font-weight:bold;padding:2px 7px;border-radius:8px;text-transform:uppercase;letter-spacing:0.5px;">{{ strtoupper($carnet->estado) }}</span>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Barra dorada (1mm) --}}
    <tr><td style="height:1mm;background-color:#C4A857;padding:0;font-size:0;line-height:0;"></td></tr>

    {{-- Foto (33mm) --}}
    <tr>
        <td style="height:33mm;background-color:#1a2342;padding:7px 6px 5px 6px;text-align:center;vertical-align:middle;">
            @if($carnet->usuario->foto_url && file_exists(public_path($carnet->usuario->foto_url)))
            <img src="{{ public_path($carnet->usuario->foto_url) }}" width="66" height="66" style="border-radius:50%;object-fit:cover;border:3px solid #C4A857;display:inline-block;">
            @else
            <div style="width:66px;height:66px;border-radius:50%;background:#2d3d72;border:3px solid #C4A857;display:inline-block;text-align:center;">
                <img src="{{ public_path('images/LogoISTPET.png') }}" width="36" height="36" style="margin-top:12px;opacity:0.55;object-fit:contain;display:block;margin-left:auto;margin-right:auto;">
            </div>
            @endif
            <br>
            <span style="display:inline-block;background:#C4A857;color:#222C57;font-size:6px;font-weight:bold;padding:2px 10px;border-radius:8px;text-transform:uppercase;letter-spacing:1px;margin-top:5px;">Estudiante</span>
        </td>
    </tr>

    {{-- Info (52mm) --}}
    <tr>
        <td style="height:52mm;background:#fff;vertical-align:top;border-left:4px solid #C4A857;padding:7px 9px 5px 9px;">
            <div style="font-size:10.5px;font-weight:bold;color:#222C57;line-height:1.2;margin-bottom:5px;padding-bottom:4px;border-bottom:1px solid rgba(196,168,87,0.4);">{{ $carnet->usuario->nombreCompleto }}</div>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:50%;padding-right:4px;padding-bottom:4px;vertical-align:top;">
                        <div style="font-size:5.5px;color:#C4A857;text-transform:uppercase;font-weight:bold;letter-spacing:0.5px;line-height:1;margin-bottom:1px;">{{ $carnet->usuario->tipo_documento === 'pasaporte' ? 'Pasaporte' : 'Cédula de Identidad' }}</div>
                        <div style="font-size:9px;color:#222C57;font-weight:bold;line-height:1.2;">{{ $carnet->usuario->cedula }}</div>
                    </td>
                    <td style="width:50%;padding-bottom:4px;vertical-align:top;">
                        <div style="font-size:5.5px;color:#C4A857;text-transform:uppercase;font-weight:bold;letter-spacing:0.5px;line-height:1;margin-bottom:1px;">Ciclo / Nivel</div>
                        <div style="font-size:8.5px;color:#222C57;font-weight:bold;line-height:1.2;">{{ $carnet->usuario->ciclo_nivel }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-bottom:4px;vertical-align:top;">
                        <div style="font-size:5.5px;color:#C4A857;text-transform:uppercase;font-weight:bold;letter-spacing:0.5px;line-height:1;margin-bottom:1px;">Carrera</div>
                        <div style="font-size:9px;color:#222C57;font-weight:bold;line-height:1.2;">{{ $carnet->usuario->carrera ?? 'N/A' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="vertical-align:top;">
                        <div style="font-size:5.5px;color:#C4A857;text-transform:uppercase;font-weight:bold;letter-spacing:0.5px;line-height:1;margin-bottom:1px;">Correo Institucional</div>
                        <div style="font-size:7.5px;color:#222C57;font-weight:bold;line-height:1.2;">{{ $carnet->usuario->correo_institucional }}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Barra dorada (1mm) --}}
    <tr><td style="height:1mm;background-color:#C4A857;padding:0;font-size:0;line-height:0;"></td></tr>

    {{-- Footer QR (27mm) --}}
    <tr>
        <td style="height:27mm;background-color:#222C57;padding:6px 8px;vertical-align:middle;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:56px;vertical-align:middle;text-align:center;">
                        <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(130)->generate($carnet->codigo_qr)) }}" width="52" height="52" style="display:block;margin:0 auto;">
                        <div style="font-size:4.5px;color:rgba(196,168,87,0.55);margin-top:2px;text-align:center;word-break:break-all;">{{ substr($carnet->codigo_qr, 0, 18) }}</div>
                    </td>
                    <td style="padding-left:7px;vertical-align:middle;border-left:1px solid rgba(196,168,87,0.2);">
                        <div style="font-size:5px;color:rgba(196,168,87,0.7);text-transform:uppercase;letter-spacing:0.5px;line-height:1;">Fecha de Emisión</div>
                        <div style="font-size:8px;color:#fff;font-weight:bold;line-height:1.3;margin-bottom:4px;">
                            @if(is_object($carnet->fecha_emision))
                            {{ $carnet->fecha_emision->format('d/m/Y') }}
                            @else
                            {{ date('d/m/Y', strtotime($carnet->fecha_emision)) }}
                            @endif
                        </div>
                        <div style="font-size:5px;color:rgba(196,168,87,0.7);text-transform:uppercase;letter-spacing:0.5px;line-height:1;">Válido Hasta</div>
                        <div style="font-size:8px;color:#C4A857;font-weight:bold;line-height:1.3;margin-bottom:5px;">
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
                        <div style="font-size:4.5px;color:rgba(255,255,255,0.3);font-style:italic;border-top:1px solid rgba(196,168,87,0.15);padding-top:3px;">Excelencia Académica</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

</table>
