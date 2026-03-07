@extends('layouts.app')

@section('title', 'Detalles del Estudiante')

@section('content')
<div class="container py-4">
    <div class="row">
        {{-- Columna principal --}}
        <div class="col-lg-8">

            {{-- Información del Estudiante --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-person me-2"></i>Información del Estudiante
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.estudiantes.edit', $estudiante->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Editar
                        </a>
                        @if($estudiante->tipo_usuario === 'graduado')
                            <span class="btn btn-secondary disabled" title="Registro de graduado — no se puede cambiar estado">
                                <i class="bi bi-mortarboard me-2"></i>Graduado
                            </span>
                        @else
                        <form action="{{ route('admin.estudiantes.toggle', $estudiante->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('¿{{ $estudiante->estado === 'activo' ? 'Bloquear' : 'Desbloquear' }} a {{ $estudiante->nombreCompleto }}?')">
                            @csrf
                            <button type="submit" class="btn btn-{{ $estudiante->estado === 'activo' ? 'danger' : 'success' }}">
                                <i class="bi bi-{{ $estudiante->estado === 'activo' ? 'lock' : 'unlock' }} me-2"></i>
                                {{ $estudiante->estado === 'activo' ? 'Bloquear' : 'Desbloquear' }}
                            </button>
                        </form>
                        @endif
                        {{-- Solo mostrar si NO existe ya como profesor --}}
                        @php $yaEsProfesor = \App\Models\Profesor::where('cedula', $estudiante->cedula)->exists(); @endphp
                        @if(!$yaEsProfesor)
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPromoverProfesor">
                            <i class="bi bi-arrow-up-circle me-1"></i> Promover a Profesor
                        </button>
                        @else
                        <span class="btn btn-secondary disabled">
                            <i class="bi bi-check-circle me-1"></i> Ya es Profesor
                        </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    {{-- Foto del estudiante --}}
                    <div class="text-center mb-4 pb-3 border-bottom">
                        @if($estudiante->foto_url)
                        <img src="{{ asset($estudiante->foto_url) }}"
                            alt="Foto de {{ $estudiante->nombreCompleto }}"
                            class="img-thumbnail rounded-circle"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                        <div class="d-inline-flex align-items-center justify-content-center img-thumbnail rounded-circle"
                            style="width: 150px; height: 150px; background: linear-gradient(135deg, var(--istpet-azul) 0%, #4a5a9a 100%);">
                            <span style="font-size: 48px; color: white; font-weight: bold;">
                                {{ strtoupper(substr($estudiante->nombres, 0, 1) . substr($estudiante->apellidos, 0, 1)) }}
                            </span>
                        </div>
                        @endif
                        <h5 class="mt-3 mb-0">{{ $estudiante->nombreCompleto }}</h5>
                        @if($estudiante->tipo_usuario === 'graduado')
                        <span class="badge mt-1" style="background:var(--istpet-dorado);color:var(--istpet-azul);">
                            <i class="bi bi-mortarboard me-1"></i>GRADUADO
                        </span>
                        @elseif($estudiante->estado === 'activo')
                        <span class="badge bg-success mt-1"><i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>ACTIVO</span>
                        @else
                        <span class="badge bg-danger mt-1"><i class="bi bi-lock me-1"></i>BLOQUEADO</span>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">CÉDULA O PASAPORTE</h6>
                            <p class="mb-0">{{ strtoupper($estudiante->tipo_documento ?? 'CEDULA') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">NÚMERO DE DOCUMENTO</h6>
                            <p class="mb-0"><strong>{{ $estudiante->cedula }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3 border-top pt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">NACIONALIDAD</h6>
                            <p class="mb-0">{{ $estudiante->nacionalidad ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">CICLO/NIVEL</h6>
                            <p class="mb-0"><span class="badge bg-info">{{ $estudiante->ciclo_nivel }}</span></p>
                        </div>
                    </div>

                    <div class="row mb-3 border-top pt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">CORREO INSTITUCIONAL</h6>
                            <p class="mb-0"><i class="bi bi-envelope me-2"></i>{{ $estudiante->correo_institucional }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">CELULAR</h6>
                            <p class="mb-0"><i class="bi bi-phone me-2"></i>{{ $estudiante->celular }}</p>
                        </div>
                    </div>

                    <div class="row border-top pt-3">
                        <div class="col-md-6">
                            <h6 class="text-muted small">FECHA DE REGISTRO</h6>
                            <p class="mb-0">
                                @if($estudiante->created_at)
                                {{ $estudiante->created_at->format('d/m/Y H:i') }}
                                @else N/A @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">CARRERA</h6>
                            <p class="mb-0">{{ $estudiante->carrera ?? 'N/A' }}</p>
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
                                    {{ $estudiante->cedula }}
                                </code>
                                <small class="text-muted">Cédula</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small">CONTRASEÑA ACTUAL</h6>
                            @if($passwordConocida)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative">
                                        <code id="passField" class="fs-6 px-3 py-2 rounded"
                                              style="background:#fff8e1;color:#7a6000;font-weight:700;border:1.5px dashed #c4a857;letter-spacing:2px;">
                                            {{ str_repeat('●', strlen($passwordConocida)) }}
                                        </code>
                                        <code id="passFieldVisible" class="fs-6 px-3 py-2 rounded d-none"
                                              style="background:#fff8e1;color:#7a6000;font-weight:700;border:1.5px dashed #c4a857;letter-spacing:2px;">
                                            {{ $passwordConocida }}
                                        </code>
                                    </div>
                                    <button type="button" id="togglePass" class="btn btn-sm btn-outline-secondary" title="Mostrar/ocultar">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                <small class="text-warning d-block mt-1">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Contraseña temporal activa — el estudiante debe cambiarla al ingresar
                                </small>
                            @else
                                <div class="d-flex align-items-center gap-2">
                                    <code class="fs-6 px-3 py-2 rounded" style="background:#f0f0f0;color:#666;font-weight:700;">
                                        ●●●●●●●●●●
                                    </code>
                                    <span class="text-muted small">Contraseña personalizada</span>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-info-circle me-1"></i>El estudiante cambió su contraseña — no es visible por seguridad
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="border-top pt-3 d-flex flex-wrap gap-2">
                        {{-- Botón resetear --}}
                        <form action="{{ route('admin.estudiantes.reset-password', $estudiante->id) }}" method="POST"
                            onsubmit="return confirm('¿Resetear contraseña de {{ $estudiante->nombreCompleto }}?\n\nSe generará la contraseña temporal: ISTPET{{ substr($estudiante->cedula, -4) }}')">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-key me-2"></i>Resetear Contraseña
                            </button>
                        </form>

                        {{-- Botón enviar por WhatsApp si hay contraseña conocida --}}
                        @if($passwordConocida)
                        @php
                            $telIntl = '593' . ltrim($estudiante->celular, '0');
                            $msg = "Hola {$estudiante->nombreCompleto}, tu contraseña en el Sistema ISTPET es:\n\n*{$passwordConocida}*\n\nIngresa con tu cédula y esta contraseña. Cámbiala al iniciar sesión.";
                            $waLink = 'https://wa.me/' . $telIntl . '?text=' . urlencode($msg);
                        @endphp
                        <a href="{{ $waLink }}" target="_blank"
                           class="btn btn-sm fw-bold"
                           style="background:#25D366;color:white;border:none;border-radius:8px;padding:8px 16px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white" class="me-1" style="vertical-align:middle;">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Enviar Contraseña por WhatsApp
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Solicitudes de contraseña --}}
            @if($solicitudes->count() > 0)
            <div class="card mb-4" style="border-top: 4px solid var(--istpet-dorado);">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope-exclamation me-2" style="color:var(--istpet-dorado);"></i>
                        Solicitudes de Cambio de Contraseña
                    </h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead style="background:rgba(34,44,87,0.05);">
                            <tr>
                                <th style="font-size:0.8rem;text-transform:uppercase;color:var(--istpet-azul);">Fecha</th>
                                <th style="font-size:0.8rem;text-transform:uppercase;color:var(--istpet-azul);">Correo</th>
                                <th style="font-size:0.8rem;text-transform:uppercase;color:var(--istpet-azul);">Estado</th>
                                <th style="font-size:0.8rem;text-transform:uppercase;color:var(--istpet-azul);">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($solicitudes as $sol)
                            <tr>
                                <td><small>{{ $sol->created_at->format('d/m/Y H:i') }}</small></td>
                                <td><small>{{ $sol->correo ?? $estudiante->correo_institucional }}</small></td>
                                <td>
                                    @if($sol->estado === 'pendiente')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                    @elseif($sol->estado === 'atendida')
                                    <span class="badge bg-success">Atendida</span>
                                    @else
                                    <span class="badge bg-danger">Rechazada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($sol->estado === 'pendiente')
                                    <form action="{{ route('admin.estudiantes.reset-password', $estudiante->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('¿Atender esta solicitud reseteando la contraseña?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-key me-1"></i>Resetear y atender
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Estadísticas de Accesos --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Estadísticas de Accesos</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h2 class="fw-bold" style="color:var(--istpet-azul);">{{ $stats['total_accesos'] ?? 0 }}</h2>
                            <p class="text-muted mb-0">Total de Accesos</p>
                        </div>
                        <div class="col-md-4">
                            <h2 class="fw-bold text-success">{{ $stats['accesos_mes'] ?? 0 }}</h2>
                            <p class="text-muted mb-0">Este Mes</p>
                        </div>
                        <div class="col-md-4">
                            @if(isset($stats['ultimo_acceso']) && $stats['ultimo_acceso'])
                            <h6 class="text-info fw-bold">
                                @if(is_object($stats['ultimo_acceso']->fecha_entrada))
                                {{ $stats['ultimo_acceso']->fecha_entrada->format('d/m/Y') }}
                                @else
                                {{ date('d/m/Y', strtotime($stats['ultimo_acceso']->fecha_entrada)) }}
                                @endif
                            </h6>
                            <p class="text-muted mb-0">Último Acceso</p>
                            @else
                            <h6 class="text-muted">—</h6>
                            <p class="text-muted mb-0">Sin Accesos</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna lateral --}}
        <div class="col-lg-4">

            {{-- Carnet --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Carnet</h5>
                </div>
                <div class="card-body">
                    @if($estudiante->carnet)
                    <div class="text-center">
                        <div class="mb-3 p-3 bg-light rounded">
                            <div class="border border-2 d-inline-block p-3 bg-white" style="border-color:var(--istpet-azul)!important;">
                                <i class="bi bi-qr-code display-1" style="color:var(--istpet-azul);"></i>
                            </div>
                        </div>
                        <p class="mb-1"><strong>Código QR</strong></p>
                        <p class="text-muted small">{{ $estudiante->carnet->codigo_qr }}</p>

                        <div class="border-top pt-3 mt-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Estado:</span>
                                <span class="badge bg-{{ $estudiante->carnet->estado === 'activo' ? 'success' : 'danger' }}">
                                    {{ ucfirst($estudiante->carnet->estado) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Emisión:</span>
                                <span>
                                    @if(is_object($estudiante->carnet->fecha_emision))
                                    {{ $estudiante->carnet->fecha_emision->format('d/m/Y') }}
                                    @else
                                    {{ date('d/m/Y', strtotime($estudiante->carnet->fecha_emision)) }}
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Vencimiento:</span>
                                <span>
                                    @if($estudiante->carnet->fecha_vencimiento)
                                    @php $fv = is_object($estudiante->carnet->fecha_vencimiento) ? $estudiante->carnet->fecha_vencimiento : \Carbon\Carbon::parse($estudiante->carnet->fecha_vencimiento); @endphp
                                    <span class="{{ $fv->isPast() ? 'text-danger fw-bold' : '' }}">
                                        {{ $fv->format('d/m/Y') }}
                                        @if($fv->isPast()) <i class="bi bi-exclamation-triangle-fill"></i> @endif
                                    </span>
                                    @else N/A @endif
                                </span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('admin.carnets.show', $estudiante->carnet->id) }}" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-eye me-2"></i>Ver Carnet Completo
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-circle display-3 text-warning"></i>
                        <p class="text-muted mt-3">Sin carnet asignado</p>
                        <a href="{{ route('admin.carnets.create') }}?estudiante={{ $estudiante->id }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Generar Carnet
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Acciones Administrativas --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Acciones</h5>
                </div>
                <div class="card-body d-flex flex-column gap-2">
                    <a href="{{ route('admin.estudiantes.edit', $estudiante->id) }}" class="btn btn-warning w-100">
                        <i class="bi bi-pencil me-2"></i>Editar Datos
                    </a>
                    <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-left me-2"></i>Volver a la lista
                    </a>
                    <hr class="my-1">
                    <form action="{{ route('admin.estudiantes.destroy', $estudiante->id) }}" method="POST"
                        onsubmit="return confirm('⚠️ ¿Eliminar a {{ $estudiante->nombreCompleto }}?\n\nSe eliminará el estudiante, su carnet y todo su historial.\n\nEsta acción NO se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-2"></i>Eliminar Estudiante
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Modal: Promover a Profesor ─────────────────────────────────────── --}}
<div class="modal fade" id="modalPromoverProfesor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border:2px solid var(--istpet-dorado);border-radius:10px;overflow:hidden;">
            {{-- Header con colores del instituto --}}
            <div class="modal-header" style="background:var(--istpet-azul);">
                <div>
                    <h5 class="modal-title text-white mb-0">
                        <i class="bi bi-mortarboard me-2" style="color:var(--istpet-dorado);"></i>Promover a Profesor
                    </h5>
                    <small style="color:rgba(255,255,255,0.7);">{{ $estudiante->nombreCompleto }}</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            {{-- Barra dorada decorativa --}}
            <div style="height:3px;background:linear-gradient(90deg,var(--istpet-dorado),var(--istpet-azul));"></div>

            <form action="{{ route('admin.estudiantes.promover', $estudiante->id) }}" method="POST"
                  onsubmit="return confirm('¿Confirmar promoción?\n\nEl historial de estudiante quedará guardado como graduado.\nSe creará una nueva cuenta de profesor.')">
                @csrf
                <div class="modal-body">
                    {{-- Aviso informativo con color del instituto --}}
                    <div class="p-3 rounded mb-4" style="background:rgba(26,35,66,0.06);border-left:4px solid var(--istpet-dorado);">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill mt-1" style="color:var(--istpet-dorado);font-size:1.1rem;"></i>
                            <div>
                                <strong style="color:var(--istpet-azul);">Historial preservado</strong>
                                <p class="mb-0 small text-muted">El registro de estudiante <strong>NO se elimina</strong>. Quedará marcado como <span class="badge" style="background:var(--istpet-dorado);color:var(--istpet-azul);">GRADUADO</span> conservando todo su historial de accesos y actividad. Solo se desactiva su acceso como estudiante y se crea una nueva cuenta de profesor.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Datos de identidad (solo lectura) --}}
                    <p class="small fw-bold mb-2" style="color:var(--istpet-azul);text-transform:uppercase;letter-spacing:0.5px;">
                        <i class="bi bi-person-badge me-1"></i>Datos de identidad (del expediente estudiantil)
                    </p>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted small">NOMBRES</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $estudiante->nombres }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">APELLIDOS</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $estudiante->apellidos }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">CÉDULA O PASAPORTE</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $estudiante->cedula }}" readonly>
                        </div>
                    </div>

                    <hr style="border-color:var(--istpet-dorado);opacity:0.3;">
                    <p class="small fw-bold mb-3" style="color:var(--istpet-azul);text-transform:uppercase;letter-spacing:0.5px;">
                        <i class="bi bi-person-workspace me-1"></i>Datos para la cuenta de profesor
                    </p>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Correo del Profesor <span class="text-danger">*</span></label>
                            <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                                   value="{{ old('correo', $estudiante->correo_institucional) }}" required>
                            @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Celular <span class="text-danger">*</span></label>
                            <input type="text" name="celular" class="form-control @error('celular') is-invalid @enderror"
                                   value="{{ old('celular', $estudiante->celular) }}" maxlength="10" required>
                            @error('celular')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Especialidad</label>
                            <input type="text" name="especialidad" class="form-control" value="{{ old('especialidad') }}" placeholder="Ej: Redes y Telecomunicaciones">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Departamento</label>
                            <input type="text" name="departamento" class="form-control" value="{{ old('departamento') }}" placeholder="Ej: Tecnología">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Ingreso</label>
                            <input type="date" name="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Horario de Trabajo</label>
                            <input type="text" name="horario" class="form-control" value="{{ old('horario') }}" placeholder="Ej: Lunes a Viernes 08:00–16:00">
                        </div>
                    </div>

                    {{-- Contraseña temporal auto-generada --}}
                    <div class="p-3 rounded" style="background:rgba(212,175,55,0.12);border-left:4px solid var(--istpet-dorado);">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-key-fill mt-1" style="color:var(--istpet-dorado);font-size:1.1rem;"></i>
                            <div>
                                <strong style="color:var(--istpet-azul);">Contraseña temporal auto-generada</strong>
                                <p class="mb-0 small text-muted">Se asignará automáticamente la clave <strong>ISTPET + últimos 4 dígitos de cédula</strong>. El docente deberá cambiarla al iniciar sesión por primera vez. Se mostrará el enlace de WhatsApp para notificarle.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background:rgba(26,35,66,0.04);">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn fw-bold"
                            style="background:var(--istpet-dorado);color:var(--istpet-azul);border:none;">
                        <i class="bi bi-mortarboard me-1"></i> Confirmar Promoción
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Toggle mostrar/ocultar contraseña temporal
    document.getElementById('togglePass')?.addEventListener('click', function () {
        const oculto = document.getElementById('passField');
        const visible = document.getElementById('passFieldVisible');
        const icon = document.getElementById('eyeIcon');
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
