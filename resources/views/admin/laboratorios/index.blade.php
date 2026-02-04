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

    {{-- Filtros por tipo --}}
    <div class="row mb-3">
        <div class="col-12">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="pill" href="#todos">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Todos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#laboratorios">
                        <i class="bi bi-cpu me-2"></i>Laboratorios Técnicos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#aulas">
                        <i class="bi bi-projector me-2"></i>Aulas Interactivas
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Contenido por pestañas --}}
    <div class="tab-content">
        {{-- Todos --}}
        <div class="tab-pane fade show active" id="todos">
            <div class="row">
                @forelse($laboratorios as $lab)
                @include('admin.laboratorios.partials.card-laboratorio', ['lab' => $lab])
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        No hay laboratorios o aulas registradas.
                        <a href="{{ route('admin.laboratorios.create') }}" class="alert-link">Crear el primero</a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Solo Laboratorios --}}
        <div class="tab-pane fade" id="laboratorios">
            <div class="row">
                @forelse($laboratorios->where('tipo', 'laboratorio') as $lab)
                @include('admin.laboratorios.partials.card-laboratorio', ['lab' => $lab])
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        No hay laboratorios técnicos registrados.
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Solo Aulas Interactivas --}}
        <div class="tab-pane fade" id="aulas">
            <div class="row">
                @forelse($laboratorios->where('tipo', 'aula_interactiva') as $lab)
                @include('admin.laboratorios.partials.card-laboratorio', ['lab' => $lab])
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        No hay aulas interactivas registradas.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $laboratorios->links() }}
    </div>
</div>
@endsection