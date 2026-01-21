@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i>Cambio de Contraseña Obligatorio
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Tienes una contraseña temporal. Por seguridad, debes cambiarla antes de continuar.
                    </div>

                    <form method="POST" action="{{ route('estudiante.cambiar-password.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Contraseña Actual (Temporal)</label>
                            <input type="password"
                                name="password_actual"
                                class="form-control @error('password_actual') is-invalid @enderror"
                                required>
                            @error('password_actual')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                minlength="8"
                                required>
                            <small class="text-muted">Mínimo 8 caracteres</small>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password"
                                name="password_confirmation"
                                class="form-control"
                                minlength="8"
                                required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">
                            <i class="bi bi-check-circle me-2"></i>Cambiar Contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection