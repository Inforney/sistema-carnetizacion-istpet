@extends('layouts.app')

@section('title', 'Resultado de Importación')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-check-circle me-2"></i>Resultado de Importación</h2>
            <p class="text-muted mb-0">Resumen del proceso de importación masiva</p>
        </div>
        <div>
            <a href="{{ route('admin.importacion.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-repeat me-2"></i>Nueva Importación
            </a>
            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-people me-2"></i>Ver Estudiantes
            </a>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle text-success" style="font-size: 48px;"></i>
                    <h3 class="mt-2 mb-0 text-success">{{ count($resultados['exitosos']) }}</h3>
                    <p class="text-muted mb-0">Exitosos</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle text-danger" style="font-size: 48px;"></i>
                    <h3 class="mt-2 mb-0 text-danger">{{ count($resultados['errores']) }}</h3>
                    <p class="text-muted mb-0">Errores</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 48px;"></i>
                    <h3 class="mt-2 mb-0 text-warning">{{ count($resultados['duplicados']) }}</h3>
                    <p class="text-muted mb-0">Duplicados</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="bi bi-credit-card text-info" style="font-size: 48px;"></i>
                    <h3 class="mt-2 mb-0 text-info">{{ $resultados['carnets_generados'] }}</h3>
                    <p class="text-muted mb-0">Carnets Generados</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Mensaje de resumen --}}
    @if(count($resultados['exitosos']) > 0)
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-2"></i>
        <strong>¡Importación completada!</strong>
        Se importaron exitosamente {{ count($resultados['exitosos']) }} estudiantes.
        @if($resultados['carnets_generados'] > 0)
        Se generaron {{ $resultados['carnets_generados'] }} carnets digitales.
        @endif
    </div>
    @endif

    {{-- Estudiantes Exitosos --}}
    @if(count($resultados['exitosos']) > 0)
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="bi bi-check-circle me-2"></i>
                Estudiantes Importados Exitosamente ({{ count($resultados['exitosos']) }})
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Fila</th>
                            <th>Nombre Completo</th>
                            <th>Cédula</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultados['exitosos'] as $exitoso)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $exitoso['fila'] }}</span></td>
                            <td>{{ $exitoso['nombre'] }}</td>
                            <td>{{ $exitoso['cedula'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-success">
                                    <i class="bi bi-check"></i> Importado
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Registros Duplicados --}}
    @if(count($resultados['duplicados']) > 0)
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Registros Duplicados - Ya existen en el sistema ({{ count($resultados['duplicados']) }})
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Fila</th>
                            <th>Nombre</th>
                            <th>Cédula</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultados['duplicados'] as $duplicado)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $duplicado['fila'] }}</span></td>
                            <td>{{ $duplicado['nombre'] }}</td>
                            <td>{{ $duplicado['cedula'] }}</td>
                            <td><span class="badge bg-warning text-dark">Ya existe en BD</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Errores --}}
    @if(count($resultados['errores']) > 0)
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="bi bi-x-circle me-2"></i>
                Registros con Errores ({{ count($resultados['errores']) }})
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Fila</th>
                            <th>Datos</th>
                            <th>Errores Encontrados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultados['errores'] as $error)
                        <tr>
                            <td><span class="badge bg-danger">{{ $error['fila'] }}</span></td>
                            <td>
                                @if(!empty($error['datos']))
                                <strong>{{ $error['datos']['nombres'] ?? 'N/A' }} {{ $error['datos']['apellidos'] ?? '' }}</strong><br>
                                <small class="text-muted">CI: {{ $error['datos']['cedula'] ?? 'N/A' }}</small>
                                @else
                                <em class="text-muted">Datos no disponibles</em>
                                @endif
                            </td>
                            <td>
                                <ul class="mb-0 text-danger">
                                    @foreach($error['errores'] as $mensajeError)
                                    <li>{{ $mensajeError }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Sin errores --}}
    @if(count($resultados['errores']) == 0 && count($resultados['duplicados']) == 0)
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        ¡Perfecto! Todos los registros se importaron sin problemas.
    </div>
    @endif

    {{-- Acciones finales --}}
    <div class="text-center mt-4">
        <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-lg btn-primary">
            <i class="bi bi-people me-2"></i>Ver Lista de Estudiantes
        </a>
        @if($resultados['carnets_generados'] > 0)
        <a href="{{ route('admin.carnets.index') }}" class="btn btn-lg btn-outline-success">
            <i class="bi bi-credit-card me-2"></i>Ver Carnets Generados
        </a>
        @endif
    </div>
</div>
@endsection