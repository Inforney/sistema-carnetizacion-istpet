@extends('layouts.app')

@section('title', 'Gestión de Laboratorios')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>
                <i class="bi bi-building me-2"></i>Gestión de Laboratorios
            </h2>
            <p class="text-muted mb-0">Administra los laboratorios y genera códigos QR</p>
        </div>
        <div>
            <a href="{{ route('admin.laboratorios.create') }}" class="btn text-white me-2" style="background: #1a2342;">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Laboratorio
            </a>
            <a href="{{ route('admin.laboratorios.descargar-qr-todos') }}" class="btn btn-success">
                <i class="bi bi-download me-2"></i>Descargar Todos los QR
            </a>
        </div>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Tarjetas de laboratorios --}}
    <div class="row">
        @forelse($laboratorios as $lab)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: #1a2342;">
                    <h5 class="mb-0 text-white">{{ $lab->nombre }}</h5>
                    <span class="badge bg-{{ $lab->estado === 'activo' ? 'success' : ($lab->estado === 'mantenimiento' ? 'warning' : 'danger') }}">
                        {{ ucfirst($lab->estado) }}
                    </span>
                </div>
                <div class="card-body">
                    {{-- QR Code --}}
                    <div class="text-center mb-3">
                        {!! QrCode::size(180)->generate($lab->codigo_qr_lab) !!}
                    </div>

                    {{-- Información --}}
                    <div class="mb-2">
                        <small class="text-muted"><i class="bi bi-qr-code me-1"></i>CÓDIGO:</small>
                        <p class="mb-0 small"><code>{{ $lab->codigo_qr_lab }}</code></p>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>UBICACIÓN:</small>
                        <p class="mb-0">{{ $lab->ubicacion }}</p>
                    </div>

                    @if($lab->descripcion)
                    <div class="mb-2">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>DESCRIPCIÓN:</small>
                        <p class="mb-0 small">{{ $lab->descripcion }}</p>
                    </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-6">
                            <small class="text-muted">CAPACIDAD:</small>
                            <p class="mb-0"><strong>{{ $lab->capacidad }}</strong> estudiantes</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">OCUPACIÓN:</small>
                            <p class="mb-0">
                                <span class="badge bg-{{ $lab->accesos_count > 0 ? 'success' : 'secondary' }}">
                                    {{ $lab->accesos_count }}/{{ $lab->capacidad }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between gap-2">
                        <a href="{{ route('admin.laboratorios.generar-qr', $lab->id) }}"
                            class="btn btn-sm btn-outline-success flex-fill"
                            title="Descargar QR">
                            <i class="bi bi-download"></i> QR
                        </a>
                        <a href="{{ route('admin.laboratorios.edit', $lab->id) }}"
                            class="btn btn-sm btn-outline-primary flex-fill"
                            title="Editar">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <form action="{{ route('admin.laboratorios.destroy', $lab->id) }}"
                            method="POST"
                            class="flex-fill"
                            onsubmit="return confirm('¿Estás seguro de eliminar este laboratorio?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="btn btn-sm btn-outline-danger w-100"
                                title="Eliminar">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>
                No hay laboratorios registrados.
                <a href="{{ route('admin.laboratorios.create') }}" class="alert-link">Crear el primero</a>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $laboratorios->links() }}
    </div>
</div>
@endsection