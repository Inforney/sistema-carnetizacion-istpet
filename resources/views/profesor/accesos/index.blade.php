@extends('layouts.app')

@section('title', 'Control de Accesos')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4">
        <i class="bi bi-door-open me-2"></i>Control de Accesos a Laboratorios
    </h2>

    {{-- Tarjetas de Laboratorios --}}
    <div class="row mb-4">
        @if(isset($laboratorios) && $laboratorios->count() > 0)
        @foreach($laboratorios as $lab)
        @php
        $ocupacion = $lab->accesos()
        ->whereNull('hora_salida')
        ->where('marcado_ausente', false)
        ->whereDate('fecha_entrada', today())
        ->count();
        $porcentaje = $lab->capacidad > 0 ? round(($ocupacion / $lab->capacidad) * 100) : 0;
        @endphp
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $lab->nombre }}</h5>
                    <p class="text-muted small mb-2">{{ $lab->ubicacion }}</p>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Ocupación:</span>
                        <strong>{{ $ocupacion }} / {{ $lab->capacidad }}</strong>
                    </div>

                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar {{ $porcentaje > 80 ? 'bg-danger' : ($porcentaje > 50 ? 'bg-warning' : 'bg-success') }}"
                            style="width: {{ $porcentaje }}%">
                            {{ $porcentaje }}%
                        </div>
                    </div>

                    <a href="{{ route('profesor.accesos.estudiantes-lab', $lab->id) }}"
                        class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-people me-2"></i>Ver Estudiantes
                    </a>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="col-12">
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                No hay laboratorios disponibles.
            </div>
        </div>
        @endif
    </div>

    {{-- Formularios de Registro --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Registrar Entrada
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profesor.accesos.registrar-entrada') }}" method="POST">
                        @csrf
                        <div class="mb-3 position-relative">
                            <label class="form-label">Buscar Estudiante (Cédula, Nombre o Apellido)</label>
                            <input type="text"
                                id="buscarEntrada"
                                class="form-control"
                                placeholder="Escribe para buscar..."
                                autocomplete="off">
                            <input type="hidden" name="estudiante_cedula" id="cedulaEntrada" required>
                            <div id="resultadosEntrada" class="border rounded mt-2 shadow-sm" style="display: none; max-height: 300px; overflow-y: auto; position: absolute; z-index: 1000; background: white; width: calc(100% - 30px);"></div>
                            <small id="errorEntrada" class="text-danger" style="display: none;"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Laboratorio</label>
                            <select name="laboratorio_id" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                @if(isset($laboratorios))
                                @foreach($laboratorios as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->nombre }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-2"></i>Registrar Entrada
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-box-arrow-right me-2"></i>Registrar Salida
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profesor.accesos.registrar-salida') }}" method="POST">
                        @csrf
                        <div class="mb-3 position-relative">
                            <label class="form-label">Buscar Estudiante (Cédula, Nombre o Apellido)</label>
                            <input type="text"
                                id="buscarSalida"
                                class="form-control"
                                placeholder="Escribe para buscar..."
                                autocomplete="off">
                            <input type="hidden" name="estudiante_cedula" id="cedulaSalida" required>
                            <div id="resultadosSalida" class="border rounded mt-2 shadow-sm" style="display: none; max-height: 300px; overflow-y: auto; position: absolute; z-index: 1000; background: white; width: calc(100% - 30px);"></div>
                            <small id="errorSalida" class="text-danger" style="display: none;"></small>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 mt-5">
                            <i class="bi bi-box-arrow-left me-2"></i>Registrar Salida
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Accesos de Hoy --}}
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>Accesos de Hoy
            </h5>
        </div>
        <div class="card-body">
            @if(isset($accesosHoy) && $accesosHoy->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Laboratorio</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Duración</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accesosHoy as $acceso)
                        <tr>
                            <td>
                                <strong>{{ $acceso->usuario->nombreCompleto }}</strong>
                                <br>
                                <small class="text-muted">{{ $acceso->usuario->cedula }}</small>
                            </td>
                            <td>{{ $acceso->laboratorio->nombre }}</td>
                            <td>{{ date('H:i', strtotime($acceso->hora_entrada)) }}</td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger">Ausente</span>
                                @elseif($acceso->hora_salida)
                                {{ date('H:i', strtotime($acceso->hora_salida)) }}
                                @else
                                <span class="badge bg-warning">Sin salida</span>
                                @endif
                            </td>
                            <td>
                                @if($acceso->marcado_ausente)
                                <span class="badge bg-danger">Marcado Ausente</span>
                                @elseif($acceso->duracion_formateada)
                                <span class="badge bg-info">{{ $acceso->duracion_formateada }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if(!$acceso->hora_salida)
                                <form action="{{ route('profesor.accesos.salida-directa', $acceso->id) }}"
                                    method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        title="Registrar Salida">
                                        <i class="bi bi-box-arrow-right"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $accesosHoy->links() }}
            @else
            <p class="text-muted text-center py-4">No hay accesos registrados hoy</p>
            @endif
        </div>
    </div>
