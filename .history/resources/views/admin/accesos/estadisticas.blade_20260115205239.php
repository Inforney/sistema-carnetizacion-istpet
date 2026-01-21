@extends('layouts.app')

@section('title', 'Estadísticas de Accesos')

@section('content')
<div class="container-fluid py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.accesos.index') }}">Accesos</a>
            </li>
            <li class="breadcrumb-item active">Estadísticas</li>
        </ol>
    </nav>

    <h2 class="mb-4">
        <i class="bi bi-graph-up me-2"></i>Estadísticas de Accesos
    </h2>

    {{-- Estadísticas generales --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted small">TOTAL ACCESOS</h6>
                    <h2 class="mb-0">{{ $stats['total_accesos'] }}</h2>
                    <small class="text-muted">Desde el inicio</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted small">ACCESOS HOY</h6>
                    <h2 class="mb-0">{{ $stats['accesos_hoy'] }}</h2>
                    <small class="text-success">Registros de hoy</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-info border-4">
                <div class="card-body">
                    <h6 class="text-muted small">ACCESOS ESTE MES</h6>
                    <h2 class="mb-0">{{ $stats['accesos_mes'] }}</h2>
                    <small class="text-muted">{{ \Carbon\Carbon::now()->format('F Y') }}</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted small">ESTUDIANTES ACTIVOS</h6>
                    <h2 class="mb-0">{{ $stats['estudiantes_activos'] }}</h2>
                    <small class="text-warning">En labs ahora</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Top Estudiantes --}}
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy me-2"></i>Top 5 Estudiantes Más Activos
                    </h5>
                </div>
                <div class="card-body">
                    @if($topEstudiantes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($topEstudiantes as $index => $item)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary rounded-pill me-2">{{ $index + 1 }}</span>
                                <strong>{{ $item->usuario->nombreCompleto }}</strong>
                                <br>
                                <small class="text-muted">{{ $item->usuario->cedula }}</small>
                            </div>
                            <span class="badge bg-success rounded-pill">
                                {{ $item->total_accesos }} accesos
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center py-4">No hay datos disponibles</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top Laboratorios --}}
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>Laboratorios Más Utilizados
                    </h5>
                </div>
                <div class="card-body">
                    @if($topLaboratorios->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($topLaboratorios as $index => $item)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ $item->laboratorio->nombre }}</strong>
                                <span class="badge bg-info">{{ $item->total_accesos }} accesos</span>
                            </div>
                            @php
                            $porcentaje = ($item->total_accesos / $stats['total_accesos']) * 100;
                            @endphp
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-info"
                                    role="progressbar"
                                    style="width: {{ $porcentaje }}%">
                                    {{ round($porcentaje, 1) }}%
                                </div>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-geo-alt"></i> {{ $item->laboratorio->ubicacion }}
                            </small>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center py-4">No hay datos disponibles</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Gráfica de accesos por día --}}
    <div class="card">
        <div class="card-header" style="background: #1a2342;">
            <h5 class="mb-0 text-white">
                <i class="bi bi-bar-chart me-2"></i>Accesos de los Últimos 7 Días
            </h5>
        </div>
        <div class="card-body" style="min-height: 400px;">
            <canvas id="chartAccesos"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Debug: Ver datos
    console.log('Datos de accesos por día:', @json($accesosPorDia));

    const ctx = document.getElementById('chartAccesos');

    const labels = @json(array_column($accesosPorDia, 'fecha'));
    const data = @json(array_column($accesosPorDia, 'total'));

    console.log('Labels:', labels);
    console.log('Data:', data);

    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Número de Accesos',
                    data: data,
                    backgroundColor: 'rgba(196, 168, 87, 0.8)',
                    borderColor: '#C4A857',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(196, 168, 87, 1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return Math.floor(value);
                            },
                            font: {
                                size: 14
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        title: {
                            display: true,
                            text: 'Cantidad de Accesos',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 14
                            }
                        },
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Fecha',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 35, 66, 0.9)',
                        titleFont: {
                            size: 16
                        },
                        bodyFont: {
                            size: 14
                        },
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return 'Accesos: ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });
    } else {
        console.error('No se encontró el elemento canvas con id "chartAccesos"');
    }
</script>
@endsection