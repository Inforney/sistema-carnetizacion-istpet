@extends('layouts.app')

@section('title', 'Resultado de Actualización de Niveles')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <div>
            <h2 class="mb-1" style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">
                <i class="bi bi-check2-circle me-2" style="color:var(--istpet-dorado);"></i>Resultado de la Actualización
            </h2>
            <p class="text-muted mb-0" style="font-size:0.88rem;">Resumen del proceso de actualización masiva de niveles</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.importacion.actualizar-niveles') }}"
               class="btn btn-sm" style="background:var(--istpet-dorado);color:var(--istpet-azul);font-weight:700;">
                <i class="bi bi-arrow-repeat me-1"></i>Nueva Actualización
            </a>
            <a href="{{ route('admin.estudiantes.index') }}"
               class="btn btn-sm" style="background:var(--istpet-azul);color:#fff;">
                <i class="bi bi-people me-1"></i>Ver Estudiantes
            </a>
        </div>
    </div>
    <div class="mb-4" style="height:3px;background:linear-gradient(90deg,var(--istpet-dorado) 0%,var(--istpet-azul) 60%,transparent 100%);border-radius:2px;"></div>

    {{-- Resumen estadístico --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-center" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);border-top:4px solid #198754;">
                <div class="card-body py-3">
                    <div style="font-size:2.2rem;font-weight:700;color:#198754;font-family:'Oswald',sans-serif;">
                        {{ count($resultados['actualizados']) }}
                    </div>
                    <div style="font-size:0.82rem;color:#555;text-transform:uppercase;letter-spacing:0.5px;">Actualizados</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);border-top:4px solid #dc3545;">
                <div class="card-body py-3">
                    <div style="font-size:2.2rem;font-weight:700;color:#dc3545;font-family:'Oswald',sans-serif;">
                        {{ count($resultados['no_encontrados']) }}
                    </div>
                    <div style="font-size:0.82rem;color:#555;text-transform:uppercase;letter-spacing:0.5px;">No Encontrados</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);border-top:4px solid var(--istpet-dorado);">
                <div class="card-body py-3">
                    <div style="font-size:2.2rem;font-weight:700;color:var(--istpet-azul);font-family:'Oswald',sans-serif;">
                        {{ count($resultados['actualizados']) + count($resultados['no_encontrados']) }}
                    </div>
                    <div style="font-size:0.82rem;color:#555;text-transform:uppercase;letter-spacing:0.5px;">Total Procesadas</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de actualizados --}}
    @if(count($resultados['actualizados']) > 0)
    <div class="card mb-4" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background:#198754;border-bottom:2px solid var(--istpet-dorado);">
            <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                <i class="bi bi-check-circle me-2"></i>
                Estudiantes Actualizados ({{ count($resultados['actualizados']) }})
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" style="font-size:0.84rem;">
                    <thead style="background:rgba(34,44,87,0.06);">
                        <tr>
                            <th class="ps-3">Fila</th>
                            <th>Estudiante</th>
                            <th>Cédula</th>
                            <th>Nivel Anterior</th>
                            <th>Nivel Nuevo</th>
                            <th>Carrera</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultados['actualizados'] as $item)
                        <tr>
                            <td class="ps-3 text-muted">{{ $item['fila'] }}</td>
                            <td><strong style="color:var(--istpet-azul);">{{ $item['nombre'] }}</strong></td>
                            <td><code>{{ $item['cedula'] }}</code></td>
                            <td>
                                <span class="badge bg-secondary" style="font-size:0.75rem;">
                                    {{ $item['ciclo_anterior'] ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background:rgba(25,135,84,0.15);color:#198754;border:1px solid rgba(25,135,84,0.3);font-size:0.75rem;">
                                    {{ $item['ciclo_nuevo'] }}
                                </span>
                            </td>
                            <td style="font-size:0.8rem;color:#555;">
                                @if($item['carrera_nueva'])
                                    <span class="text-decoration-line-through text-muted me-1" style="font-size:0.75rem;">{{ $item['carrera_anterior'] }}</span>
                                    <span style="color:#198754;">{{ $item['carrera_nueva'] }}</span>
                                @else
                                    <span class="text-muted">Sin cambio</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- No encontrados --}}
    @if(count($resultados['no_encontrados']) > 0)
    <div class="card mb-4" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background:#dc3545;border-bottom:2px solid var(--istpet-dorado);">
            <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                <i class="bi bi-exclamation-circle me-2"></i>
                Cédulas No Encontradas ({{ count($resultados['no_encontrados']) }})
            </h6>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3" style="font-size:0.84rem;">
                Estos documentos no coinciden con ningún estudiante registrado.
                Verifica que la cédula esté bien escrita o que el estudiante exista en el sistema.
            </p>
            <div class="row g-2">
                @foreach($resultados['no_encontrados'] as $item)
                <div class="col-auto">
                    <span class="badge" style="background:rgba(220,53,69,0.1);color:#dc3545;border:1px solid rgba(220,53,69,0.3);font-size:0.82rem;padding:5px 10px;">
                        <i class="bi bi-person-x me-1"></i>Fila {{ $item['fila'] }}: <code>{{ $item['cedula'] }}</code>
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Estado vacío --}}
    @if(count($resultados['actualizados']) === 0 && count($resultados['no_encontrados']) === 0)
    <div class="card" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox" style="font-size:3rem;color:#ccc;"></i>
            <p class="mt-3 text-muted">El archivo no contenía filas con datos válidos.</p>
            <a href="{{ route('admin.importacion.actualizar-niveles') }}" class="btn btn-sm"
               style="background:var(--istpet-dorado);color:var(--istpet-azul);font-weight:700;">
                Intentar de nuevo
            </a>
        </div>
    </div>
    @endif

</div>
@endsection