</div>

<script>
    // URL base
    const baseUrl = '{{ url("/") }}';

    // Búsqueda para ENTRADA
    let timeoutEntrada;
    const inputEntrada = document.getElementById('buscarEntrada');
    const cedulaEntrada = document.getElementById('cedulaEntrada');
    const resultadosEntrada = document.getElementById('resultadosEntrada');
    const errorEntrada = document.getElementById('errorEntrada');

    inputEntrada.addEventListener('input', function() {
        clearTimeout(timeoutEntrada);
        const termino = this.value.trim();

        if (termino.length < 2) {
            resultadosEntrada.style.display = 'none';
            cedulaEntrada.value = '';
            errorEntrada.style.display = 'none';
            return;
        }

        timeoutEntrada = setTimeout(() => {
            fetch(`${baseUrl}/profesor/buscar-estudiante?term=${encodeURIComponent(termino)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta');
                    return response.json();
                })
                .then(data => {
                    resultadosEntrada.innerHTML = '';
                    errorEntrada.style.display = 'none';

                    if (data.length > 0) {
                        data.forEach(est => {
                            const div = document.createElement('div');
                            div.className = 'p-2 border-bottom';
                            div.style.cursor = 'pointer';
                            div.innerHTML = `
                            <strong>${est.nombreCompleto}</strong><br>
                            <small class="text-muted">Cédula: ${est.cedula}</small>
                        `;
                            div.addEventListener('click', () => {
                                inputEntrada.value = est.nombreCompleto;
                                cedulaEntrada.value = est.cedula;
                                resultadosEntrada.style.display = 'none';
                            });
                            div.addEventListener('mouseenter', () => div.style.background = '#f0f0f0');
                            div.addEventListener('mouseleave', () => div.style.background = 'white');
                            resultadosEntrada.appendChild(div);
                        });
                        resultadosEntrada.style.display = 'block';
                    } else {
                        resultadosEntrada.innerHTML = '<div class="p-2 text-muted">No se encontraron estudiantes</div>';
                        resultadosEntrada.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorEntrada.textContent = 'Error al buscar. Intenta de nuevo.';
                    errorEntrada.style.display = 'block';
                    resultadosEntrada.style.display = 'none';
                });
        }, 300);
    });

    // Búsqueda para SALIDA
    let timeoutSalida;
    const inputSalida = document.getElementById('buscarSalida');
    const cedulaSalida = document.getElementById('cedulaSalida');
    const resultadosSalida = document.getElementById('resultadosSalida');
    const errorSalida = document.getElementById('errorSalida');

    inputSalida.addEventListener('input', function() {
        clearTimeout(timeoutSalida);
        const termino = this.value.trim();

        if (termino.length < 2) {
            resultadosSalida.style.display = 'none';
            cedulaSalida.value = '';
            errorSalida.style.display = 'none';
            return;
        }

        timeoutSalida = setTimeout(() => {
            fetch(`${baseUrl}/profesor/buscar-estudiante?term=${encodeURIComponent(termino)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta');
                    return response.json();
                })
                .then(data => {
                    resultadosSalida.innerHTML = '';
                    errorSalida.style.display = 'none';

                    if (data.length > 0) {
                        data.forEach(est => {
                            const div = document.createElement('div');
                            div.className = 'p-2 border-bottom';
                            div.style.cursor = 'pointer';
                            div.innerHTML = `
                            <strong>${est.nombreCompleto}</strong><br>
                            <small class="text-muted">Cédula: ${est.cedula}</small>
                        `;
                            div.addEventListener('click', () => {
                                inputSalida.value = est.nombreCompleto;
                                cedulaSalida.value = est.cedula;
                                resultadosSalida.style.display = 'none';
                            });
                            div.addEventListener('mouseenter', () => div.style.background = '#f0f0f0');
                            div.addEventListener('mouseleave', () => div.style.background = 'white');
                            resultadosSalida.appendChild(div);
                        });
                        resultadosSalida.style.display = 'block';
                    } else {
                        resultadosSalida.innerHTML = '<div class="p-2 text-muted">No se encontraron estudiantes</div>';
                        resultadosSalida.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorSalida.textContent = 'Error al buscar. Intenta de nuevo.';
                    errorSalida.style.display = 'block';
                    resultadosSalida.style.display = 'none';
                });
        }, 300);
    });

    // Cerrar al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!inputEntrada.contains(e.target) && !resultadosEntrada.contains(e.target)) {
            resultadosEntrada.style.display = 'none';
        }
        if (!inputSalida.contains(e.target) && !resultadosSalida.contains(e.target)) {
            resultadosSalida.style.display = 'none';
        }
    });
</script>
@endsection