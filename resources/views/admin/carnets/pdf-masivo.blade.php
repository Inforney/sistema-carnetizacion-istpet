<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carnets ISTPET - Descarga Masiva</title>
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
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .page-break { page-break-after: always; }
        /* Grid A4: 2 carnets (85.6mm) × 2 filas (135mm) con márgenes y gaps */
        .page-wrap {
            width: 210mm;
            height: 297mm;
            background: #eef0f5;
            position: relative;
        }
        /* Cada página tiene: encabezado 12mm + 2 filas + gaps + pie */
    </style>
</head>
<body>

@php
    $totalCarnets = count($carnets);
    $porPagina = 4; // 2 columnas × 2 filas
    $paginas = ceil($totalCarnets / $porPagina);
@endphp

@for($p = 0; $p < $paginas; $p++)
@php
    $inicio = $p * $porPagina;
    $fin = min($inicio + $porPagina, $totalCarnets);
    $grupoActual = $carnets->slice($inicio, $porPagina)->values();
@endphp

<table width="100%" style="width:210mm;height:297mm;border-collapse:collapse;table-layout:fixed;background:#eef0f5;" cellpadding="0" cellspacing="0">

    {{-- ══ ENCABEZADO (14mm) ══ --}}
    <tr>
        <td colspan="2" style="height:14mm;background:#222C57;padding:0;vertical-align:middle;">
            <table width="100%" cellpadding="0" cellspacing="0" style="padding:3mm 10mm;">
                <tr>
                    <td style="vertical-align:middle;">
                        <div style="width:22px;height:22px;background:#fff;border-radius:4px;display:inline-block;text-align:center;padding:2px;vertical-align:middle;margin-right:7px;">
                            <img src="{{ public_path('images/LogoISTPET.png') }}" width="18" height="18" style="display:block;object-fit:contain;">
                        </div>
                        <span style="color:#C4A857;font-size:14px;font-weight:bold;letter-spacing:2px;vertical-align:middle;">ISTPET</span>
                        <span style="color:rgba(255,255,255,0.6);font-size:7px;vertical-align:middle;margin-left:8px;">Instituto Superior Tecnológico Público Mayor Pedro Traversari · Sistema de Carnetización</span>
                    </td>
                    <td style="vertical-align:middle;text-align:right;">
                        <span style="color:rgba(196,168,87,0.7);font-size:7px;">Pág. {{ $p + 1 }} / {{ $paginas }} · {{ now()->format('d/m/Y') }}</span>
                    </td>
                </tr>
            </table>
            <div style="height:2px;background:#C4A857;"></div>
        </td>
    </tr>

    {{-- ══ FILA 1 de carnets (139mm) ══ --}}
    <tr>
        {{-- Carnet 1 --}}
        <td style="height:139mm;width:105mm;vertical-align:middle;text-align:center;padding:4mm 5mm 2mm 10mm;">
            @if(isset($grupoActual[0]))
            @php $c = $grupoActual[0]; @endphp
            @include('admin.carnets._carnet-mini', ['carnet' => $c])
            @endif
        </td>
        {{-- Carnet 2 --}}
        <td style="height:139mm;width:105mm;vertical-align:middle;text-align:center;padding:4mm 10mm 2mm 5mm;">
            @if(isset($grupoActual[1]))
            @php $c = $grupoActual[1]; @endphp
            @include('admin.carnets._carnet-mini', ['carnet' => $c])
            @endif
        </td>
    </tr>

    {{-- ══ FILA 2 de carnets (139mm) ══ --}}
    <tr>
        {{-- Carnet 3 --}}
        <td style="height:139mm;width:105mm;vertical-align:middle;text-align:center;padding:2mm 5mm 4mm 10mm;">
            @if(isset($grupoActual[2]))
            @php $c = $grupoActual[2]; @endphp
            @include('admin.carnets._carnet-mini', ['carnet' => $c])
            @endif
        </td>
        {{-- Carnet 4 --}}
        <td style="height:139mm;width:105mm;vertical-align:middle;text-align:center;padding:2mm 10mm 4mm 5mm;">
            @if(isset($grupoActual[3]))
            @php $c = $grupoActual[3]; @endphp
            @include('admin.carnets._carnet-mini', ['carnet' => $c])
            @endif
        </td>
    </tr>

    {{-- ══ PIE DE PÁGINA (5mm) ══ --}}
    <tr>
        <td colspan="2" style="height:5mm;background:#222C57;padding:0;vertical-align:middle;text-align:center;">
            <span style="color:rgba(196,168,87,0.5);font-size:6px;font-style:italic;">✂ Recortar por las líneas punteadas · Excelencia Académica · ISTPET {{ now()->year }}</span>
        </td>
    </tr>

</table>

@if($p < $paginas - 1)
<div class="page-break"></div>
@endif

@endfor

</body>
</html>
