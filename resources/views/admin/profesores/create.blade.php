@extends('layouts.app')

@section('title', 'Crear Profesor')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>Registrar Nuevo Profesor
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST"
                        action="{{ route('admin.profesores.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            {{-- Columna Izquierda: Datos Personales --}}
                            <div class="col-lg-8">
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

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Cédula * (10 dígitos)</label>
                                        <input type="text"
                                            name="cedula"
                                            class="form-control @error('cedula') is-invalid @enderror"
                                            value="{{ old('cedula') }}"
                                            maxlength="10"
                                            required>
                                        @error('cedula')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">La cédula se usará como usuario para el login</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Celular * (10 dígitos)</label>
                                        <input type="text"
                                            name="celular"
                                            class="form-control @error('celular') is-invalid @enderror"
                                            value="{{ old('celular') }}"
                                            maxlength="10"
                                            required>
                                        @error('celular')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Correo Electrónico *</label>
                                    <input type="email"
                                        name="correo"
                                        class="form-control @error('correo') is-invalid @enderror"
                                        value="{{ old('correo') }}"
                                        required>
                                    @error('correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <h5 class="border-bottom pb-2 mb-3 mt-4">Información Académica</h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Especialidad</label>
                                        <input type="text"
                                            name="especialidad"
                                            class="form-control @error('especialidad') is-invalid @enderror"
                                            value="{{ old('especialidad') }}"
                                            placeholder="Ej: Ingeniería de Sistemas">
                                        @error('especialidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Departamento</label>
                                        <input type="text"
                                            name="departamento"
                                            class="form-control @error('departamento') is-invalid @enderror"
                                            value="{{ old('departamento') }}"
                                            placeholder="Ej: Tecnologías de la Información">
                                        @error('departamento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Fecha de Ingreso</label>
                                        <input type="date"
                                            name="fecha_ingreso"
                                            class="form-control @error('fecha_ingreso') is-invalid @enderror"
                                            value="{{ old('fecha_ingreso') }}">
                                        @error('fecha_ingreso')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Horario de Trabajo</label>
                                        <input type="text"
                                            name="horario"
                                            class="form-control @error('horario') is-invalid @enderror"
                                            value="{{ old('horario') }}"
                                            placeholder="Ej: 08:00 - 16:00">
                                        @error('horario')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <h5 class="border-bottom pb-2 mb-3 mt-4">Credenciales de Acceso</h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Contraseña * (mínimo 8 caracteres)</label>
                                        <input type="password"
                                            name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            required>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Confirmar Contraseña *</label>
                                        <input type="password"
                                            name="password_confirmation"
                                            class="form-control"
                                            required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Estado *</label>
                                    <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                        <option value="activo" {{ old('estado') === 'activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                    @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Columna Derecha: Foto --}}
                            <div class="col-lg-4">
                                <h5 class="border-bottom pb-2 mb-3">Fotografía</h5>

                                <div class="text-center mb-3">
                                    <img id="preview"
                                        src="{{ asset('images/default-avatar.png') }}"
                                        class="img-thumbnail rounded-circle mb-3"
                                        style="width: 200px; height: 200px; object-fit: cover;"
                                        alt="Vista previa"
                                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjE4IiBmaWxsPSIjOTk5IiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5GT1RPPC90ZXh0Pjwvc3ZnPg=='">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Subir Foto</label>
                                    <input type="file"
                                        name="foto"
                                        id="foto"
                                        class="form-control @error('foto') is-invalid @enderror"
                                        accept="image/jpeg,image/jpg,image/png">
                                    @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Formatos: JPG, PNG. Máx: 2MB</small>
                                </div>

                                <div class="alert alert-info">
                                    <small>
                                        <i class="bi bi-info-circle me-1"></i>
                                        <strong>Importante:</strong> Los campos marcados con (*) son obligatorios.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.profesores.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Registrar Profesor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview de la foto
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
