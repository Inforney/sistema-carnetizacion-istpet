<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - ISTPET</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .recover-wrapper {
            width: 100%;
            max-width: 480px;
        }

        .brand {
            text-align: center;
            margin-bottom: 35px;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: #C4A857;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .brand-logo i {
            font-size: 28px;
            color: #1a2342;
        }

        .brand h1 {
            font-size: 22px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 5px;
        }

        .brand p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .recover-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(196, 168, 87, 0.2);
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .alert-info {
            background: rgba(196, 168, 87, 0.15);
            color: #C4A857;
            border: 1px solid rgba(196, 168, 87, 0.3);
        }

        .info-box {
            background: rgba(196, 168, 87, 0.1);
            border: 1px solid rgba(196, 168, 87, 0.3);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .info-box h6 {
            color: #C4A857;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .info-box ol {
            margin: 0;
            padding-left: 20px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
        }

        .info-box li {
            margin-bottom: 4px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            font-size: 15px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            transition: all 0.2s;
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .form-control:focus {
            outline: none;
            border-color: #C4A857;
            box-shadow: 0 0 0 3px rgba(196, 168, 87, 0.15);
            background: rgba(255, 255, 255, 0.12);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .text-danger {
            color: #f87171;
            font-size: 13px;
            margin-top: 4px;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            color: #1a2342;
            background: #C4A857;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 12px;
        }

        .btn-primary:hover {
            background: #d4b866;
            transform: translateY(-1px);
        }

        .back-link {
            text-align: center;
        }

        .back-link a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: #C4A857;
        }

        .copyright {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.4);
        }
    </style>
</head>

<body>
    <div class="recover-wrapper">
        {{-- Brand --}}
        <div class="brand">
            <div class="brand-logo">
                <i class="bi bi-key-fill"></i>
            </div>
            <h1>Recuperar Contraseña</h1>
            <p>Sistema de Carnetización ISTPET</p>
        </div>

        {{-- Recover Card --}}
        <div class="recover-card">
            @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            </div>
            @endif

            <div class="info-box">
                <h6><i class="bi bi-info-circle me-2"></i>¿Cómo funciona?</h6>
                <ol>
                    <li>Ingresa tu documento y correo institucional</li>
                    <li>Tu solicitud llegará al administrador</li>
                    <li>Recibirás una contraseña temporal por correo</li>
                    <li>Inicia sesión con la contraseña temporal</li>
                </ol>
            </div>

            <form method="POST" action="{{ route('recuperar.enviar') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Documento de Identidad *</label>
                    <input type="text"
                        name="documento"
                        class="form-control @error('documento') is-invalid @enderror"
                        placeholder="Cédula o pasaporte"
                        value="{{ old('documento') }}"
                        required>
                    @error('documento')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Correo Institucional *</label>
                    <input type="email"
                        name="correo"
                        class="form-control @error('correo') is-invalid @enderror"
                        placeholder="ejemplo@istpet.edu.ec"
                        value="{{ old('correo') }}"
                        required>
                    @error('correo')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">
                    <i class="bi bi-send me-2"></i>Enviar Solicitud
                </button>

                <div class="back-link">
                    <a href="{{ route('login') }}">
                        <i class="bi bi-arrow-left me-1"></i>Volver al login
                    </a>
                </div>
            </form>
        </div>

        <div class="copyright">
            © 2026 ISTPET · Instituto Superior Tecnológico Mayor Pedro Traversari
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>