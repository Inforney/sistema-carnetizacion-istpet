@extends('layouts.app')

@section('title', 'Actualización Masiva de Niveles')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <div>
            <h2 class="mb-1" style="font-family:'Oswald',sans-serif;color:var(--istpet-azul);">
                <i class="bi bi-arrow-up-circle me-2" style="color:var(--istpet-dorado);"></i>Actualización Masiva de Niveles
            </h2>
            <p class="text-muted mb-0" style="font-size:0.88rem;">
                Actualiza el ciclo/nivel (y carrera) de varios estudiantes en un solo paso
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.importacion.descargar-plantilla-niveles') }}"
               class="btn btn-success btn-sm">
                <i class="bi bi-download me-1"></i>Descargar Plantilla
            </a>
            <a href="{{ route('admin.importacion.index') }}"
               class="btn btn-sm" style="background:var(--istpet-azul);color:#fff;">
                <i class="bi bi-arrow-left me-1"></i>Importación de Estudiantes
            </a>
            <a href="{{ route('admin.estudiantes.index') }}"
               class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-people me-1"></i>Estudiantes
            </a>
        </div>
    </div>
    <div class="mb-4" style="height:3px;background:linear-gradient(90deg,var(--istpet-dorado) 0%,var(--istpet-azul) 60%,transparent 100%);border-radius:2px;"></div>

    <div class="row g-4">

        {{-- Instrucciones --}}
        <div class="col-lg-5">
            <div class="card h-100" style="border:none;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
                <div class="card-header" style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
                    <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-info-circle me-2" style="color:var(--istpet-dorado);"></i>¿Para qué sirve esto?
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted" style="font-size:0.88rem;">
                        Al inicio de cada período académico, los estudiantes suben de nivel. Esta función permite actualizar
                        el <strong>ciclo/nivel</strong> y opcionalmente la <strong>carrera</strong> de todos ellos de una sola vez,
                        sin tener que editar cada perfil manualmente.
                    </p>

                    <div class="alert alert-warning py-2 px-3" style="font-size:0.84rem;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Solo actualiza estudiantes que ya existen.</strong> No crea nuevos registros.
                        Para importar nuevos estudiantes usa la <a href="{{ route('admin.importacion.index') }}">importación masiva</a>.
                    </div>

                    <h6 style="color:var(--istpet-azul);font-family:'Oswald',sans-serif;margin-top:1.2rem;">Pasos:</h6>
                    <ol style="font-size:0.88rem;color:#555;">
                        <li class="mb-2">Descarga la <strong>plantilla Excel</strong> con el botón verde de arriba.</li>
                        <li class="mb-2">Llena la columna <code>cedula</code> con los documentos de los estudiantes
                            que cambian de nivel.</li>
                        <li class="mb-2">Escribe el <strong>nuevo nivel</strong> en la columna <code>ciclo_nivel</code>
                            (usa el desplegable de la plantilla).</li>
                        <li class="mb-2">Si también cambia la carrera, escríbela en la columna <code>carrera</code>;
                            si no, déjala vacía.</li>
                        <li>Sube el archivo con el formulario y revisa el resultado.</li>
                    </ol>

                    <div class="mt-3 p-3 rounded" style="background:rgba(34,44,87,0.05);border-left:3px solid var(--istpet-dorado);">
                        <div style="font-size:0.78rem;color:#666;">
                            <strong style="color:var(--istpet-azul);">Niveles válidos:</strong><br>
                            PRIMER NIVEL · SEGUNDO NIVEL · TERCER NIVEL · CUARTO NIVEL
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="col-lg-7">
            <div class="card" style="border:none;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
                <div class="card-header" style="background:var(--istpet-azul);border-bottom:2px solid var(--istpet-dorado);">
                    <h6 class="mb-0 text-white" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-upload me-2" style="color:var(--istpet-dorado);"></i>Subir Archivo Excel
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.importacion.actualizar-niveles.procesar') }}"
                          method="POST" enctype="multipart/form-data" id="formActualizar">
                        @csrf

                        {{-- Archivo --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold" for="archivo_excel">
                                <i class="bi bi-file-earmark-excel text-success me-2"></i>
                                Archivo Excel <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                   class="form-control @error('archivo_excel') is-invalid @enderror"
                                   name="archivo_excel"
                                   id="archivo_excel"
                                   accept=".xlsx,.xls,.csv"
                                   required>
                            <small class="text-muted">Formatos: .xlsx, .xls, .csv | Máximo 10 MB</small>
                            @error('archivo_excel')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Formato de columnas --}}
                        <div class="mb-4">
                            <h6 style="color:var(--istpet-azul);font-size:0.88rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">
                                Columnas del archivo
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0" style="font-size:0.82rem;">
                                    <thead>
                                        <tr style="background:var(--istpet-azul);color:#fff;">
                                            <th>Col</th>
                                            <th>Campo</th>
                                            <th>Ejemplo</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>A</strong></td>
                                            <td><code>cedula</code></td>
                                            <td>1750123456</td>
                                            <td><span class="badge bg-danger" style="font-size:0.7rem;">Obligatorio</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>B</strong></td>
                                            <td><code>ciclo_nivel</code></td>
                                            <td>SEGUNDO NIVEL</td>
                                            <td><span class="badge bg-danger" style="font-size:0.7rem;">Obligatorio</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>C</strong></td>
                                            <td><code>carrera</code></td>
                                            <td>Redes y Telecom.</td>
                                            <td><span class="badge bg-secondary" style="font-size:0.7rem;">Opcional</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted">La primera fila debe ser el encabezado (se ignora automáticamente).</small>
                        </div>

                        {{-- Advertencia --}}
                        <div class="alert py-2 px-3" style="background:rgba(196,168,87,0.12);border:1px solid rgba(196,168,87,0.4);font-size:0.84rem;">
                            <i class="bi bi-clock me-1" style="color:var(--istpet-dorado);"></i>
                            No cierres la ventana mientras se procesa el archivo.
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                    class="btn py-2 fw-bold"
                                    style="background:var(--istpet-dorado);color:var(--istpet-azul);font-family:'Oswald',sans-serif;letter-spacing:0.5px;"
                                    id="btnProcesar">
                                <i class="bi bi-arrow-up-circle me-2"></i>Actualizar Niveles
                            </button>
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
document.getElementById('formActualizar').addEventListener('submit', function() {
    const btn = document.getElementById('btnProcesar');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
});
</script>
@endsection
