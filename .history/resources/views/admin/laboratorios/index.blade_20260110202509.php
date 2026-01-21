@extends('layouts.app')

@section('title', 'Gestión de Laboratorios')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>
                <i class="bi bi-building me-2"></i>Gestión de Laboratorios
            </h2>
            <p class="text-muted mb-0">Administra los códigos QR de los laboratorios</p>
        </div>
        <a href="{{ route('admin.laboratorios.descargar-qr-todos') }}" class="btn btn-primary">
            <i class="bi bi-download me-2"></i>Descargar Todos los QR
        </a>
    </div>

    <div class="row">
        @foreach($laboratorios as $lab)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $lab->nombre }}</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        {!! QrCode::size(200)->generate($lab->codigo_qr_lab) !!}
                    </div>

                    <div class="mb-2">
                        <small class="text-muted">CÓDIGO QR:</small>
                        <p class="mb-0"><code>{{ $lab->codigo_qr_lab }}</code></p>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted">UBICACIÓN:</small>
                        <p class="mb-0">{{ $lab->ubicacion }}</p>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">CAPACIDAD:</small>
                            <p class="mb-0"><strong>{{ $lab->capacidad }}</strong> estudiantes</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">OCUPACIÓN ACTUAL:</small>
                            <p class="mb-0">
                                <span class="badge bg-{{ $lab->accesos_count > 0 ? 'success' : 'secondary' }}">
                                    {{ $lab->accesos_count }}/{{ $lab->capacidad }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid">
                        <a href="{{ route('admin.laboratorios.generar-qr', $lab->id) }}"
                            class="btn btn-outline-primary">
                            <i class="bi bi-download me-2"></i>Descargar QR Individual
                        </a>
                    </div>
                </div>
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Imprime y pega en la puerta del laboratorio
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection