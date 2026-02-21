<div class="col-lg-4 col-md-6 mb-4">
    <div class="card h-100 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: #1a2342;">
            <div>
                <h5 class="mb-0 text-white">
                    @if($lab->tipo === 'laboratorio')
                    <i class="bi bi-cpu me-2"></i>
                    @else
                    <i class="bi bi-projector me-2"></i>
                    @endif
                    {{ $lab->nombre }}
                </h5>
                <small class="text-white-50">
                    {{ $lab->tipo === 'laboratorio' ? 'Laboratorio Técnico' : 'Aula Interactiva' }}
                </small>
            </div>
            <span class="badge bg-{{ $lab->estado === 'activo' ? 'success' : ($lab->estado === 'mantenimiento' ? 'warning' : 'danger') }}">
                {{ ucfirst($lab->estado) }}
            </span>
        </div>
        <div class="card-body">
            {{-- QR Code --}}
            <div class="text-center mb-3">
                {!! QrCode::size(180)->generate($lab->codigo_qr_lab) !!}
            </div>

            {{-- Información --}}
            <div class="mb-2">
                <small class="text-muted"><i class="bi bi-qr-code me-1"></i>CÓDIGO:</small>
                <p class="mb-0 small"><code>{{ $lab->codigo_qr_lab }}</code></p>
            </div>

            <div class="mb-2">
                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>UBICACIÓN:</small>
                <p class="mb-0">{{ $lab->ubicacion }}</p>
            </div>

            @if($lab->descripcion)
            <div class="mb-2">
                <small class="text-muted"><i class="bi bi-info-circle me-1"></i>DESCRIPCIÓN:</small>
                <p class="mb-0 small">{{ $lab->descripcion }}</p>
            </div>
            @endif

            <div class="row mt-3">
                <div class="col-6">
                    <small class="text-muted">CAPACIDAD:</small>
                    <p class="mb-0"><strong>{{ $lab->capacidad }}</strong> estudiantes</p>
                </div>
                <div class="col-6">
                    <small class="text-muted">OCUPACIÓN:</small>
                    <p class="mb-0">
                        <span class="badge bg-{{ $lab->accesos_count > 0 ? 'success' : 'secondary' }}">
                            {{ $lab->accesos_count }}/{{ $lab->capacidad }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between gap-2">
                <a href="{{ route('admin.laboratorios.generar-qr', $lab->id) }}"
                    class="btn btn-sm btn-outline-success flex-fill"
                    title="Descargar QR">
                    <i class="bi bi-download"></i> QR
                </a>
                <a href="{{ route('admin.laboratorios.edit', $lab->id) }}"
                    class="btn btn-sm btn-outline-primary flex-fill"
                    title="Editar">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <form action="{{ route('admin.laboratorios.destroy', $lab->id) }}"
                    method="POST"
                    class="flex-fill"
                    onsubmit="return confirm('¿Estás seguro de eliminar?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="btn btn-sm btn-outline-danger w-100"
                        title="Eliminar">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>