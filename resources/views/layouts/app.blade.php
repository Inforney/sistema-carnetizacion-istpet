<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - ISTPET')</title>

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
            background-color: #f5f5f5;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Oswald', sans-serif;
        }

        /* Navbar */
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

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            padding: 0.5rem 1rem;
            transition: all 0.3s;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: var(--istpet-dorado) !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .navbar-nav .nav-link.active {
            color: var(--istpet-dorado) !important;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 5px;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            padding: 30px 0;
            min-height: calc(100vh - 120px);
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: white;
            border-bottom: 2px solid var(--istpet-dorado);
            font-weight: 600;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--istpet-azul);
            border-color: var(--istpet-azul);
        }

        .btn-primary:hover {
            background-color: #1a2342;
            border-color: #1a2342;
        }

        .btn-outline-primary {
            color: var(--istpet-azul);
            border-color: var(--istpet-azul);
        }

        .btn-outline-primary:hover {
            background-color: var(--istpet-azul);
            border-color: var(--istpet-azul);
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            padding: 20px 0;
            margin-top: 40px;
        }

        footer a {
            color: var(--istpet-dorado);
            text-decoration: none;
        }

        .slogan {
            font-style: italic;
            color: #ccc;
        }
    </style>

    @yield('styles')
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            @auth('administrador')
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                🎓 ISTPET
            </a>
            @endauth

            @auth('profesor')
            <a class="navbar-brand" href="{{ route('profesor.dashboard') }}">
                🎓 ISTPET
            </a>
            @endauth

            @auth
            <a class="navbar-brand" href="{{ route('estudiante.dashboard') }}">
                🎓 ISTPET
            </a>
            @endauth

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                {{-- Menú según tipo de usuario --}}
                <ul class="navbar-nav me-auto">
                    @auth('administrador')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.estudiantes.*') ? 'active' : '' }}"
                            href="{{ route('admin.estudiantes.index') }}">
                            <i class="bi bi-people me-1"></i>Estudiantes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.carnets.*') ? 'active' : '' }}"
                            href="{{ route('admin.carnets.index') }}">
                            <i class="bi bi-credit-card me-1"></i>Carnets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.profesores.*') ? 'active' : '' }}"
                            href="{{ route('admin.profesores.index') }}">
                            <i class="bi bi-person-workspace me-1"></i>Profesores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.accesos.*') ? 'active' : '' }}"
                            href="{{ route('admin.accesos.index') }}">
                            <i class="bi bi-door-open me-1"></i>Accesos
                        </a>
                    </li>
                    @endauth

                    @auth('profesor')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profesor.dashboard') ? 'active' : '' }}"
                            href="{{ route('profesor.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profesor.accesos.*') ? 'active' : '' }}"
                            href="{{ route('profesor.accesos.index') }}">
                            <i class="bi bi-door-open me-1"></i>Control de Accesos
                        </a>
                    </li>
                    @endauth

                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('estudiante.dashboard') ? 'active' : '' }}"
                            href="{{ route('estudiante.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('estudiante.carnet.*') ? 'active' : '' }}"
                            href="{{ route('estudiante.carnet.show') }}">
                            <i class="bi bi-credit-card me-1"></i>Mi Carnet
                        </a>
                    </li>
                    @endauth
                </ul>

                {{-- Usuario --}}
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-2"></i>
                            @auth('administrador')
                            {{ Auth::guard('administrador')->user()->usuario }}
                            @endauth
                            @auth('profesor')
                            {{ Auth::guard('profesor')->user()->nombres }}
                            @endauth
                            @auth
                            {{ Auth::user()->nombres }}
                            @endauth
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="main-content">
        <div class="container-fluid">
            {{-- Mensajes de sesión --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Contenido de la página --}}
            @yield('content')
        </div>
    </div>

    {{-- Footer --}}
    <footer class="text-center">
        <div class="container">
            <p class="mb-1">© 2026 ISTPET - Sistema de Carnetización</p>
            <p class="slogan mb-0">Excelencia Académica - Atrévete a cambiar el mundo</p>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

    @yield('scripts')
</body>

</html>