@extends('layouts.app')

@section('title', 'Registrar Acceso al Laboratorio')

@section('content')
<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-6">
            {{-- Acceso Activo --}}
            @if($accesoActivo)
            <div class="alert alert-info mb-4">
                <h5 class="alert-heading">
                    <i class="bi bi-clock-history me-2"></i>Tienes un acceso activo
                </h5>
                <p class="mb-2">
                    <strong>Laboratorio:</strong> {{ $accesoActivo->laboratorio->nombre }}<br>
                    <strong>Entrada:</strong> {{ date('H:i', strtotime($accesoActivo->hora_entrada)) }}
                </p>
                <p class="mb-0 small">Escanea el QR del mismo laboratorio para registrar tu salida.</p>
            </div>
            @endif

            {{-- Card Principal --}}
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-qr-code-scan me-2"></i>Escanear QR del Laboratorio
                    </h4>
                </div>

                <div class="card-body p-4">
                    {{-- Instrucciones --}}
                    <div class="alert alert-light border mb-4">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-info-circle text-primary me-2"></i>¿Cómo funciona?
                        </h6>
                        <ol class="mb-0 small">
                            <li>Busca el código QR pegado en la puerta del laboratorio</li>
                            <li>Click en "Activar Cámara" y apunta al QR</li>
                            <li>El sistema registrará tu <strong>entrada</strong> automáticamente</li>
                            <li>Al salir, vuelve a escanear el mismo QR para registrar tu <strong>salida</strong></li>
                        </ol>
                    </div>

                    {{-- Área de Escaneo --}}
                    <div class="text-center mb-4">
                        <div id="qr-reader" style="width: 100%; display: none;"></div>

                        <div id="qr-placeholder" class="p-5 bg-light rounded border">
                            <i class="bi bi-camera display-1 text-muted mb-3"></i>
                            <p class="text-muted mb-3">Presiona el botón para activar la cámara</p>
                            <button type="button" id="start-scan" class="btn btn-primary btn-lg">
                                <i class="bi bi-camera-fill me-2"></i>Activar Cámara
                            </button>
                        </div>
                    </div>

                    {{-- Resultado --}}
                    <div id="result-container" style="display: none;">
                        <div class="alert mb-0" id="result-alert"></div>
                    </div>
                </div>

                <div class="card-footer text-center">
                    <small class="text-muted">
                        <i class="bi bi-shield-check me-1"></i>
                        Tus accesos quedan registrados de forma segura
                    </small>
                </div>
            </div>

            {{-- Botón Ver Historial --}}
            <div class="text-center mt-3">
                <a href="{{ route('estudiante.acceso.historial') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-clock-history me-2"></i>Ver Mi Historial de Accesos
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Librerías de QR Scanner --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let html5QrCode = null;
    let scanning = false;

    document.getElementById('start-scan').addEventListener('click', function() {
        if (scanning) return;

        document.getElementById('qr-placeholder').style.display = 'none';
        document.getElementById('qr-reader').style.display = 'block';

        html5QrCode = new Html5Qrcode("qr-reader");

        html5QrCode.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            },
            onScanSuccess,
            onScanFailure
        ).then(() => {
            scanning = true;
        }).catch((err) => {
            console.error('Error al iniciar escáner:', err);
            alert('No se pudo acceder a la cámara. Por favor, verifica los permisos.');
            resetScanner();
        });
    });

    function onScanSuccess(decodedText, decodedResult) {
        // Detener el escáner
        html5QrCode.stop().then(() => {
            scanning = false;
            procesarQR(decodedText);
        });
    }

    function onScanFailure(error) {
        // No hacer nada, solo esperar
    }

    function procesarQR(codigoQR) {
        // Mostrar loading
        showResult('info', '<i class="spinner-border spinner-border-sm me-2"></i>Procesando...', false);

        // Enviar al servidor
        fetch('{{ route("estudiante.acceso.procesar-qr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    codigo_qr: codigoQR
                })
            })
            .then(response => {
                // Verificar si la respuesta es OK
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Error del servidor');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data); // DEBUG

                if (data.success) {
                    let mensaje = '';

                    if (data.accion === 'entrada') {
                        mensaje = `
                    <h5 class="alert-heading">
                        <i class="bi bi-check-circle me-2"></i>¡Entrada Registrada!
                    </h5>
                    <p class="mb-2"><strong>Laboratorio:</strong> ${data.laboratorio}</p>
                    <p class="mb-2"><strong>Ubicación:</strong> ${data.ubicacion}</p>
                    <p class="mb-2"><strong>Hora:</strong> ${data.hora_entrada}</p>
                    <p class="mb-0 small">Ocupación: ${data.ocupacion} estudiantes</p>
                `;
                        showResult('success', mensaje, true);
                    } else {
                        mensaje = `
                    <h5 class="alert-heading">
                        <i class="bi bi-box-arrow-right me-2"></i>¡Salida Registrada!
                    </h5>
                    <p class="mb-2"><strong>Laboratorio:</strong> ${data.laboratorio}</p>
                    <p class="mb-2"><strong>Entrada:</strong> ${data.hora_entrada}</p>
                    <p class="mb-2"><strong>Salida:</strong> ${data.hora_salida}</p>
                    <p class="mb-0"><strong>Duración:</strong> ${data.duracion}</p>
                `;
                        showResult('success', mensaje, true);
                    }

                    // Recargar después de 3 segundos
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);

                } else {
                    showResult('danger', `<strong>Error:</strong> ${data.message}`, true);
                }
            })
            .catch(error => {
                console.error('Error completo:', error);
                showResult('danger', `<strong>Error:</strong> ${error.message || 'No se pudo procesar el código QR. Intenta de nuevo.'}`, true);
            });
    }

    function showResult(type, message, showRetry) {
        const resultContainer = document.getElementById('result-container');
        const resultAlert = document.getElementById('result-alert');

        resultAlert.className = `alert alert-${type}`;
        resultAlert.innerHTML = message;

        if (showRetry && type === 'danger') {
            resultAlert.innerHTML += `
            <hr>
            <button type="button" class="btn btn-sm btn-${type}" onclick="resetScanner()">
                <i class="bi bi-arrow-clockwise me-1"></i>Intentar de Nuevo
            </button>
        `;
        }

        resultContainer.style.display = 'block';
    }

    function resetScanner() {
        document.getElementById('qr-reader').style.display = 'none';
        document.getElementById('qr-placeholder').style.display = 'block';
        document.getElementById('result-container').style.display = 'none';
        scanning = false;
    }
</script>
@endsection