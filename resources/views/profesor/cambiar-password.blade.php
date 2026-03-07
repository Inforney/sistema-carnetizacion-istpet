@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow" style="border:2px solid var(--istpet-dorado);border-radius:10px;overflow:hidden;">
                <div class="card-header p-0">
                    <div style="background:var(--istpet-azul);padding:1.25rem 1.5rem;">
                        <h4 class="mb-0 text-white">
                            <i class="bi bi-shield-lock me-2" style="color:var(--istpet-dorado);"></i>Cambio de Contraseña Obligatorio
                        </h4>
                        <small style="color:rgba(255,255,255,0.7);">Docente: {{ auth('profesor')->user()->nombreCompleto }}</small>
                    </div>
                    <div style="height:3px;background:linear-gradient(90deg,var(--istpet-dorado),var(--istpet-azul));"></div>
                </div>
                <div class="card-body p-4">
                    <div class="p-3 rounded mb-4" style="background:rgba(212,175,55,0.12);border-left:4px solid var(--istpet-dorado);">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-exclamation-triangle-fill mt-1" style="color:var(--istpet-dorado);font-size:1.1rem;"></i>
                            <div>
                                <strong style="color:var(--istpet-azul);">Contraseña temporal activa</strong>
                                <p class="mb-0 small text-muted">Por seguridad, debes establecer tu propia contraseña antes de usar el sistema.</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profesor.cambiar-password.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nueva Contraseña <span class="text-danger">*</span></label>
                            <input type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                minlength="8"
                                required>
                            <small class="text-muted">Mínimo 8 caracteres, debe incluir mayúsculas, minúsculas, números y un carácter especial (@$!%*?&#)</small>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Confirmar Nueva Contraseña <span class="text-danger">*</span></label>
                            <input type="password"
                                name="password_confirmation"
                                class="form-control"
                                minlength="8"
                                required>
                        </div>

                        <button type="submit" class="btn fw-bold w-100"
                                style="background:var(--istpet-dorado);color:var(--istpet-azul);border:none;padding:0.65rem;">
                            <i class="bi bi-check-circle me-2"></i>Establecer Mi Contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
