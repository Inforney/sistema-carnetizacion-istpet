@extends('layouts.app')

@section('title', 'Crear Estudiante')

@section('content')
<div class="container py-4">


    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>Crear Nuevo Estudiante
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.estudiantes.store') }}">
                        @csrf

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
                                    <option value="PRIMER NIVEL" {{ old('ciclo_nivel') == 'PRIMER NIVEL' ? 'selected' : '' }}>Primer Semestre</option>
                                    <option value="SEGUNDO NIVEL" {{ old('ciclo_nivel') == 'SEGUNDO NIVEL' ? 'selected' : '' }}>Segundo Semestre</option>
                                    <option value="TERCER NIVEL" {{ old('ciclo_nivel') == 'TERCER NIVEL' ? 'selected' : '' }}>Tercer Semestre</option>
                                    <option value="CUARTO NIVEL" {{ old('ciclo_nivel') == 'CUARTO NIVEL' ? 'selected' : '' }}>Cuarto Semestre</option>
                                </select>
                                @error('ciclo_nivel')
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

                        {{-- Botones --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Crear Estudiante
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection