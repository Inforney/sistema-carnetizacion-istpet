<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Carnetización - ISTPET</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --istpet-azul: #222C57;
            --istpet-dorado: #C4A857;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, var(--istpet-azul) 0%, #2E3B6F 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        h1,
        h2,
        h3 {
            font-family: 'Oswald', sans-serif;
        }

        .welcome-container {
            text-align: center;
            padding: 40px 20px;
            max-width: 800px;
        }

        .logo {
            font-size: 120px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .title {
            font-size: 3rem;
            font-weight: 700;
            color: var(--istpet-dorado);
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 1.5rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .slogan {
            font-style: italic;
            font-size: 1.1rem;
            margin-bottom: 40px;
            opacity: 0.8;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 30px 20px;
            transition: transform 0.3s, background 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--istpet-dorado);
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .btn-login {
            background-color: var(--istpet-dorado);
            color: var(--istpet-azul);
            border: none;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-login:hover {
            background-color: #D4B867;
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(196, 168, 87, 0.4);
        }

        .status-badge {
            display: inline-block;
            background: rgba(40, 167, 69, 0.2);
            border: 2px solid #28A745;
            color: #28A745;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .tech-stack {
            margin-top: 50px;
            opacity: 0.7;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .title {
                font-size: 2rem;
            }

            .subtitle {
                font-size: 1.2rem;
            }

            .logo {
                font-size: 80px;
            }
        }
    </style>
</head>

<body>
    <div class="welcome-container">
        <div class="logo">🎓</div>

        <div class="status-badge">
            <i class="bi bi-check-circle-fill me-2"></i>Sistema Operativo
        </div>

        <h1 class="title">ISTPET</h1>
        <h2 class="subtitle">Sistema de Carnetización y Control de Acceso</h2>
        <p class="slogan">Excelencia Académica - Atrévete a cambiar el mundo</p>

        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="feature-title">Carnets Digitales</div>
                <p>Gestión completa de carnets estudiantiles con código QR</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-door-open"></i>
                </div>
                <div class="feature-title">Control de Acceso</div>
                <p>Registro automático de entrada y salida a laboratorios</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="feature-title">Reportes en Tiempo Real</div>
                <p>Estadísticas y análisis de uso de laboratorios</p>
            </div>
        </div>

        <div class="mt-5">
            <a href="{{ route('login') }}" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
            </a>
        </div>

        <div class="tech-stack">
            <p>
                <strong>Stack Tecnológico:</strong><br>
                Laravel 10 • PHP 8.2 • MySQL 8.0 • Bootstrap 5 • Chart.js
            </p>
            <p class="mt-3">
                <small>© 2026 ISTPET - Desarrollo de Software III</small>
            </p>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>