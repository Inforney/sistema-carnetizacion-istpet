@extends('layouts.app')

@section('title', 'Estudiantes en ' . $laboratorio->nombre)

@section('content')
<div class="container py-4">
    {{-- Header --}}
    <div class="card mb-4" style="background: linear-gradient(135deg, #1a2342 0%, #222C57 100%);">
        <div class="card-body text-white">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="bi bi-building me-2"></i>{{ $laboratorio->nombre }}
                    </h2>
                    <p class="mb-0">
                        <i class="bi bi-geo-alt me-2"></i>{{ $laboratorio->ubicacion }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <h3 class="mb-1">
                        <span class="badge bg-light text-dark fs-4">
                            {{ $estudiantesActivos->count() }} / {{ $laboratorio->capacidad }}
                        </span>
                    </h3>
                    <small>Ocupación actual</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Lista de Estudiantes Activos --}}
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-people me-2"></i>Estudiantes Actualmente en el Laboratorio
            </h5>
        </div>
        <div class="card-body">
            @if($estudiantesActivos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Cédula</th>
                            <th>Carrera</th>
                            <th>Hora Entrada</th>
                            <th>Tiempo en Lab</th>
                            <th>Método</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudiantesActivos as $acceso)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($acceso->usuario->foto_url)
                                    <img src="{{ asset($acceso->usuario->foto_url) }}"
                                        class="rounded-circle me-2"
                                        style="width: 40px; height: 40px; object-fit: cover;"
                                        alt="Foto">
                                    @else
                                    <div class="rounded-circle bg-secondary me-2 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $acceso->usuario->nombreCompleto }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $acceso->usuario->ciclo_nivel }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $acceso->usuario->cedula }}</td>
                            <td><small>{{ $acceso->usuario->carrera ?? 'N/A' }}</small></td>
                            <td>
                                <strong>{{ date('H:i', strtotime($acceso->hora_entrada)) }}</strong>
                            </td>
                            <td>
                                @php
                                try {
                                $entrada = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $acceso->fecha_entrada . ' ' . $acceso->hora_entrada);
                                $ahora = \Carbon\Carbon::now();
                                $diff = $entrada->diff($ahora);
                                $horas = $diff->h;
                                $minutos = $diff->i;
                                } catch (\Exception $e) {
                                $horas = 0;
                                $minutos = 0;
                                }
                                @endphp
                                <span class="badge bg-info">
                                    @if($horas > 0)
                                    {{ $horas }}h {{ $minutos }}m
                                    @else
                                    {{ $minutos }}m
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($acceso->metodo_registro === 'qr_estudiante')
                                <span class="badge bg-success">
                                    <i class="bi bi-qr-code"></i> QR
                                </span>
                                @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-person"></i> Manual
                                </span>
                                @endif
                            </td>
                            <td>
                                @if(Request::is('profesor/*'))
                                <div class="btn-group btn-group-sm">
                                    {{-- Registrar Salida --}}
                                    <form action="{{ route('profesor.accesos.salida-directa', $acceso->id) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-success"
                                            title="Registrar Salida">
                                            <i class="bi bi-box-arrow-right"></i>
                                        </button>
                                    </form>

                                    {{-- Marcar Ausente --}}
                                    <button type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalAusente{{ $acceso->id }}"
                                        title="Marcar como Ausente">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                                @else
                                <span class="text-muted small">Solo lectura</span>
                                @endif

                                {{-- Modal Marcar Ausente --}}
                                <div class="modal fade" id="modalAusente{{ $acceso->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                    Marcar como Ausente
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('profesor.accesos.marcar-ausente', $acceso->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="alert alert-warning">
                                                        <strong>¿Estás seguro?</strong> Estás a punto de marcar a
                                                        <strong>{{ $acceso->usuario->nombreCompleto }}</strong> como ausente.
                                                        Esto eliminará su registro de acceso.
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Motivo de la Ausencia *</label>
                                                        <textarea name="nota"
                                                            class="form-control"
                                                            rows="3"
                                                            placeholder="Ej: El estudiante no está físicamente presente en el laboratorio"
                                                            required></textarea>
                                                    </div>

                                                    <p class="mb-0 small text-muted">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Esta acción quedará registrada en el sistema con tu usuario.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">Marcar como Ausente</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="text-muted mt-3">No hay estudiantes en el laboratorio actualmente</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Estudiantes Marcados Ausentes HOY --}}
    @php
    $estudiantesAusentes = \App\Models\Acceso::where('laboratorio_id', $laboratorio->id)
    ->where('marcado_ausente', true)
    ->whereDate('fecha_entrada', today())
    ->with('usuario', 'profesor')
    ->orderBy('updated_at', 'desc')
    ->get();
    @endphp

    @if($estudiantesAusentes->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>Estudiantes Marcados como Ausentes Hoy
            </h5>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <i class="bi bi-info-circle me-2"></i>
                Estos estudiantes registraron entrada pero fueron marcados ausentes por no estar físicamente presentes.
            </div>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Hora Registro</th>
                            <th>Motivo</th>
                            <th>Marcado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudiantesAusentes as $ausente)
                        <tr>
                            <td>
                                <strong>{{ $ausente->usuario->nombreCompleto }}</strong><br>
                                <small class="text-muted">{{ $ausente->usuario->cedula }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($ausente->hora_entrada)->format('H:i') }}</td>
                            <td><small>{{ $ausente->nota_ausencia ?? 'Sin motivo' }}</small></td>
                            <td>
                                @if($ausente->profesor_valida_id)
                                @php
                                $prof = \App\Models\Profesor::find($ausente->profesor_valida_id);
                                @endphp
                                <small>{{ $prof ? $prof->nombres : 'N/A' }}</small>
                                @else
                                <small class="text-muted">N/A</small>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Botón Volver --}}
    <div class="text-center mt-4">
        @if(Request::is('admin/*'))
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver al Dashboard
        </a>
        @else
        <a href="{{ route('profesor.accesos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver al Control de Accesos
        </a>
        @endif
    </div>
</div>

<script>
    // Auto-refresh cada 30 segundos
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endsection