<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carnets ISTPET - Impresión Masiva</title>
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
    </style>
</head>
<body>

@php
    $lista = collect($carnetes);
    $total = $lista->count();
    $porPagina = 4;
    $paginas = ceil($total / $porPagina);
@endphp

@for($p = 0; $p < $paginas; $p++)
@php
    $inicio = $p * $porPagina;
    $grupo = $lista->slice($inicio, $porPagina)->values();
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

    {{-- ══ FILA 1 (139mm) ══ --}}
    <tr>
        <td style="height:139mm;width:105mm;vertical-align:middle;text-align:center;padding:4mm 5mm 2mm 10mm;">
            @if(isset($grupo[0]))
            @php $item = $grupo[0]; @endphp
            @include('carnets._carnet-mini-item', ['item' => $item])
            @endif
        </td>
        <td style="height:139mm;width:105mm;vertical-align:middle;text-align:center;padding:4mm 10mm 2mm 5mm;">
            @if(isset($grupo[1]))
            @php $item = $grupo[1]; @endphp
            @include('carnets._carnet-mini-item', ['item' => $item])
            @endif
        </td>
    </tr>

    {{-- ══ FILA 2 (139mm) ══ --}}
    <tr>
        <td style="height:139mm;width:105mm;vertical-align:middle;text-align:center;padding:2mm 5mm 4mm 10mm;">
            @if(isset($grupo[2]))
            @php $item = $grupo[2]; @endphp
            @include('carnets._carnet-mini-item', ['item' => $item])
            @endif
        </td>
        <td style="height:139mm;width:105mm;vertical-align:middle;text-align:center;padding:2mm 10mm 4mm 5mm;">
            @if(isset($grupo[3]))
            @php $item = $grupo[3]; @endphp
            @include('carnets._carnet-mini-item', ['item' => $item])
            @endif
        </td>
    </tr>

    {{-- ══ PIE (5mm) ══ --}}
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
