<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ISTPET</title>
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

        .login-wrapper {
            width: 100%;
            max-width: 420px;
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

        .login-card {
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

        .form-text {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 4px;
            display: block;
        }

        .text-danger {
            color: #f87171;
            font-size: 13px;
            margin-top: 4px;
        }

        .forgot-link {
            text-align: right;
            margin-bottom: 24px;
        }

        .forgot-link a {
            font-size: 14px;
            color: #C4A857;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link a:hover {
            color: #d4b866;
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
        }

        .btn-primary:hover {
            background: #d4b866;
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .divider span {
            padding: 0 12px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
        }

        .btn-secondary {
            width: 100%;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            color: #C4A857;
            background: transparent;
            border: 1.5px solid #C4A857;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: rgba(196, 168, 87, 0.1);
            color: #d4b866;
        }

        .footer-note {
            text-align: center;
            margin-top: 24px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-note p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            margin: 0;
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
    <div class="login-wrapper">
        {{-- Brand --}}
        <div class="brand">
            <div class="brand-logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <h1>ISTPET</h1>
            <p>Sistema de Carnetización</p>
        </div>

        {{-- Login Card --}}
        <div class="login-card">
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

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Usuario</label>
                    <input type="text"
                        name="usuario"
                        class="form-control @error('usuario') is-invalid @enderror"
                        placeholder="Cédula, pasaporte o usuario"
                        value="{{ old('usuario') }}"
                        required>
                    <span class="form-text">Ingresa tu documento de identidad</span>
                    @error('usuario')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••"
                        required>
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="forgot-link">
                    <a href="{{ route('recuperar.password') }}">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-primary">
                    Iniciar sesión
                </button>

                <div class="divider">
                    <span>o</span>
                </div>

                <a href="{{ route('registro') }}" class="btn-secondary">
                    Crear cuenta de estudiante
                </a>
            </form>
        </div>

        <div class="footer-note">
            <p><i class="bi bi-info-circle me-1"></i> Profesores y administradores: Contacte con sistemas</p>
        </div>

        <div class="copyright">
            © 2026 ISTPET · Instituto Superior Tecnológico Mayor Pedro Traversari
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>