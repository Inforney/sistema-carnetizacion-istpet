<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - ISTPET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #1a2342;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .register-wrapper {
            max-width: 700px;
            margin: 0 auto;
        }

        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            background: #C4A857;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .brand-logo i {
            font-size: 24px;
            color: #1a2342;
        }

        .brand h1 {
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .brand p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(196, 168, 87, 0.2);
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-info {
            background: rgba(196, 168, 87, 0.15);
            color: #C4A857;
            border: 1px solid rgba(196, 168, 87, 0.3);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .alert-warning {
            background: rgba(251, 191, 36, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .section-title {
            font-size: 15px;
            font-weight: 600;
            color: #C4A857;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid rgba(196, 168, 87, 0.3);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 5px;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 10px 12px;
            font-size: 14px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 7px;
            transition: all 0.2s;
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: #C4A857;
            box-shadow: 0 0 0 3px rgba(196, 168, 87, 0.15);
            background: rgba(255, 255, 255, 0.12);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-select {
            color: rgba(255, 255, 255, 0.9);
        }

        .form-select option {
            background: #1a2342;
            color: #ffffff;
        }

        .form-text {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 3px;
            display: block;
        }

        .btn-check:checked+.btn-outline-primary {
            background: #C4A857;
            border-color: #C4A857;
            color: #1a2342;
        }

        .btn-outline-primary {
            color: #C4A857;
            border-color: rgba(196, 168, 87, 0.5);
        }

        .btn-outline-primary:hover {
            background: rgba(196, 168, 87, 0.2);
            border-color: #C4A857;
            color: #C4A857;
        }

        #video,
        #foto_preview {
            border: 2px solid #C4A857;
            border-radius: 8px;
        }

        .progress {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-primary {
            background: #C4A857;
            border: none;
            color: #1a2342;
            font-weight: 600;
            padding: 11px 24px;
            border-radius: 7px;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #d4b866;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(196, 168, 87, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            padding: 11px 24px;
            border-radius: 7px;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            color: #ffffff;
        }

        .btn-success {
            background: #10b981;
            border: none;
        }

        .btn-warning {
            background: #f59e0b;
            border: none;
        }

        .invalid-feedback {
            color: #f87171;
            font-size: 12px;
        }

        .text-success {
            color: #10b981 !important;
        }

        .text-muted {
            color: rgba(255, 255, 255, 0.5) !important;
        }
    </style>
</head>

<body>
    <div class="register-wrapper">
        {{-- Brand --}}
        <div class="brand">
            <div class="brand-logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <h1>Instituto Superior Tecnológico Público Mayor Pedro Traversari</h1>
            <p>Sistema de Carnetización Estudiantil</p>
        </div>

        <div class="register-card">
            @if($errors->any())
            <div class="alert alert-danger">
                <strong>¡Error!</strong> Por favor corrige los siguientes problemas:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="alert alert-info">
                <strong>Importante:</strong> Al registrarte, se generará automáticamente tu carnet digital con un código QR único.
            </div>

            <form action="{{ route('registro.post') }}" method="POST" enctype="multipart/form-data" id="registroForm">
                @csrf

                {{-- Fotografía --}}
                <div class="section-title">Fotografía</div>

                <div class="form-group">
                    <div class="btn-group w-100 mb-3" role="group">
                        <input type="radio" class="btn-check" name="foto_opcion" id="opcion_subir" value="subir" checked>
                        <label class="btn btn-outline-primary" for="opcion_subir">
                            <i class="bi bi-upload me-2"></i>Subir Foto
                        </label>

                        <input type="radio" class="btn-check" name="foto_opcion" id="opcion_camara" value="camara">
                        <label class="btn btn-outline-primary" for="opcion_camara">
                            <i class="bi bi-camera me-2"></i>Tomar Foto
                        </label>
                    </div>

                    <div id="div_subir_foto">
                        <input type="file"
                            name="foto"
                            id="foto"
                            class="form-control"
                            accept="image/jpeg,image/png,image/jpg">
                        <small class="form-text">
                            <i class="bi bi-info-circle"></i>
                            Foto tipo carnet (fondo claro, rostro visible). JPG, PNG. Máx: 2MB.
                        </small>
                    </div>

                    <div id="div_camara_foto" style="display: none;">
                        <div class="text-center">
                            <video id="video" width="320" height="240" autoplay></video>
                            <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
                            <br>
                            <button type="button" class="btn btn-success mt-2" id="btn_tomar_foto">
                                <i class="bi bi-camera-fill me-2"></i>Capturar Foto
                            </button>
                            <button type="button" class="btn btn-warning mt-2" id="btn_retomar_foto" style="display: none;">
                                <i class="bi bi-arrow-clockwise me-2"></i>Tomar Otra
                            </button>
                        </div>
                        <input type="hidden" name="foto_base64" id="foto_base64">
                        <img id="foto_preview" style="max-width: 200px; margin-top: 10px; display: none;">
                    </div>
                </div>

                {{-- Datos Personales --}}
                <div class="section-title mt-4">Datos Personales</div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nombres *</label>
                            <input type="text"
                                name="nombres"
                                class="form-control"
                                value="{{ old('nombres') }}"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Apellidos *</label>
                            <input type="text"
                                name="apellidos"
                                class="form-control"
                                value="{{ old('apellidos') }}"
                                required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Tipo de Documento *</label>
                            <select name="tipo_documento"
                                id="tipo_documento"
                                class="form-select"
                                required>
                                <option value="cedula">Cédula Ecuatoriana</option>
                                <option value="pasaporte">Pasaporte</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label">Número de Documento *</label>
                            <input type="text"
                                name="documento"
                                id="documento"
                                class="form-control"
                                value="{{ old('documento') }}"
                                placeholder="Ej: 1750123456 o V12345678"
                                required>
                            <small class="form-text" id="doc_hint">
                                <i class="bi bi-info-circle"></i>
                                Si tu cédula empieza con 0, ingrésala completa.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nacionalidad *</label>
                            <select name="nacionalidad"
                                id="nacionalidad"
                                class="form-select"
                                required>
                                <option value="Ecuatoriana">Ecuatoriana</option>
                                <option value="Venezolana">Venezolana</option>
                                <option value="Colombiana">Colombiana</option>
                                <option value="Peruana">Peruana</option>
                                <option value="Cubana">Cubana</option>
                                <option value="Boliviana">Boliviana</option>
                                <option value="Chilena">Chilena</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Brasileña">Brasileña</option>
                                <option value="Mexicana">Mexicana</option>
                                <option value="Estadounidense">Estadounidense</option>
                                <option value="Canadiense">Canadiense</option>
                                <option value="Española">Española</option>
                                <option value="Italiana">Italiana</option>
                                <option value="Francesa">Francesa</option>
                                <option value="Alemana">Alemana</option>
                                <option value="China">China</option>
                                <option value="Japonesa">Japonesa</option>
                                <option value="Coreana">Coreana</option>
                                <option value="Otra">Otra</option>
                            </select>
                            <small class="form-text">Puedes cambiarla si la detección es incorrecta</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Carrera *</label>
                            <select name="carrera" class="form-select" required>
                                <option value="Desarrollo de Software" selected>Desarrollo de Software</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Semestre *</label>
                    <select name="ciclo_nivel" class="form-select" required>
                        <option value="">-- Seleccionar --</option>
                        <option value="PRIMER NIVEL">Primer Semestre</option>
                        <option value="SEGUNDO NIVEL">Segundo Semestre</option>
                        <option value="TERCER NIVEL">Tercer Semestre</option>
                        <option value="CUARTO NIVEL">Cuarto Semestre</option>
                    </select>
                </div>

                {{-- Contacto --}}
                <div class="section-title mt-4">Información de Contacto</div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Correo Institucional *</label>
                            <input type="email"
                                name="correo_institucional"
                                class="form-control"
                                value="{{ old('correo_institucional') }}"
                                placeholder="ejemplo@istpet.edu.ec"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Celular *</label>
                            <input type="text"
                                name="celular"
                                class="form-control"
                                value="{{ old('celular') }}"
                                placeholder="0991234567"
                                pattern="[0-9]{10}"
                                required>
                            <small class="form-text">10 dígitos</small>
                        </div>
                    </div>
                </div>

                {{-- Contraseña --}}
                <div class="section-title mt-4">Contraseña de Acceso</div>

                <div class="alert alert-warning">
                    <strong><i class="bi bi-shield-lock me-2"></i>Requisitos de contraseña:</strong>
                    <ul class="mb-0 mt-2" style="font-size: 13px;">
                        <li>Mínimo 8 caracteres</li>
                        <li>Al menos 1 letra mayúscula</li>
                        <li>Al menos 1 letra minúscula</li>
                        <li>Al menos 1 número</li>
                        <li>Al menos 1 carácter especial (@$!%*?&#)</li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Contraseña *</label>
                            <input type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                required
                                minlength="8"
                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$">
                            <small class="form-text">Mínimo 8 caracteres con mayúsculas, minúsculas, números y símbolos</small>
                            <div class="mt-2">
                                <small class="form-text">Fortaleza:</small>
                                <div class="progress" style="height: 5px;">
                                    <div id="password_strength" class="progress-bar" style="width: 0%"></div>
                                </div>
                                <small id="password_feedback" class="form-text"></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Confirmar Contraseña *</label>
                            <input type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                required
                                minlength="8">
                            <div id="password_match" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('login') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver al Login
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Registrarme
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ============================================
        // 1. MANEJO DE CÁMARA Y FOTO
        // ============================================
        let stream = null;

        document.getElementById('opcion_subir').addEventListener('change', function() {
            document.getElementById('div_subir_foto').style.display = 'block';
            document.getElementById('div_camara_foto').style.display = 'none';
            detenerCamara();
        });

        document.getElementById('opcion_camara').addEventListener('change', function() {
            document.getElementById('div_subir_foto').style.display = 'none';
            document.getElementById('div_camara_foto').style.display = 'block';
            iniciarCamara();
        });

        function iniciarCamara() {
            const video = document.getElementById('video');
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(function(s) {
                    stream = s;
                    video.srcObject = stream;
                })
                .catch(function(err) {
                    alert('Error al acceder a la cámara: ' + err.message);
                });
        }

        function detenerCamara() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        }

        document.getElementById('btn_tomar_foto').addEventListener('click', function() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');

            context.drawImage(video, 0, 0, 320, 240);
            const dataURL = canvas.toDataURL('image/jpeg');

            document.getElementById('foto_base64').value = dataURL;
            document.getElementById('foto_preview').src = dataURL;
            document.getElementById('foto_preview').style.display = 'block';

            video.style.display = 'none';
            this.style.display = 'none';
            document.getElementById('btn_retomar_foto').style.display = 'inline-block';

            detenerCamara();
        });

        document.getElementById('btn_retomar_foto').addEventListener('click', function() {
            const video = document.getElementById('video');
            video.style.display = 'block';
            document.getElementById('btn_tomar_foto').style.display = 'inline-block';
            this.style.display = 'none';
            document.getElementById('foto_preview').style.display = 'none';
            document.getElementById('foto_base64').value = '';

            iniciarCamara();
        });

        // ============================================
        // 2. DETECCIÓN AUTOMÁTICA DE PASAPORTES
        // ============================================
        const documentoInput = document.getElementById('documento');
        const tipoDocumento = document.getElementById('tipo_documento');
        const nacionalidad = document.getElementById('nacionalidad');
        const docHint = document.getElementById('doc_hint');

        documentoInput.addEventListener('input', function() {
            const valor = this.value.trim().toUpperCase();

            // Detectar pasaportes por patrón (letra(s) seguida de números)
            if (/^[A-Z]{1,2}\d{6,9}$/.test(valor)) {
                tipoDocumento.value = 'pasaporte';

                // Detectar nacionalidad por prefijo
                const prefijo = valor.charAt(0);
                switch (prefijo) {
                    case 'V':
                    case 'E':
                        nacionalidad.value = 'Venezolana';
                        docHint.innerHTML = '<i class="bi bi-check-circle text-success"></i> Pasaporte venezolano detectado';
                        break;
                    case 'P':
                        nacionalidad.value = 'Peruana';
                        docHint.innerHTML = '<i class="bi bi-check-circle text-success"></i> Pasaporte peruano detectado';
                        break;
                    case 'C':
                        nacionalidad.value = 'Colombiana';
                        docHint.innerHTML = '<i class="bi bi-check-circle text-success"></i> Pasaporte colombiano detectado';
                        break;
                    default:
                        docHint.innerHTML = '<i class="bi bi-check-circle text-success"></i> Pasaporte detectado - Selecciona tu nacionalidad';
                        break;
                }
            }
        });

        tipoDocumento.addEventListener('change', function() {
            if (this.value === 'pasaporte') {
                docHint.innerHTML = '<i class="bi bi-info-circle"></i> Ingresa tu pasaporte (Ej: V12345678, E123456, P123456)';
                documentoInput.placeholder = 'Ej: V12345678, E123456, P123456';
                nacionalidad.focus();
            } else {
                docHint.innerHTML = '<i class="bi bi-info-circle"></i> Si tu cédula empieza con 0, ingrésala completa.';
                documentoInput.placeholder = 'Ej: 1750123456';
                if (nacionalidad.value !== 'Ecuatoriana') {
                    nacionalidad.value = 'Ecuatoriana';
                }
            }
        });

        // ============================================
        // 3. VALIDACIÓN DE CONTRASEÑA FUERTE
        // ============================================
        const passwordInput = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const strengthBar = document.getElementById('password_strength');
        const feedbackText = document.getElementById('password_feedback');
        const matchDiv = document.getElementById('password_match');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength += 20;
            if (/[a-z]/.test(password)) strength += 20;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/\d/.test(password)) strength += 20;
            if (/[@$!%*?&#]/.test(password)) strength += 20;

            strengthBar.style.width = strength + '%';

            if (strength < 40) {
                strengthBar.className = 'progress-bar bg-danger';
                feedbackText.textContent = 'Muy débil';
                feedbackText.style.color = '#f87171';
            } else if (strength < 80) {
                strengthBar.className = 'progress-bar bg-warning';
                feedbackText.textContent = 'Débil';
                feedbackText.style.color = '#fbbf24';
            } else if (strength < 100) {
                strengthBar.className = 'progress-bar bg-info';
                feedbackText.textContent = 'Buena';
                feedbackText.style.color = '#3b82f6';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                feedbackText.textContent = 'Fuerte';
                feedbackText.style.color = '#10b981';
            }

            verificarCoincidencia();
        });

        passwordConfirmation.addEventListener('input', verificarCoincidencia);

        function verificarCoincidencia() {
            const password = passwordInput.value;
            const confirmation = passwordConfirmation.value;

            if (confirmation.length === 0) {
                matchDiv.innerHTML = '';
                return;
            }

            if (password === confirmation) {
                matchDiv.innerHTML = '<small class="text-success"><i class="bi bi-check-circle me-1"></i>Las contraseñas coinciden</small>';
            } else {
                matchDiv.innerHTML = '<small style="color: #f87171;"><i class="bi bi-x-circle me-1"></i>Las contraseñas no coinciden</small>';
            }
        }

        // Detener cámara al enviar formulario
        document.getElementById('registroForm').addEventListener('submit', function() {
            detenerCamara();
        });
    </script>
</body>

</html>