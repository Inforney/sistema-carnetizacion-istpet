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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
            background-color: var(--istpet-azul) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            color: var(--istpet-dorado) !important;
            font-size: 1.5rem;
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
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
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
            padding: 20px 0;
            margin-top: auto;
        }

        .slogan {
            font-style: italic;
            opacity: 0.8;
        }
    </style>

    @yield('styles')
</head>

<body>
    {{-- Navbar Simple --}}
    <nav class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('login') }}">
                🎓 ISTPET
            </a>
            <span class="navbar-text text-white">
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