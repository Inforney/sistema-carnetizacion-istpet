@extends('layouts.app')

@section('title', 'Editar Estudiante')

@section('content')
<div class="container py-4">


    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil me-2"></i>Editar Estudiante
                    </h4>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Nota:</strong> El documento de identidad NO puede ser modificado por seguridad.
                    </div>

                    <form method="POST" action="{{ route('admin.estudiantes.update', $estudiante->id) }}">
                        @csrf
                        @method('PUT')

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
                                    <option value="PRIMER NIVEL" {{ old('ciclo_nivel', $estudiante->ciclo_nivel) == 'PRIMER NIVEL' ? 'selected' : '' }}>Primer Semestre</option>
                                    <option value="SEGUNDO NIVEL" {{ old('ciclo_nivel', $estudiante->ciclo_nivel) == 'SEGUNDO NIVEL' ? 'selected' : '' }}>Segundo Semestre</option>
                                    <option value="TERCER NIVEL" {{ old('ciclo_nivel', $estudiante->ciclo_nivel) == 'TERCER NIVEL' ? 'selected' : '' }}>Tercer Semestre</option>
                                    <option value="CUARTO NIVEL" {{ old('ciclo_nivel', $estudiante->ciclo_nivel) == 'CUARTO NIVEL' ? 'selected' : '' }}>Cuarto Semestre</option>
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

                        {{-- Botones --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.estudiantes.show', $estudiante->id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection