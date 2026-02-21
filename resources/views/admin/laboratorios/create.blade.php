@extends('layouts.app')

@section('title', 'Crear Laboratorio')

@section('content')
<div class="container-fluid py-4">


    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #1a2342;">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Laboratorio
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

                    <form action="{{ route('admin.laboratorios.store') }}" method="POST">
                        @csrf

                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text"
                                name="nombre"
                                class="form-control @error('nombre') is-invalid @enderror"
                                value="{{ old('nombre') }}"
                                placeholder="Ej: Laboratorio de Redes"
                                required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tipo --}}
                        <div class="mb-3">
                            <label class="form-label">Tipo *</label>
                            <select name="tipo"
                                class="form-select @error('tipo') is-invalid @enderror"
                                required>
                                <option value="">Seleccione el tipo</option>
                                <option value="laboratorio" {{ old('tipo') == 'laboratorio' ? 'selected' : '' }}>
                                    🔬 Laboratorio Técnico
                                </option>
                                <option value="aula_interactiva" {{ old('tipo') == 'aula_interactiva' ? 'selected' : '' }}>
                                    📚 Aula Interactiva
                                </option>
                            </select>
                            <small class="text-muted">
                                Laboratorios: Para prácticas técnicas con equipos especializados<br>
                                Aulas Interactivas: Para clases teóricas con tecnología multimedia
                            </small>
                            @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ubicación --}}
                        <div class="mb-3">
                            <label class="form-label">Ubicación *</label>
                            <input type="text"
                                name="ubicacion"
                                class="form-control @error('ubicacion') is-invalid @enderror"
                                value="{{ old('ubicacion') }}"
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
                                value="{{ old('capacidad', 30) }}"
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
                                placeholder="Descripción del laboratorio, equipamiento, etc.">{{ old('descripcion') }}</textarea>
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
                                <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="mantenimiento" {{ old('estado') == 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                            </select>
                            @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.laboratorios.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn text-white" style="background: #1a2342;">
                                <i class="bi bi-save me-2"></i>Guardar Laboratorio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection