@extends('layouts.app')

@section('title', 'Editar Laboratorio')

@section('content')
<div class="container-fluid py-4">


    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #1a2342;">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-pencil me-2"></i>Editar Laboratorio
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>¡Error!</strong> Por favor corrige los siguientes problemas:
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.laboratorios.update', $laboratorio->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label class="form-label">Nombre del Laboratorio *</label>
                            <input type="text"
                                name="nombre"
                                class="form-control @error('nombre') is-invalid @enderror"
                                value="{{ old('nombre', $laboratorio->nombre) }}"
                                placeholder="Ej: Laboratorio de Redes"
                                required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ubicación --}}
                        <div class="mb-3">
                            <label class="form-label">Ubicación *</label>
                            <input type="text"
                                name="ubicacion"
                                class="form-control @error('ubicacion') is-invalid @enderror"
                                value="{{ old('ubicacion', $laboratorio->ubicacion) }}"
                                placeholder="Ej: Edificio A, Piso 2, Aula 201"
                                required>
                            @error('ubicacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Capacidad --}}
                        <div class="mb-3">
                            <label class="form-label">Capacidad (Estudiantes) *</label>
                            <input type="number"
                                name="capacidad"
                                class="form-control @error('capacidad') is-invalid @enderror"
                                value="{{ old('capacidad', $laboratorio->capacidad) }}"
                                min="1"
                                max="100"
                                required>
                            <small class="text-muted">Máximo 100 estudiantes</small>
                            @error('capacidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-3">
                            <label class="form-label">Descripción (Opcional)</label>
                            <textarea name="descripcion"
                                class="form-control @error('descripcion') is-invalid @enderror"
                                rows="3"
                                maxlength="500"
                                placeholder="Descripción del laboratorio, equipamiento, etc.">{{ old('descripcion', $laboratorio->descripcion) }}</textarea>
                            <small class="text-muted">Máximo 500 caracteres</small>
                            @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <div class="mb-4">
                            <label class="form-label">Estado *</label>
                            <select name="estado"
                                class="form-select @error('estado') is-invalid @enderror"
                                required>
                                <option value="activo" {{ old('estado', $laboratorio->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado', $laboratorio->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="mantenimiento" {{ old('estado', $laboratorio->estado) == 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                            </select>
                            @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Código QR (Solo lectura) --}}
                        <div class="mb-4">
                            <label class="form-label">Código QR</label>
                            <input type="text"
                                class="form-control"
                                value="{{ $laboratorio->codigo_qr_lab }}"
                                readonly>
                            <small class="text-muted">El código QR no se puede modificar</small>
                        </div>

                        {{-- Botones --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.laboratorios.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn text-white" style="background: #1a2342;">
                                <i class="bi bi-save me-2"></i>Actualizar Laboratorio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection