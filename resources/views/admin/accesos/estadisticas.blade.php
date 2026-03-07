@extends('layouts.app')

@section('title', 'Estadísticas de Accesos')

@section('content')
<div class="container-fluid py-4">

    {{-- ── HEADER ── --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <div>
            <h2 class="mb-1" style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">
                <i class="bi bi-graph-up me-2" style="color:var(--istpet-dorado);"></i>Estadísticas de Accesos
            </h2>
            <p class="text-muted mb-0" style="font-size:0.88rem;">Análisis de uso de laboratorios y asistencia estudiantil</p>
        </div>
        <a href="{{ route('admin.accesos.index') }}"
           class="btn btn-sm" style="background:var(--istpet-azul);color:#fff;">
            <i class="bi bi-arrow-left me-1"></i>Ver Registros
        </a>
    </div>
    <div class="mb-4" style="height:3px;background:linear-gradient(90deg,var(--istpet-dorado) 0%,var(--istpet-azul) 60%,transparent 100%);border-radius:2px;"></div>

    {{-- ── STATS CARDS ── --}}
    <div class="row g-3 mb-4">
        <div class="col-xl col-md-4">
            <div class="card text-center stat-card" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
                <div class="card-body py-3">
                    <i class="bi bi-collection" style="font-size:1.4rem;color:var(--istpet-dorado);"></i>
                    <div style="font-size:1.9rem;font-weight:700;color:var(--istpet-azul);font-family:'Oswald',sans-serif;line-height:1.1;margin-top:4px;">
                        {{ number_format($stats['total_accesos']) }}
                    </div>
                    <div style="font-size:0.72rem;color:#777;text-transform:uppercase;letter-spacing:0.5px;">Total histórico</div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4">
            <div class="card text-center stat-card" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
                <div class="card-body py-3">
                    <i class="bi bi-circle-fill" style="font-size:1.4rem;color:#198754;animation:pulse 1.5s infinite;"></i>
                    <div style="font-size:1.9rem;font-weight:700;color:#198754;font-family:'Oswald',sans-serif;line-height:1.1;margin-top:4px;">
                        {{ $stats['activos_ahora'] }}
                    </div>
                    <div style="font-size:0.72rem;color:#777;text-transform:uppercase;letter-spacing:0.5px;">En labs ahora</div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4">
            <div class="card text-center stat-card" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
                <div class="card-body py-3">
                    <i class="bi bi-calendar-day" style="font-size:1.4rem;color:var(--istpet-dorado);"></i>
                    <div style="font-size:1.9rem;font-weight:700;color:var(--istpet-azul);font-family:'Oswald',sans-serif;line-height:1.1;margin-top:4px;">
                        {{ $stats['accesos_hoy'] }}
                    </div>
                    <div style="font-size:0.72rem;color:#777;text-transform:uppercase;letter-spacing:0.5px;">Accesos hoy</div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4">
            <div class="card text-center stat-card" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
                <div class="card-body py-3">
                    <i class="bi bi-calendar-month" style="font-size:1.4rem;color:var(--istpet-dorado);"></i>
                    <div style="font-size:1.9rem;font-weight:700;color:var(--istpet-azul);font-family:'Oswald',sans-serif;line-height:1.1;margin-top:4px;">
                        {{ number_format($stats['accesos_mes']) }}
                    </div>
                    <div style="font-size:0.72rem;color:#777;text-transform:uppercase;letter-spacing:0.5px;">
                        Este mes ({{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }})
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4">
            <div class="card text-center stat-card" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
                <div class="card-body py-3">
                    <i class="bi bi-people" style="font-size:1.4rem;color:var(--istpet-dorado);"></i>
                    <div style="font-size:1.9rem;font-weight:700;color:var(--istpet-azul);font-family:'Oswald',sans-serif;line-height:1.1;margin-top:4px;">
                        {{ $stats['estudiantes_unicos'] }}
                    </div>
                    <div style="font-size:0.72rem;color:#777;text-transform:uppercase;letter-spacing:0.5px;">Estudiantes únicos</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── GRÁFICA 30 DÍAS ── --}}
    <div class="card mb-4" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
        <div class="card-header" style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
            <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                <i class="bi bi-bar-chart me-2" style="color:var(--istpet-dorado);"></i>Accesos — Últimos 30 Días
            </h6>
        </div>
        <div class="card-body" style="height:260px;">
            <canvas id="chartDias"></canvas>
        </div>
    </div>

    {{-- ── GRÁFICA HORAS PICO + TOP LABS ── --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card h-100" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
                <div class="card-header" style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
                    <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-clock me-2" style="color:var(--istpet-dorado);"></i>Horas Pico del Día
                        <small style="font-size:0.7rem;opacity:0.6;font-weight:400;"> — ¿Cuándo usan más los labs?</small>
                    </h6>
                </div>
                <div class="card-body" style="height:260px;">
                    <canvas id="chartHoras"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
                <div class="card-header" style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
                    <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-building me-2" style="color:var(--istpet-dorado);"></i>Top 5 Laboratorios
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($topLaboratorios->count() > 0)
                    @php $maxLab = $topLaboratorios->first()->total_accesos; @endphp
                    @foreach($topLaboratorios as $i => $item)
                    <div class="px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width:22px;height:22px;background:var(--istpet-azul);color:var(--istpet-dorado);border-radius:50%;font-size:0.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">{{ $i+1 }}</span>
                                <strong style="color:var(--istpet-azul);font-size:0.88rem;">{{ $item->laboratorio->nombre }}</strong>
                            </div>
                            <span class="badge" style="background:rgba(196,168,87,0.15);color:var(--istpet-azul);border:1px solid rgba(196,168,87,0.4);font-size:0.75rem;">
                                {{ number_format($item->total_accesos) }}
                            </span>
                        </div>
                        <div class="progress" style="height:6px;background:rgba(34,44,87,0.08);border-radius:3px;">
                            <div class="progress-bar" role="progressbar"
                                 style="width:{{ ($item->total_accesos / max($maxLab,1)) * 100 }}%;background:linear-gradient(90deg,var(--istpet-dorado),var(--istpet-azul));border-radius:3px;">
                            </div>
                        </div>
                        @if($item->laboratorio->ubicacion)
                        <small class="text-muted" style="font-size:0.72rem;">
                            <i class="bi bi-geo-alt me-1"></i>{{ $item->laboratorio->ubicacion }}
                        </small>
                        @endif
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-5 text-muted">Sin datos</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── TOP ESTUDIANTES: HISTÓRICO + MES ACTUAL ── --}}
    <div class="row g-4">
        {{-- Top 10 histórico --}}
        <div class="col-lg-7">
            <div class="card" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
                <div class="card-header d-flex justify-content-between align-items-center"
                     style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
                    <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-trophy me-2" style="color:var(--istpet-dorado);"></i>Top 10 Estudiantes Más Activos
                        <small style="font-size:0.7rem;opacity:0.6;font-weight:400;"> — Histórico</small>
                    </h6>
                    <span class="badge" style="background:rgba(196,168,87,0.2);color:var(--istpet-dorado);font-size:0.7rem;">
                        Para reconocimientos
                    </span>
                </div>
                <div class="card-body p-0">
                    @if($topEstudiantes->count() > 0)
                    @php $maxEst = $topEstudiantes->first()->total_accesos; @endphp
                    @foreach($topEstudiantes as $i => $item)
                    <div class="px-4 py-2 d-flex align-items-center gap-3 {{ !$loop->last ? 'border-bottom' : '' }}"
                         style="{{ $i < 3 ? 'background:rgba(196,168,87,0.04);' : '' }}">
                        {{-- Medalla --}}
                        <div style="width:28px;text-align:center;flex-shrink:0;">
                            @if($i === 0)
                            <span style="font-size:1.2rem;">🥇</span>
                            @elseif($i === 1)
                            <span style="font-size:1.2rem;">🥈</span>
                            @elseif($i === 2)
                            <span style="font-size:1.2rem;">🥉</span>
                            @else
                            <span style="font-size:0.78rem;color:#999;font-weight:700;">{{ $i+1 }}</span>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="flex-grow-1" style="min-width:0;">
                            <div style="font-weight:700;color:var(--istpet-azul);font-size:0.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $item->usuario->nombreCompleto }}
                            </div>
                            <div style="font-size:0.72rem;color:#888;">{{ $item->usuario->cedula }}</div>
                            <div class="progress mt-1" style="height:4px;background:rgba(34,44,87,0.08);border-radius:2px;">
                                <div style="width:{{ ($item->total_accesos / max($maxEst,1)) * 100 }}%;background:{{ $i < 3 ? 'var(--istpet-dorado)' : 'var(--istpet-azul)' }};height:4px;border-radius:2px;"></div>
                            </div>
                        </div>
                        {{-- Contador --}}
                        <div style="text-align:right;flex-shrink:0;">
                            <span style="font-size:1.1rem;font-weight:700;color:var(--istpet-azul);font-family:'Oswald',sans-serif;">
                                {{ $item->total_accesos }}
                            </span>
                            <div style="font-size:0.65rem;color:#999;text-transform:uppercase;">accesos</div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-5 text-muted">Sin datos</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top 5 este mes --}}
        <div class="col-lg-5">
            <div class="card" style="border:none;box-shadow:0 2px 10px rgba(0,0,0,0.08);">
                <div class="card-header" style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
                    <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-star me-2" style="color:var(--istpet-dorado);"></i>Más Activos Este Mes
                        <small style="font-size:0.7rem;opacity:0.6;font-weight:400;">
                            ({{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM YYYY') }})
                        </small>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($topMes->count() > 0)
                    @foreach($topMes as $i => $item)
                    <div class="px-4 py-3 d-flex align-items-center gap-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div style="width:32px;height:32px;background:{{ $i === 0 ? 'var(--istpet-dorado)' : 'rgba(34,44,87,0.08)' }};border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="font-size:0.8rem;font-weight:700;color:{{ $i === 0 ? 'var(--istpet-azul)' : 'var(--istpet-azul)' }};">{{ $i+1 }}</span>
                        </div>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div style="font-weight:700;color:var(--istpet-azul);font-size:0.86rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $item->usuario->nombreCompleto }}
                            </div>
                            <div style="font-size:0.72rem;color:#888;">{{ $item->usuario->ciclo_nivel }}</div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <span style="font-size:1.1rem;font-weight:700;color:{{ $i === 0 ? '#C4A857' : 'var(--istpet-azul)' }};font-family:'Oswald',sans-serif;">
                                {{ $item->total_mes }}
                            </span>
                            <div style="font-size:0.62rem;color:#999;text-transform:uppercase;">este mes</div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-5 text-muted" style="font-size:0.88rem;">
                        <i class="bi bi-calendar-x d-block mb-2" style="font-size:2rem;opacity:0.3;"></i>
                        Sin accesos este mes
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.3; }
}
</style>
@endsection

@section('scripts')
<script>
// ── GRÁFICA 30 DÍAS ──────────────────────────────────────────
(function () {
    const labels30 = @json(array_column($accesosPorDia, 'fecha'));
    const data30   = @json(array_column($accesosPorDia, 'total'));

    new Chart(document.getElementById('chartDias'), {
        type: 'bar',
        data: {
            labels: labels30,
            datasets: [{
                label: 'Accesos',
                data: data30,
                backgroundColor: 'rgba(196,168,87,0.75)',
                borderColor: '#C4A857',
                borderWidth: 1,
                borderRadius: 4,
                hoverBackgroundColor: '#C4A857',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(34,44,87,0.92)',
                    titleFont: { size: 12 },
                    bodyFont: { size: 13, weight: 'bold' },
                    padding: 10,
                    callbacks: {
                        label: ctx => ' ' + ctx.parsed.y + ' accesos'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, callback: v => Number.isInteger(v) ? v : '' },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    ticks: { font: { size: 10 }, maxRotation: 45 },
                    grid: { display: false }
                }
            }
        }
    });
})();

// ── GRÁFICA HORAS PICO ───────────────────────────────────────
(function () {
    const horasData = @json(array_values($accesosPorHora));
    const horasLabels = Array.from({length: 24}, (_, i) => i.toString().padStart(2,'0') + ':00');
    const maxVal = Math.max(...horasData);

    const colores = horasData.map(v => {
        if (v === maxVal && v > 0) return 'rgba(196,168,87,0.9)';   // hora pico: dorado
        if (v > maxVal * 0.6)      return 'rgba(34,44,87,0.75)';    // alta: azul
        return 'rgba(34,44,87,0.3)';                                  // baja: gris
    });

    new Chart(document.getElementById('chartHoras'), {
        type: 'bar',
        data: {
            labels: horasLabels,
            datasets: [{
                label: 'Accesos',
                data: horasData,
                backgroundColor: colores,
                borderRadius: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(34,44,87,0.92)',
                    callbacks: {
                        title: ctx => 'Hora: ' + ctx[0].label,
                        label: ctx => ' ' + ctx.parsed.y + ' accesos'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, callback: v => Number.isInteger(v) ? v : '' },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    ticks: { font: { size: 9 }, maxRotation: 45 },
                    grid: { display: false }
                }
            }
        }
    });
})();
</script>
@endsection
