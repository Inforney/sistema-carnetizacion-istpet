@extends('layouts.app')

@section('title', 'Solicitudes de Cambio de Contraseña')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>
                <i class="bi bi-key-fill me-2"></i>Solicitudes de Cambio de Contraseña
            </h2>
            <p class="text-muted mb-0">Gestiona las solicitudes de recuperación de contraseña</p>
        </div>
        <div>
            <span class="badge bg-warning text-dark fs-5">
                <i class="bi bi-clock-history me-1"></i>
                {{ $pendientes }} Pendientes
            </span>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lista de Solicitudes ({{ $solicitudes->total() }})</h5>
        </div>
        <div class="card-body">
            @if($solicitudes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha Solicitud</th>
                            <th>Estudiante</th>
                            <th>Documento</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitudes as $solicitud)
                        <tr class="{{ $solicitud->estado === 'pendiente' ? 'table-warning' : '' }}">
                            <td>
                                <small>
                                    {{ $solicitud->created_at->format('d/m/Y') }}<br>
                                    {{ $solicitud->created_at->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                <strong>{{ $solicitud->usuario->nombreCompleto }}</strong>
                                <br>
                                <small class="text-muted">{{ $solicitud->usuario->carrera ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <small>
                                    {{ strtoupper($solicitud->usuario->tipo_documento ?? 'CEDULA') }}<br>
                                    {{ $solicitud->documento }}
                                </small>
                            </td>
                            <td>
                                <small>{{ $solicitud->correo }}</small>
                            </td>
                            <td>
                                @if($solicitud->estado === 'pendiente')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock"></i> Pendiente
                                </span>
                                @elseif($solicitud->estado === 'atendida')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Atendida
                                </span>
                                @if($solicitud->notas_admin)
                                <br>
                                <small class="text-muted">{{ $solicitud->notas_admin }}</small>
                                @endif
                                @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle"></i> Rechazada
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($solicitud->estado === 'pendiente')
                                <form action="{{ route('admin.solicitudes.atender', $solicitud->id) }}"
                                    method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-success btn-sm"
                                        onclick="return confirm('¿Generar nueva contraseña temporal y notificar al estudiante?')">
                                        <i class="bi bi-check-circle"></i> Atender
                                    </button>
                                </form>

                                <button type="button"
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#rechazarModal{{ $solicitud->id }}">
                                    <i class="bi bi-x-circle"></i> Rechazar
                                </button>

                                {{-- Modal Rechazar --}}
                                <div class="modal fade" id="rechazarModal{{ $solicitud->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Rechazar Solicitud</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.solicitudes.rechazar', $solicitud->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Motivo del rechazo (opcional)</label>
                                                        <textarea name="motivo" class="form-control" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">Rechazar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-3">
                {{ $solicitudes->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="text-muted mt-3">No hay solicitudes registradas</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection