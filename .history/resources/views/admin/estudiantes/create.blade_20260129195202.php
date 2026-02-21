@extends('layouts.app')

@section('title', 'Crear Estudiante')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header" style="background: #1a2342;">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-person-plus me-2"></i>Crear Nuevo Estudiante
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    </div>
                    @endif

                    <form method="POST"
                        action="{{ route('admin.estudiantes.store') }}"
                        enctype="multipart/form-data">
                        @csrf

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
                                            value="{{ old('nombres') }}"
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
                                            value="{{ old('apellidos') }}"
                                            required>
                                        @error('apellidos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Documento --}}
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Tipo de Documento *</label>
                                        <select name="tipo_documento"
                                            class="form-select @error('tipo_documento') is-invalid @enderror"
                                            required>
                                            <option value="cedula" {{ old('tipo_documento') == 'cedula' ? 'selected' : '' }}>Cédula</option>
                                            <option value="pasaporte" {{ old('tipo_documento') == 'pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                                            <option value="ruc" {{ old('tipo_documento') == 'ruc' ? 'selected' : '' }}>RUC</option>
                                        </select>
                                        @error('tipo_documento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-8">
                                        <label class="form-label">Número de Documento *</label>
                                        <input type="text"
                                            name="cedula"
                                            class="form-control @error('cedula') is-invalid @enderror"
                                            value="{{ old('cedula') }}"
                                            required>
                                        @error('cedula')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nacionalidad *</label>
                                        <select name="nacionalidad"
                                            class="form-select @error('nacionalidad') is-invalid @enderror"
                                            required>
                                            <option value="Ecuatoriana" {{ old('nacionalidad') == 'Ecuatoriana' ? 'selected' : '' }}>Ecuatoriana</option>
                                            <option value="Colombiana" {{ old('nacionalidad') == 'Colombiana' ? 'selected' : '' }}>Colombiana</option>
                                            <option value="Peruana" {{ old('nacionalidad') == 'Peruana' ? 'selected' : '' }}>Peruana</option>
                                            <option value="Venezolana" {{ old('nacionalidad') == 'Venezolana' ? 'selected' : '' }}>Venezolana</option>
                                            <option value="Otra">Otra</option>
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
                                            <option value="PRIMER NIVEL" {{ old('ciclo_nivel') == 'PRIMER NIVEL' ? 'selected' : '' }}>Primer Nivel</option>
                                            <option value="SEGUNDO NIVEL" {{ old('ciclo_nivel') == 'SEGUNDO NIVEL' ? 'selected' : '' }}>Segundo Nivel</option>
                                            <option value="TERCER NIVEL" {{ old('ciclo_nivel') == 'TERCER NIVEL' ? 'selected' : '' }}>Tercer Nivel</option>
                                            <option value="CUARTO NIVEL" {{ old('ciclo_nivel') == 'CUARTO NIVEL' ? 'selected' : '' }}>Cuarto Nivel</option>
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
                                            <option value="Desarrollo de Software" {{ old('carrera') == 'Desarrollo de Software' ? 'selected' : '' }}>Desarrollo de Software</option>
                                            <option value="Redes y Telecomunicaciones" {{ old('carrera') == 'Redes y Telecomunicaciones' ? 'selected' : '' }}>Redes y Telecomunicaciones</option>
                                            <option value="Seguridad Informática" {{ old('carrera') == 'Seguridad Informática' ? 'selected' : '' }}>Seguridad Informática</option>
                                            <option value="Administración de Sistemas" {{ old('carrera') == 'Administración de Sistemas' ? 'selected' : '' }}>Administración de Sistemas</option>
                                            <option value="Soporte Técnico" {{ old('carrera') == 'Soporte Técnico' ? 'selected' : '' }}>Soporte Técnico</option>
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
                                            value="{{ old('correo_institucional') }}"
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
                                            value="{{ old('celular') }}"
                                            required>
                                        @error('celular')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Contraseña --}}
                                <h5 class="border-bottom pb-2 mb-3 mt-4">Contraseña de Acceso</h5>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Contraseña *</label>
                                        <input type="password"
                                            name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            required
                                            minlength="8">
                                        <small class="text-muted">Mínimo 8 caracteres</small>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Generar Carnet --}}
                                <div class="form-check mb-4">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="generar_carnet"
                                        id="generar_carnet"
                                        value="1"
                                        checked>
                                    <label class="form-check-label" for="generar_carnet">
                                        <strong>Generar carnet automáticamente</strong>
                                        <br>
                                        <small class="text-muted">
                                            Se creará un carnet con código QR único válido por 4 años
                                        </small>
                                    </label>
                                </div>
                            </div>

                            {{-- Columna Derecha: Foto --}}
                            <div class="col-lg-4">
                                <h5 class="border-bottom pb-2 mb-3">Foto de Perfil</h5>

                                <div class="text-center mb-3">
                                    {{-- Vista previa de foto --}}
                                    <div class="position-relative d-inline-block">
                                        <div id="preview-foto"
                                            class="img-thumbnail rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 180px; height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <span style="font-size: 64px; color: white; font-weight: bold;">
                                                <i class="bi bi-person"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-camera"></i> Sin foto
                                        </span>
                                    </div>
                                </div>

                                {{-- Input de foto --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-camera me-1"></i>
                                        Subir foto <span class="text-muted">(Opcional)</span>
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

                                <div class="alert alert-light small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Tip:</strong> Puedes subir la foto ahora o después al editar el estudiante.
                                </div>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Crear Estudiante
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
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection