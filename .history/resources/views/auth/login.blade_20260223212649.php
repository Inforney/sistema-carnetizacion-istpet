<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ISTPET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --azul: #222C57;
            --azul-dark: #1a2342;
            --azul-darker: #111829;
            --dorado: #C4A857;
            --dorado-hover: #d4b866;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(160deg, var(--azul) 0%, var(--azul-dark) 55%, var(--azul-darker) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Efecto de fondo sutil */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 15% 40%, rgba(196, 168, 87, 0.07) 0%, transparent 55%),
                radial-gradient(ellipse at 85% 15%, rgba(196, 168, 87, 0.04) 0%, transparent 45%),
                radial-gradient(ellipse at 50% 90%, rgba(34, 44, 87, 0.5) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* ---- NAVBAR ---- */
        .top-nav {
            position: relative;
            z-index: 10;
            padding: 14px 0;
            border-bottom: 1px solid rgba(196, 168, 87, 0.15);
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .brand-logo {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0;
        }

        .brand-logo .ist {
            color: var(--dorado);
        }

        .brand-logo .sep {
            color: var(--dorado);
            margin: 0 4px;
            opacity: 0.5;
            font-weight: 400;
        }

        .brand-logo .name {
            color: #fff;
            font-size: 0.82rem;
            letter-spacing: 0.6px;
            margin-left: 7px;
        }

        /* ---- SEPARADOR DE MARCA ---- */
        .brand-divider {
            height: 3px;
            background: linear-gradient(90deg, var(--dorado) 0%, var(--azul) 100%);
            border: none;
            margin: 0;
        }

        /* ---- CONTENIDO ---- */
        .main-wrap {
            flex: 1;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        /* ---- BRAND HEADER ---- */
        .brand-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .brand-icon-box {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--dorado) 0%, #b8983f 100%);
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 12px 40px rgba(196, 168, 87, 0.45), 0 4px 16px rgba(0, 0, 0, 0.3);
        }

        .brand-icon-box i {
            font-size: 52px;
            color: var(--azul);
        }

        .brand-header h1 {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 2.2rem;
            color: #fff;
            margin-bottom: 6px;
            letter-spacing: 2px;
        }

        .brand-header h1 span {
            color: var(--dorado);
        }

        .brand-header p {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.55);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* ---- CARD LOGIN ---- */
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(196, 168, 87, 0.2);
            border-top: 3px solid var(--dorado);
            border-radius: 14px;
            padding: 36px 38px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }

        .section-title {
            font-family: 'Oswald', sans-serif;
            font-size: 1.1rem;
            color: #fff;
            font-weight: 600;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(196, 168, 87, 0.25);
        }

        /* ---- ALERTS ---- */
        .alert {
            padding: 11px 15px;
            border-radius: 8px;
            font-size: 13.5px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        /* ---- FORM ---- */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            font-size: 14.5px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
            transition: all 0.2s;
            font-family: 'Open Sans', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--dorado);
            box-shadow: 0 0 0 3px rgba(196, 168, 87, 0.12);
            background: rgba(255, 255, 255, 0.1);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .form-text {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 4px;
            display: block;
        }

        .text-danger-custom {
            color: #f87171;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        /* ---- FORGOT ---- */
        .forgot-link {
            text-align: right;
            margin-bottom: 22px;
        }

        .forgot-link a {
            font-size: 13px;
            color: var(--dorado);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link a:hover {
            color: var(--dorado-hover);
            text-decoration: underline;
        }

        /* ---- BOTONES ---- */
        .btn-login {
            width: 100%;
            padding: 13px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Oswald', sans-serif;
            letter-spacing: 1px;
            color: var(--azul);
            background: var(--dorado);
            border: none;
            border-radius: 9px;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
        }

        .btn-login:hover {
            background: var(--dorado-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(196, 168, 87, 0.35);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        /* ---- DIVIDER ---- */
        .divider {
            display: flex;
            align-items: center;
            margin: 22px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
        }

        .divider span {
            padding: 0 12px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Oswald', sans-serif;
            letter-spacing: 0.5px;
            color: var(--dorado);
            background: transparent;
            border: 1.5px solid rgba(196, 168, 87, 0.4);
            border-radius: 9px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.2s;
        }

        .btn-register:hover {
            border-color: var(--dorado);
            background: rgba(196, 168, 87, 0.08);
            color: var(--dorado-hover);
        }

        /* ---- FOOTER NOTE ---- */
        .footer-note {
            text-align: center;
            margin-top: 20px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 9px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-note p {
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.5);
            margin: 0;
        }

        /* ---- COPYRIGHT ---- */
        .copyright {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.3);
        }

        .copyright span {
            color: var(--dorado);
            opacity: 0.7;
        }

        /* ---- FOOTER ---- */
        footer {
            position: relative;
            z-index: 1;
            background: rgba(0, 0, 0, 0.25);
            border-top: 1px solid rgba(196, 168, 87, 0.15);
            padding: 12px 0;
            text-align: center;
        }

        footer p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.35);
            margin: 0;
        }

        footer em {
            color: var(--dorado);
            opacity: 0.7;
            font-style: italic;
        }
    </style>
</head>

<body>

    {{-- Navbar de marca --}}
    <nav class="top-nav">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="brand-logo" href="{{ route('login') }}">
                <span class="ist">IST</span><span class="sep">|</span><span class="ist">PET</span>
                <span class="name">TECNOLÓGICO TRAVERSARI</span>
            </a>
            <span style="font-size:12px; color:rgba(255,255,255,0.4); letter-spacing:0.5px;">
                Sistema de Carnetización hecho por Kevin Huilca
            </span>
        </div>
    </nav>
    <hr class="brand-divider">

    {{-- Contenido principal --}}
    <div class="main-wrap">
        <div class="login-wrapper">

            {{-- Encabezado de marca --}}
            <div class="brand-header">
                <div class="brand-icon-box">
                    <img src="{{ asset('images/LogoISTPET.png') }}" alt="ISTPET" style="width:92px;height:92px;object-fit:contain;filter:drop-shadow(0 2px 6px rgba(0,0,0,0.25));">
                </div>
                <h1>IST<span>|</span>PET</h1>
                <p>Sistema de Carnetización</p>
            </div>

            {{-- Card de login --}}
            <div class="login-card">
                <div class="section-title">
                    <i class="bi bi-box-arrow-in-right" style="color:var(--dorado);"></i>
                    Iniciar Sesión
                </div>

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
                        <label class="form-label">
                            <i class="bi bi-person me-1"></i>Usuario
                        </label>
                        <input type="text"
                            name="usuario"
                            class="form-control"
                            placeholder="Cédula, pasaporte o usuario"
                            value="{{ old('usuario') }}"
                            required>
                        <span class="form-text">Ingresa tu documento de identidad</span>
                        @error('usuario')
                        <span class="text-danger-custom"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-lock me-1"></i>Contraseña
                        </label>
                        <input type="password"
                            name="password"
                            class="form-control"
                            placeholder="••••••••"
                            required>
                        @error('password')
                        <span class="text-danger-custom"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="forgot-link">
                        <a href="{{ route('recuperar.password') }}">
                            <i class="bi bi-question-circle me-1"></i>¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar al Sistema
                    </button>

                    <div class="divider">
                        <span>o</span>
                    </div>

                    <a href="{{ route('registro') }}" class="btn-register">
                        <i class="bi bi-person-plus me-2"></i>Crear Cuenta de Estudiante
                    </a>
                </form>
            </div>

            <div class="footer-note">
                <p>
                    <i class="bi bi-shield-lock me-1" style="color:var(--dorado);"></i>
                    Profesores y administradores: Contacte con el departamento de sistemas
                </p>
            </div>

            <div class="copyright">
                © 2026 <span>ISTPET</span> · Instituto Superior Tecnológico Mayor Pedro Traversari
                
            </div>

        </div>
    </div>

    {{-- Footer --}}
    <footer>
        <div class="container">
            <p><em>Excelencia Académica · Atrévete a cambiar el mundo</em></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>