@extends('layouts.guest')

@section('title', 'Crear Nueva Contraseña')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        Crear Nueva Contraseña
                    </h3>
                </div>

                <div class="card-body p-4">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('reset.post') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

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

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-check-circle me-2"></i>Actualizar Contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection