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
            box-shadow: 0 2px 10px rgba(0,0,0,0.25);
            border-bottom: 2px solid var(--istpet-dorado);
        }

        .navbar-brand {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .brand-ist  { color: var(--istpet-dorado) !important; }
        .brand-sep  { color: var(--istpet-dorado) !important; margin: 0 3px; opacity: 0.6; }
        .brand-name { color: #ffffff !important; font-size: 0.85rem; letter-spacing: 0.5px; margin-left: 6px; }

        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.85) !important;
            padding: 0.5rem 0.9rem;
            transition: all 0.25s;
            font-weight: 500;
            border-radius: 6px;
            margin: 0 2px;
        }

        .navbar-nav .nav-link:hover {
            color: var(--istpet-dorado) !important;
            background-color: rgba(255,255,255,0.08);
        }

        .navbar-nav .nav-link.active {
            color: var(--istpet-dorado) !important;
            background-color: rgba(255,255,255,0.12);
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            padding: 28px 0;
            min-height: calc(100vh - 115px);
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: white;
            border-bottom: 2px solid var(--istpet-dorado);
            font-weight: 600;
            border-radius: 10px 10px 0 0 !important;
        }

        .card-header.bg-istpet {
            background-color: var(--istpet-azul) !important;
            border-bottom: 2px solid var(--istpet-dorado);
            color: white;
        }

        /* Stat cards */
        .stat-card {
            border-top: 4px solid var(--istpet-dorado) !important;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(34,44,87,0.15) !important;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            background-color: rgba(34,44,87,0.08);
            color: var(--istpet-azul);
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

        .btn-gold {
            background-color: var(--istpet-dorado);
            border-color: var(--istpet-dorado);
            color: var(--istpet-azul);
            font-weight: 600;
        }

        .btn-gold:hover {
            background-color: #b8983f;
            border-color: #b8983f;
            color: white;
        }

        .btn-outline-primary {
            color: var(--istpet-azul);
            border-color: var(--istpet-azul);
        }

        .btn-outline-primary:hover {
            background-color: var(--istpet-azul);
            border-color: var(--istpet-azul);
            color: white;
        }

        .btn-outline-gold {
            color: var(--istpet-dorado);
            border-color: var(--istpet-dorado);
            background: transparent;
        }

        .btn-outline-gold:hover {
            background-color: var(--istpet-dorado);
            color: var(--istpet-azul);
            font-weight: 600;
        }

        /* Action buttons en dashboard */
        .action-btn {
            border: 2px solid var(--istpet-azul);
            color: var(--istpet-azul);
            background: white;
            border-radius: 10px;
            transition: all 0.2s;
            font-weight: 600;
        }

        .action-btn:hover {
            background-color: var(--istpet-azul);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(34,44,87,0.2);
        }

        .action-btn.gold {
            border-color: var(--istpet-dorado);
            color: var(--istpet-azul);
        }

        .action-btn.gold:hover {
            background-color: var(--istpet-dorado);
            border-color: var(--istpet-dorado);
            color: var(--istpet-azul);
        }

        /* Footer */
        footer {
            background-color: var(--istpet-azul);
            color: rgba(255,255,255,0.8);
            padding: 20px 0;
            margin-top: 40px;
            border-top: 3px solid var(--istpet-dorado);
        }

        footer a {
            color: var(--istpet-dorado);
            text-decoration: none;
        }

        .slogan {
            font-style: italic;
            color: var(--istpet-dorado);
            opacity: 0.85;
            font-size: 0.85rem;
        }

        /* Badges con marca */
        .badge-istpet {
            background-color: var(--istpet-azul);
            color: white;
        }

        .badge-gold {
            background-color: var(--istpet-dorado);
            color: var(--istpet-azul);
            font-weight: 700;
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
                <img src="{{ asset('images/LogoISTPET.png') }}" alt="ISTPET" style="height:36px;width:auto;object-fit:contain;filter:brightness(0) invert(1);">
                <span class="brand-name ms-2">TECNOLÓGICO TRAVERSARI</span>
            </a>
            @endauth

            @auth('profesor')
            <a class="navbar-brand" href="{{ route('profesor.dashboard') }}">
                <img src="{{ asset('images/LogoISTPET.png') }}" alt="ISTPET" style="height:36px;width:auto;object-fit:contain;filter:brightness(0) invert(1);">
                <span class="brand-name ms-2">TECNOLÓGICO TRAVERSARI</span>
            </a>
            @endauth

            @auth
            <a class="navbar-brand" href="{{ route('estudiante.dashboard') }}">
                <img src="{{ asset('images/LogoISTPET.png') }}" alt="ISTPET" style="height:36px;width:auto;object-fit:contain;filter:brightness(0) invert(1);">
                <span class="brand-name ms-2">TECNOLÓGICO TRAVERSARI</span>
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
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profesor.reservas.*') ? 'active' : '' }}"
                            href="{{ route('profesor.reservas.index') }}">
                            <i class="bi bi-calendar-check me-1"></i>Mis Reservas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profesor.accesos.historial') ? 'active' : '' }}"
                            href="{{ route('profesor.accesos.historial') }}">
                            <i class="bi bi-clock-history me-1"></i>Historial
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

            {{-- Notificación WhatsApp --}}
            @if(session('whatsapp'))
            @php $wa = session('whatsapp'); @endphp
            <div class="alert alert-dismissible fade show" role="alert"
                 style="background:#e8f5e9;border:1.5px solid #25D366;border-radius:10px;padding:16px 20px;">
                <div class="d-flex align-items-start gap-3">
                    <div style="font-size:2rem;line-height:1;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="#25D366">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <div class="flex-grow-1">
                        <strong style="color:#1a5c2a;font-size:0.95rem;">Notificación por WhatsApp</strong>
                        <p class="mb-2 mt-1" style="color:#2d5a27;font-size:0.88rem;">
                            Contraseña reseteada para <strong>{{ $wa['nombre'] }}</strong>
                            ({{ $wa['telefono'] }}).
                            Contraseña: <code style="background:#d4edda;padding:2px 6px;border-radius:4px;font-weight:700;">{{ $wa['password'] }}</code>
                        </p>
                        <a href="{{ $wa['link'] }}" target="_blank"
                           class="btn btn-sm fw-bold"
                           style="background:#25D366;color:white;border:none;border-radius:8px;padding:6px 16px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white" class="me-1" style="vertical-align:middle;">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Enviar por WhatsApp
                        </a>
                        <small class="d-block mt-2" style="color:#666;">Haz clic para abrir WhatsApp Web y enviar el mensaje automáticamente.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
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