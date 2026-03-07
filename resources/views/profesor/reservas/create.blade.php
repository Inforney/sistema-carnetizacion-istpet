@extends('layouts.app')

@section('title', 'Nueva Reserva de Laboratorio')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex align-items-center mb-4 gap-3">
                <a href="{{ route('profesor.reservas.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0 fw-bold" style="color:var(--istpet-azul);">
                        <i class="bi bi-calendar-plus me-2" style="color:var(--istpet-dorado);"></i>Nueva Reserva de Laboratorio
                    </h4>
                    <small class="text-muted">Agenda tu clase o práctica antes de asistir al laboratorio</small>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('profesor.reservas.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <h6 class="fw-bold border-bottom pb-2" style="color:var(--istpet-azul);">
                                <i class="bi bi-display me-1"></i>Laboratorio y Horario
                            </h6>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Laboratorio <span class="text-danger">*</span></label>
                                <select name="laboratorio_id" id="laboratorio_id" class="form-select @error('laboratorio_id') is-invalid @enderror" required>
                                    <option value="">Selecciona un laboratorio</option>
                                    @foreach($laboratorios as $lab)
                                    <option value="{{ $lab->id }}" {{ old('laboratorio_id') == $lab->id ? 'selected' : '' }}>
                                        {{ $lab->nombre }}
                                        @if($lab->ubicacion) — {{ $lab->ubicacion }} @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('laboratorio_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha <span class="text-danger">*</span></label>
                                <input type="date" name="fecha" id="fecha_reserva"
                                       class="form-control @error('fecha') is-invalid @enderror"
                                       value="{{ old('fecha', date('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Hora de inicio <span class="text-danger">*</span></label>
                                <input type="time" name="hora_inicio" id="hora_inicio"
                                       class="form-control @error('hora_inicio') is-invalid @enderror"
                                       value="{{ old('hora_inicio', '08:00') }}" required>
                                @error('hora_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Hora de fin <span class="text-danger">*</span></label>
                                <input type="time" name="hora_fin" id="hora_fin"
                                       class="form-control @error('hora_fin') is-invalid @enderror"
                                       value="{{ old('hora_fin', '10:00') }}" required>
                                @error('hora_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Duración calculada</label>
                                <div id="duracion_display"
                                     class="form-control-plaintext fw-bold"
                                     style="color:var(--istpet-azul); padding-top: 0.375rem;">
                                    2 horas
                                </div>
                            </div>
                        </div>

                        {{-- Disponibilidad AJAX --}}
                        <div id="disponibilidad_box" class="alert alert-info d-none py-2 mb-3" style="font-size:0.88rem;">
                            <i class="bi bi-calendar2-check me-1"></i>
                            <strong>Reservas existentes ese día en ese laboratorio:</strong>
                            <ul id="disponibilidad_lista" class="mb-0 mt-1"></ul>
                        </div>
                        <div id="sin_reservas_box" class="alert alert-success d-none py-2 mb-3" style="font-size:0.88rem;">
                            <i class="bi bi-check-circle me-1"></i>Laboratorio disponible ese día. ¡Sin conflictos!
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold border-bottom pb-2 mt-3" style="color:var(--istpet-azul);">
                                <i class="bi bi-book me-1"></i>Información de la Clase
                            </h6>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Materia / Asignatura</label>
                                <input type="text" name="materia"
                                       class="form-control @error('materia') is-invalid @enderror"
                                       value="{{ old('materia') }}"
                                       placeholder="Ej: Redes de Computadoras">
                                @error('materia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Descripción adicional</label>
                                <input type="text" name="descripcion"
                                       class="form-control @error('descripcion') is-invalid @enderror"
                                       value="{{ old('descripcion') }}"
                                       placeholder="Práctica, examen, proyecto, etc.">
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-light border" style="font-size:0.85rem;">
                            <i class="bi bi-info-circle me-1 text-primary"></i>
                            <strong>Profesor:</strong> {{ $profesor->nombreCompleto }} —
                            <strong>Cédula:</strong> {{ $profesor->cedula }}
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="bi bi-calendar-check me-2"></i>Guardar Reserva
                            </button>
                            <a href="{{ route('profesor.reservas.index') }}" class="btn btn-outline-secondary px-4">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const horaInicio = document.getElementById('hora_inicio');
    const horaFin    = document.getElementById('hora_fin');
    const labSelect  = document.getElementById('laboratorio_id');
    const fechaInput = document.getElementById('fecha_reserva');
    const durDisplay = document.getElementById('duracion_display');
    const dispBox    = document.getElementById('disponibilidad_box');
    const lista      = document.getElementById('disponibilidad_lista');
    const sinBox     = document.getElementById('sin_reservas_box');

    // Calcular duración mostrada
    function actualizarDuracion() {
        const [hI, mI] = horaInicio.value.split(':').map(Number);
        const [hF, mF] = horaFin.value.split(':').map(Number);
        if (isNaN(hI) || isNaN(hF)) return;
        const mins = (hF * 60 + mF) - (hI * 60 + mI);
        if (mins <= 0) {
            durDisplay.textContent = '⚠ Hora fin debe ser posterior a inicio';
            durDisplay.style.color = 'red';
        } else {
            const h = Math.floor(mins / 60);
            const m = mins % 60;
            durDisplay.textContent = (h > 0 ? h + 'h ' : '') + (m > 0 ? m + ' min' : '');
            durDisplay.style.color = 'var(--istpet-azul)';
        }
    }

    // Consultar disponibilidad
    function consultarDisponibilidad() {
        const labId = labSelect.value;
        const fecha = fechaInput.value;
        if (!labId || !fecha) return;

        fetch(`{{ route('profesor.reservas.disponibilidad') }}?laboratorio_id=${labId}&fecha=${fecha}`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    dispBox.classList.add('d-none');
                    sinBox.classList.remove('d-none');
                } else {
                    sinBox.classList.add('d-none');
                    lista.innerHTML = data.map(r =>
                        `<li><i class="bi bi-clock"></i> ${r.hora_inicio} – ${r.hora_fin} | <strong>${r.materia}</strong> — ${r.profesor}</li>`
                    ).join('');
                    dispBox.classList.remove('d-none');
                }
            });
    }

    horaInicio.addEventListener('change', actualizarDuracion);
    horaFin.addEventListener('change', actualizarDuracion);
    labSelect.addEventListener('change', consultarDisponibilidad);
    fechaInput.addEventListener('change', consultarDisponibilidad);

    // Ejecutar al cargar
    actualizarDuracion();
</script>
@endsection
