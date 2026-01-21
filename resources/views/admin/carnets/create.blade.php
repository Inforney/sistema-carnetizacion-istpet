@extends('layouts.app')

@section('title', 'Crear Nuevo Carnet')

@section('content')
<div class="container py-4">


    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Generar Nuevo Carnet
                    </h4>
                </div>

                <div class="card-body">
                    @if($estudiantesSinCarnet->count() > 0)
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Hay <strong>{{ $estudiantesSinCarnet->count() }}</strong> estudiantes sin carnet asignado.
                        Selecciona uno para generar su carnet.
                    </div>

                    <form action="{{ route('admin.carnets.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Seleccionar Estudiante *</label>
                            <select name="usuario_id"
                                class="form-select @error('usuario_id') is-invalid @enderror"
                                required>
                                <option value="">-- Selecciona un estudiante --</option>
                                @foreach($estudiantesSinCarnet as $estudiante)
                                <option value="{{ $estudiante->id }}">
                                    {{ $estudiante->nombreCompleto }}
                                    ({{ $estudiante->cedula }})
                                    - {{ $estudiante->ciclo_nivel }}
                                </option>
                                @endforeach
                            </select>
                            @error('usuario_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <h6 class="alert-heading">
                                <i class="bi bi-exclamation-triangle me-2"></i>Información Importante
                            </h6>
                            <ul class="mb-0">
                                <li>El carnet se generará automáticamente con un código QR único</li>
                                <li>El código QR tendrá el formato: <code>ISTPET-2026-[CEDULA]</code></li>
                                <li>El carnet será válido por 4 años (período académico completo)</li>
                                <li>El carnet se creará en estado ACTIVO</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.carnets.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Generar Carnet
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>¡Excelente!</strong> Todos los estudiantes ya tienen carnet asignado.
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('admin.carnets.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-2"></i>Volver a Carnets
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection