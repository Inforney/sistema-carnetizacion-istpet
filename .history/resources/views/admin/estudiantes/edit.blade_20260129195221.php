@extends('layouts.app')

@section('title', 'Editar Estudiante')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header" style="background: #1a2342;">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-pencil me-2"></i>Editar Estudiante
                    </h4>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Nota:</strong> El documento de identidad NO puede ser modificado por seguridad.
                    </div>

                    <form method="POST"
                        action="{{ route('admin.estudiantes.update', $estudiante->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Columna Izquierda: Datos --}}
                            <div class="col-lg-8">
                                {{-- Datos Personales --}}
                                <h5 class="border-bottom pb-2 mb-3">Datos Personales</h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombres *</label>
                                        <input type="text"
                                            name="nombres"
                                            class="form-control @error('nombres') is-invalid @enderror"
                                            value="{{ old('nombres', $estudiante->nombres) }}"
                                            required>
                                        @error('nombres')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Apellidos *</label>
                                        <input type="text"
                                            name="apellidos"
                                            class="form-control @error('apellidos') is-invalid @enderror"
                                            value="{{ old('apellidos', $estudiante->apellidos) }}"
                                            required>
                                        @error('apellidos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Documento (Solo lectura) --}}
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Tipo de Documento</label>
                                        <input type="text"
                                            class="form-control"
                                            value="{{ strtoupper($estudiante->tipo_documento) }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-8">
                                        <label class="form-label">Número de Documento</label>
                                        <input type="text"
                                            class="form-control"
                                            value="{{ $estudiante->cedula }}"
                                            readonly>
                                        <small class="text-muted">
                                            <i class="bi bi-lock"></i> No puede ser modificado
                                        </small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nacionalidad *</label>
                                        <select name="nacionalidad"
                                            class="form-select @error('nacionalidad') is-invalid @enderror"
                                            required>
                                            <option value="Ecuatoriana" {{ old('nacionalidad', $estudiante->nacionalidad) == 'Ecuatoriana' ? 'selected' : '' }}>Ecuatoriana</option>
                                            <option value="Colombiana" {{ old('nacionalidad', $estudiante->nacionalidad) == 'Colombiana' ? 'selected' : '' }}>Colombiana</option>
                                            <option value="Peruana" {{ old('nacionalidad', $estudiante->nacionalidad) == 'Peruana' ? 'selected' : '' }}>Peruana</option>
                                            <option value="Venezolana" {{ old('nacionalidad', $estudiante->nacionalidad) == 'Venezolana' ? 'selected' : '' }}>Venezolana</option>
                                            <option value="Otra" {{ old('nacionalidad', $estudiante->nacionalidad) == 'Otra' ? 'selected' : '' }}>Otra</option>
                                        </select>
                                        @error('nacionalidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Ciclo/Nivel *</label>
                                        <select name="ciclo_nivel"
                                            class="form-select @error('ciclo_nivel') is-invalid @enderror"
                                            required>
                                            <option value="PRIMER NIVEL" {{ old('ciclo_nivel', $estudiante->ciclo_nivel) == 'PRIMER NIVEL' ? 'selected' : '' }}>Primer Nivel</option>
                                            <option value="SEGUNDO NIVEL" {{ old('ciclo_nivel', $estudiante->ciclo_nivel) == 'SEGUNDO NIVEL' ? 'selected' : '' }}>Segundo Nivel</option>
                                            <option value="TERCER NIVEL" {{ old('ciclo_nivel', $estudiante->ciclo_nivel) == 'TERCER NIVEL' ? 'selected' : '' }}>Tercer Nivel</option>
                                            <option value="CUARTO NIVEL" {{ old('ciclo_nivel', $estudiante->ciclo_nivel) == 'CUARTO NIVEL' ? 'selected' : '' }}>Cuarto Nivel</option>
                                        </select>
                                        @error('ciclo_nivel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- NUEVO: Campo Carrera --}}
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">
                                            <i class="bi bi-mortarboard me-1"></i>Carrera *
                                        </label>
                                        <select name="carrera"
                                            class="form-select @error('carrera') is-invalid @enderror"
                                            required>
                                            <option value="">Seleccione una carrera</option>
                                            <option value="Desarrollo de Software" {{ old('carrera', $estudiante->carrera) == 'Desarrollo de Software' ? 'selected' : '' }}>Desarrollo de Software</option>
                                            <option value="Redes y Telecomunicaciones" {{ old('carrera', $estudiante->carrera) == 'Redes y Telecomunicaciones' ? 'selected' : '' }}>Redes y Telecomunicaciones</option>
                                            <option value="Seguridad Informática" {{ old('carrera', $estudiante->carrera) == 'Seguridad Informática' ? 'selected' : '' }}>Seguridad Informática</option>
                                            <option value="Administración de Sistemas" {{ old('carrera', $estudiante->carrera) == 'Administración de Sistemas' ? 'selected' : '' }}>Administración de Sistemas</option>
                                            <option value="Soporte Técnico" {{ old('carrera', $estudiante->carrera) == 'Soporte Técnico' ? 'selected' : '' }}>Soporte Técnico</option>
                                        </select>
                                        @error('carrera')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Contacto --}}
                                <h5 class="border-bottom pb-2 mb-3 mt-4">Información de Contacto</h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Correo Institucional *</label>
                                        <input type="email"
                                            name="correo_institucional"
                                            class="form-control @error('correo_institucional') is-invalid @enderror"
                                            value="{{ old('correo_institucional', $estudiante->correo_institucional) }}"
                                            required>
                                        @error('correo_institucional')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Celular *</label>
                                        <input type="text"
                                            name="celular"
                                            class="form-control @error('celular') is-invalid @enderror"
                                            value="{{ old('celular', $estudiante->celular) }}"
                                            required>
                                        @error('celular')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Columna Derecha: Foto --}}
                            <div class="col-lg-4">
                                <h5 class="border-bottom pb-2 mb-3">Foto de Perfil</h5>

                                <div class="text-center mb-3">
                                    {{-- Vista previa de foto actual --}}
                                    <div class="position-relative d-inline-block">
                                        @if($estudiante->foto_url)
                                        <img id="preview-foto"
                                            src="{{ asset($estudiante->foto_url) }}"
                                            alt="Foto actual"
                                            class="img-thumbnail rounded-circle"
                                            style="width: 180px; height: 180px; object-fit: cover;">
                                        @else
                                        <div id="preview-foto"
                                            class="img-thumbnail rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 180px; height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <span style="font-size: 64px; color: white; font-weight: bold;">
                                                {{ strtoupper(substr($estudiante->nombres, 0, 1) . substr($estudiante->apellidos, 0, 1)) }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>

                                    @if($estudiante->foto_url)
                                    <div class="mt-2">
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Foto actual
                                        </span>
                                    </div>
                                    @else
                                    <div class="mt-2">
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-x-circle"></i> Sin foto
                                        </span>
                                    </div>
                                    @endif
                                </div>

                                {{-- Input de foto --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-camera me-1"></i>
                                        {{ $estudiante->foto_url ? 'Cambiar foto' : 'Subir foto' }}
                                    </label>
                                    <input type="file"
                                        name="foto"
                                        id="input-foto"
                                        class="form-control @error('foto') is-invalid @enderror"
                                        accept="image/jpeg,image/jpg,image/png">
                                    <small class="text-muted d-block mt-1">
                                        Formatos: JPG, JPEG, PNG<br>
                                        Tamaño máximo: 2MB
                                    </small>
                                    @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Botón eliminar foto --}}
                                @if($estudiante->foto_url)
                                <div class="form-check mb-3">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="eliminar_foto"
                                        id="eliminar-foto"
                                        value="1">
                                    <label class="form-check-label text-danger" for="eliminar-foto">
                                        <i class="bi bi-trash"></i> Eliminar foto actual
                                    </label>
                                </div>
                                @endif

                                <div class="alert alert-light small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Tip:</strong> Una foto clara mejora la identificación del estudiante.
                                </div>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{ route('admin.estudiantes.show', $estudiante->id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script para preview de imagen --}}
<script>
    document.getElementById('input-foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview-foto');
                preview.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail rounded-circle" style="width: 180px; height: 180px; object-fit: cover;">';

                // Desmarcar eliminar foto si se sube nueva
                const eliminarCheck = document.getElementById('eliminar-foto');
                if (eliminarCheck) {
                    eliminarCheck.checked = false;
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Si se marca eliminar foto, limpiar input
    const eliminarCheck = document.getElementById('eliminar-foto');
    if (eliminarCheck) {
        eliminarCheck.addEventListener('change', function() {
            if (this.checked) {
                const inputFoto = document.getElementById('input-foto');
                inputFoto.value = '';
            }
        });
    }
</script>
@endsection