@extends('layouts.app')

@section('title', 'Importación Masiva de Estudiantes')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-file-earmark-excel me-2"></i>Importación Masiva desde Excel</h2>
            <p class="text-muted mb-0">Importa múltiples estudiantes desde un archivo Excel</p>
        </div>
        <div>
            <a href="{{ route('admin.importacion.descargar-plantilla') }}" class="btn btn-success">
                <i class="bi bi-download me-2"></i>Descargar Plantilla Excel
            </a>
            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver a Estudiantes
            </a>
        </div>
    </div>

    {{-- Instrucciones --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Instrucciones</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2"><strong>Descarga la plantilla Excel</strong> haciendo click en el botón verde de arriba</li>
                        <li class="mb-2"><strong>Llena la plantilla</strong> con los datos de los estudiantes (una fila por estudiante)</li>
                        <li class="mb-2"><strong>Prepara las fotos</strong> (opcional):
                            <ul>
                                <li>Nombra cada foto con la cédula del estudiante (ej: <code>1750123456.jpg</code>)</li>
                                <li>Comprime todas las fotos en un archivo <code>.zip</code></li>
                            </ul>
                        </li>
                        <li class="mb-0"><strong>Sube ambos archivos</strong> usando el formulario de abajo</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario de importación --}}
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header text-white" style="background: #1a2342;">
                    <h5 class="mb-0"><i class="bi bi-upload me-2"></i>Subir Archivos</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.importacion.importar') }}" method="POST" enctype="multipart/form-data" id="formImportacion">
                        @csrf

                        {{-- Archivo Excel --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-file-earmark-excel text-success me-2"></i>
                                Archivo Excel <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                class="form-control @error('archivo_excel') is-invalid @enderror"
                                name="archivo_excel"
                                accept=".xlsx,.xls,.csv"
                                required>
                            <small class="text-muted">Formatos: .xlsx, .xls, .csv | Tamaño máximo: 10MB</small>
                            @error('archivo_excel')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ZIP de fotos --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-images text-primary me-2"></i>
                                Archivo ZIP con Fotos <span class="text-muted">(Opcional)</span>
                            </label>
                            <input type="file"
                                class="form-control @error('fotos_zip') is-invalid @enderror"
                                name="fotos_zip"
                                accept=".zip">
                            <small class="text-muted">Formato: .zip | Tamaño máximo: 100MB</small>
                            @error('fotos_zip')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Generar carnets --}}
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="generar_carnets"
                                    id="generar_carnets"
                                    value="1"
                                    checked>
                                <label class="form-check-label fw-bold" for="generar_carnets">
                                    <i class="bi bi-credit-card text-warning me-2"></i>
                                    Generar carnets automáticamente
                                </label>
                                <small class="d-block text-muted">
                                    Si está activado, se generará un carnet digital para cada estudiante importado
                                </small>
                            </div>
                        </div>

                        {{-- Advertencia --}}
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Importante:</strong> Este proceso puede tardar varios minutos si el archivo es grande.
                            No cierres esta ventana mientras se procesa.
                        </div>

                        {{-- Botones --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg text-white" style="background: #1a2342;" id="btnImportar">
                                <i class="bi bi-cloud-upload me-2"></i>Iniciar Importación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Formato de columnas --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-table me-2"></i>Formato de Columnas del Excel</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Columna</th>
                                    <th>Descripción</th>
                                    <th>Ejemplo</th>
                                    <th>Obligatorio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>nombres</code></td>
                                    <td>Nombres completos del estudiante</td>
                                    <td>Juan Carlos</td>
                                    <td><span class="badge bg-danger">Sí</span></td>
                                </tr>
                                <tr>
                                    <td><code>apellidos</code></td>
                                    <td>Apellidos completos</td>
                                    <td>Pérez López</td>
                                    <td><span class="badge bg-danger">Sí</span></td>
                                </tr>
                                <tr>
                                    <td><code>cedula</code></td>
                                    <td>Cédula ecuatoriana (10 dígitos)</td>
                                    <td>1750123456</td>
                                    <td><span class="badge bg-danger">Sí</span></td>
                                </tr>
                                <tr>
                                    <td><code>tipo_documento</code></td>
                                    <td>Tipo: cedula o pasaporte</td>
                                    <td>cedula</td>
                                    <td><span class="badge bg-secondary">No</span></td>
                                </tr>
                                <tr>
                                    <td><code>correo_institucional</code></td>
                                    <td>Email del instituto</td>
                                    <td>juan.perez@istpet.edu.ec</td>
                                    <td><span class="badge bg-danger">Sí</span></td>
                                </tr>
                                <tr>
                                    <td><code>celular</code></td>
                                    <td>Celular (10 dígitos, inicia con 09)</td>
                                    <td>0987654321</td>
                                    <td><span class="badge bg-danger">Sí</span></td>
                                </tr>
                                <tr>
                                    <td><code>carrera</code></td>
                                    <td>Nombre de la carrera</td>
                                    <td>Desarrollo de Software</td>
                                    <td><span class="badge bg-danger">Sí</span></td>
                                </tr>
                                <tr>
                                    <td><code>ciclo_nivel</code></td>
                                    <td>Nivel académico actual</td>
                                    <td>TERCER NIVEL</td>
                                    <td><span class="badge bg-secondary">No</span></td>
                                </tr>
                                <tr>
                                    <td><code>nacionalidad</code></td>
                                    <td>País de origen</td>
                                    <td>Ecuatoriana</td>
                                    <td><span class="badge bg-secondary">No</span></td>
                                </tr>
                                <tr>
                                    <td><code>foto_filename</code></td>
                                    <td>Nombre del archivo de foto en el ZIP</td>
                                    <td>1750123456.jpg</td>
                                    <td><span class="badge bg-secondary">No</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('formImportacion').addEventListener('submit', function() {
        const btn = document.getElementById('btnImportar');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
    });
</script>
@endsection