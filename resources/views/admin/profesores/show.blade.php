@extends('layouts.app')

@section('title', 'Detalle del Profesor')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Información del Profesor --}}
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-person-badge me-2"></i>Detalle del Profesor
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.profesores.edit', $profesor->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil me-1"></i>Editar
                            </a>
                            <a href="{{ route('admin.profesores.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i>Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        {{-- Columna Izquierda: Información --}}
                        <div class="col-lg-8">
                            <h5 class="border-bottom pb-2 mb-3">Datos Personales</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted small">NOMBRES COMPLETOS</h6>
                                    <p class="mb-0"><strong>{{ $profesor->nombreCompleto }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted small">CÉDULA</h6>
                                    <p class="mb-0"><strong>{{ $profesor->cedula }}</strong></p>
                                </div>
                            </div>

                            <div class="row mb-3 border-top pt-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted small">CORREO ELECTRÓNICO</h6>
                                    <p class="mb-0"><strong>{{ $profesor->correo }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted small">CELULAR</h6>
                                    <p class="mb-0"><strong>{{ $profesor->celular }}</strong></p>
                                </div>
                            </div>

                            <h5 class="border-bottom pb-2 mb-3 mt-4">Información Académica</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted small">ESPECIALIDAD</h6>
                                    <p class="mb-0"><strong>{{ $profesor->especialidad ?? 'No especificada' }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted small">DEPARTAMENTO</h6>
                                    <p class="mb-0"><strong>{{ $profesor->departamento ?? 'No especificado' }}</strong></p>
                                </div>
                            </div>

                            <div class="row mb-3 border-top pt-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted small">FECHA DE INGRESO</h6>
                                    <p class="mb-0">
                                        <strong>
                                            @if($profesor->fecha_ingreso)
                                            {{ date('d/m/Y', strtotime($profesor->fecha_ingreso)) }}
                                            @else No especificada @endif
                                        </strong>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted small">HORARIO DE TRABAJO</h6>
                                    <p class="mb-0"><strong>{{ $profesor->horario ?? 'No especificado' }}</strong></p>
                                </div>
                            </div>

                            <h5 class="border-bottom pb-2 mb-3 mt-4">Estado y Estadísticas</h5>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <h6 class="text-muted small">ESTADO</h6>
                                    <span class="badge bg-{{ $profesor->estado === 'activo' ? 'success' : 'danger' }} fs-6">
                                        {{ strtoupper($profesor->estado) }}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted small">ACCESOS VALIDADOS</h6>
                                    <p class="mb-0"><strong class="text-primary fs-5">{{ $accesosValidados }}</strong></p>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted small">REGISTRADO DESDE</h6>
                                    <p class="mb-0"><strong>{{ $profesor->created_at->format('d/m/Y') }}</strong></p>
                                </div>
                            </div>

                            <h5 class="border-bottom pb-2 mb-3 mt-4">Acciones</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <form action="{{ route('admin.profesores.toggle', $profesor->id) }}" method="POST"
                                    onsubmit="return confirm('¿{{ $profesor->estado === 'activo' ? 'Desactivar' : 'Activar' }} a {{ $profesor->nombreCompleto }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $profesor->estado === 'activo' ? 'danger' : 'success' }}">
                                        <i class="bi bi-{{ $profesor->estado === 'activo' ? 'lock' : 'unlock' }} me-2"></i>
                                        {{ $profesor->estado === 'activo' ? 'Desactivar Profesor' : 'Activar Profesor' }}
                                    </button>
                                </form>

                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                    <i class="bi bi-key me-2"></i>Resetear Contraseña
                                </button>
                            </div>
                        </div>

                        {{-- Columna Derecha: Foto --}}
                        <div class="col-lg-4">
                            <h5 class="border-bottom pb-2 mb-3">Fotografía</h5>
                            <div class="text-center mb-3">
                                @if($profesor->foto_url && file_exists(public_path($profesor->foto_url)))
                                <img src="{{ asset($profesor->foto_url) }}"
                                    class="img-thumbnail rounded-circle"
                                    style="width: 200px; height: 200px; object-fit: cover;"
                                    alt="Foto del profesor">
                                @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                    style="width:200px;height:200px;background:linear-gradient(135deg,var(--istpet-azul),#4a5a9a);color:white;">
                                    <span style="font-size:64px;font-weight:bold;">
                                        {{ strtoupper(substr($profesor->nombres, 0, 1) . substr($profesor->apellidos, 0, 1)) }}
                                    </span>
                                </div>
                                <p class="text-muted mt-2">Sin fotografía</p>
                                @endif
                            </div>

                            <div class="alert alert-light border" style="font-size:0.82rem;">
                                <i class="bi bi-calendar-plus me-1"></i> Creado: {{ $profesor->created_at->format('d/m/Y H:i') }}<br>
                                <i class="bi bi-calendar-check me-1"></i> Actualizado: {{ $profesor->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CREDENCIALES DE ACCESO --}}
            <div class="card mb-4" style="border-top: 4px solid var(--istpet-azul);">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-lock me-2" style="color:var(--istpet-dorado);"></i>Credenciales de Acceso
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">USUARIO (para iniciar sesión)</h6>
                            <div class="d-flex align-items-center gap-2">
                                <code class="fs-6 px-3 py-2 rounded" style="background:rgba(34,44,87,0.08);color:var(--istpet-azul);font-weight:700;">
                                    {{ $profesor->cedula }}
                                </code>
                                <small class="text-muted">Cédula</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">CONTRASEÑA</h6>
                            @if($profesor->password_temporal)
                                {{-- Contraseña temporal activa: mostrable con toggle --}}
                                <div class="d-flex align-items-center gap-2">
                                    <code id="passProf" class="fs-6 px-3 py-2 rounded"
                                          style="background:#fff8e1;color:#7a6000;font-weight:700;border:1.5px dashed #c4a857;letter-spacing:2px;">
                                        {{ str_repeat('●', strlen('ISTPET' . substr($profesor->cedula, -4))) }}
                                    </code>
                                    <code id="passProfVisible" class="fs-6 px-3 py-2 rounded d-none"
                                          style="background:#fff8e1;color:#7a6000;font-weight:700;border:1.5px dashed #c4a857;letter-spacing:2px;">
                                        ISTPET{{ substr($profesor->cedula, -4) }}
                                    </code>
                                    <button type="button" id="togglePassProf" class="btn btn-sm btn-outline-secondary" title="Mostrar/ocultar">
                                        <i class="bi bi-eye" id="eyeIconProf"></i>
                                    </button>
                                </div>
                                <small class="text-warning d-block mt-1">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Contraseña temporal activa — el docente <strong>debe cambiarla</strong> al iniciar sesión por primera vez.
                                </small>
                            @else
                                {{-- El docente ya cambió su contraseña --}}
                                <div class="d-flex align-items-center gap-2">
                                    <code class="fs-6 px-3 py-2 rounded" style="background:#f0f0f0;color:#888;font-weight:700;">
                                        ●●●●●●●●●●
                                    </code>
                                    <span class="text-muted small">Contraseña personalizada</span>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-info-circle me-1"></i>
                                    El docente estableció su propia contraseña — no es visible por seguridad.
                                    Para asignar una nueva temporal, usa <strong>Resetear Contraseña</strong>.
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="border-top pt-3 d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                            <i class="bi bi-key me-2"></i>Resetear Contraseña
                        </button>

                        {{-- Botón WhatsApp solo si hay contraseña temporal activa --}}
                        @if($profesor->password_temporal)
                        @php
                            $passReset = 'ISTPET' . substr($profesor->cedula, -4);
                            $telIntl = '593' . ltrim($profesor->celular, '0');
                            $msgProf = "Hola {$profesor->nombreCompleto}, tu contraseña temporal en el Sistema ISTPET es:\n\n*{$passReset}*\n\nIngresa con tu cédula y esta contraseña. Deberás cambiarla al iniciar sesión.\n" . config('app.url');
                            $waLinkProf = 'https://wa.me/' . $telIntl . '?text=' . urlencode($msgProf);
                        @endphp
                        <a href="{{ $waLinkProf }}" target="_blank"
                           class="btn fw-bold"
                           style="background:#25D366;color:white;border:none;border-radius:8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white" class="me-1" style="vertical-align:middle;">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Enviar Contraseña por WhatsApp
                        </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal para Resetear Contraseña --}}
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-key me-2"></i>Resetear Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Resetear la contraseña de <strong>{{ $profesor->nombreCompleto }}</strong>?</p>
                <div class="alert" style="background:#fff8e1;border:1.5px dashed #c4a857;border-radius:8px;">
                    <i class="bi bi-key me-2" style="color:var(--istpet-dorado);"></i>
                    La nueva contraseña temporal será:
                    <strong class="d-block mt-1 fs-5" style="color:var(--istpet-azul);letter-spacing:2px;">
                        ISTPET{{ substr($profesor->cedula, -4) }}
                    </strong>
                </div>
                <p class="text-muted small mb-0">
                    <i class="bi bi-whatsapp me-1" style="color:#25D366;"></i>
                    Después de resetear, usa el botón "Enviar por WhatsApp" para notificar al profesor.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.profesores.reset-password', $profesor->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key me-2"></i>Confirmar Reset
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Toggle contraseña profesor
    document.getElementById('togglePassProf')?.addEventListener('click', function () {
        const oculto = document.getElementById('passProf');
        const visible = document.getElementById('passProfVisible');
        const icon = document.getElementById('eyeIconProf');
        if (oculto.classList.contains('d-none')) {
            oculto.classList.remove('d-none');
            visible.classList.add('d-none');
            icon.className = 'bi bi-eye';
        } else {
            oculto.classList.add('d-none');
            visible.classList.remove('d-none');
            icon.className = 'bi bi-eye-slash';
        }
    });
</script>
@endsection
