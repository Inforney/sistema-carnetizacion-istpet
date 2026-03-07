<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'ISTPET - Sistema de Carnetización')</title>

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

        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(160deg, #222C57 0%, #1a2342 60%, #111829 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(196,168,87,0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(196,168,87,0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .main-content {
            position: relative;
            z-index: 1;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Oswald', sans-serif;
        }

        .navbar {
            background-color: rgba(0,0,0,0.25) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(196,168,87,0.2);
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        .navbar-brand {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0;
            text-decoration: none;
        }

        .brand-ist { color: var(--istpet-dorado) !important; }
        .brand-sep { color: var(--istpet-dorado) !important; margin: 0 4px; opacity: 0.7; }
        .brand-name { color: #ffffff !important; font-size: 1rem; letter-spacing: 0.5px; }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            border-top: 3px solid var(--istpet-dorado);
        }

        .card-header-brand {
            background: linear-gradient(135deg, var(--istpet-azul) 0%, #1a2342 100%);
            color: white;
            border-radius: 13px 13px 0 0 !important;
            padding: 1.5rem;
            text-align: center;
            border-bottom: 3px solid var(--istpet-dorado);
        }

        .card-header-brand .brand-title {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: white;
            margin: 0.5rem 0 0.25rem;
        }

        .card-header-brand .brand-subtitle {
            color: var(--istpet-dorado);
            font-size: 0.8rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--istpet-azul);
            border-color: var(--istpet-azul);
        }

        .btn-primary:hover {
            background-color: #1a2342;
            border-color: #1a2342;
        }

        footer {
            background-color: rgba(0,0,0,0.3);
            border-top: 1px solid rgba(196,168,87,0.2);
            color: rgba(255,255,255,0.7);
            padding: 15px 0;
            margin-top: auto;
            position: relative;
            z-index: 1;
        }

        .slogan {
            font-style: italic;
            color: var(--istpet-dorado);
            opacity: 0.85;
            font-size: 0.85rem;
        }
    </style>

    @yield('styles')
</head>

<body>
    {{-- Navbar Simple --}}
    <nav class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('login') }}">
                <span class="brand-ist">IST</span><span class="brand-sep">|</span><span class="brand-ist">PET</span>
                <span class="brand-name ms-2">TECNOLÓGICO TRAVERSARI</span>
            </a>
            <span class="navbar-text text-white opacity-75" style="font-size:0.85rem;">
                Sistema de Carnetización
            </span>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="main-content">
        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="text-center text-white">
        <div class="container">
            <p class="mb-1">© 2026 ISTPET - Instituto Superior Tecnológico Mayor Pedro Traversari</p>
            <p class="slogan mb-0">Excelencia Académica - Atrévete a cambiar el mundo</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>

</html>