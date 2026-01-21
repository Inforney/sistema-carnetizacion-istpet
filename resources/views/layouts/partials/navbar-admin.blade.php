<nav class="navbar navbar-expand-lg navbar-dark" style="background: #1a2342;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-mortarboard-fill me-2" style="color: #C4A857;"></i>
            ISTPET Admin
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
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
                    <a class="nav-link {{ request()->routeIs('admin.accesos.*') ? 'active' : '' }}"
                        href="{{ route('admin.accesos.index') }}">
                        <i class="bi bi-door-open me-1"></i>Accesos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.laboratorios.*') ? 'active' : '' }}"
                        href="{{ route('admin.laboratorios.index') }}">
                        <i class="bi bi-building me-1"></i>Laboratorios
                    </a>
                </li>

                {{-- Solicitudes con Badge --}}
                <li class="nav-item">
                    @php
                    $solicitudesPendientes = \App\Models\SolicitudPassword::where('estado', 'pendiente')->count();
                    @endphp
                    <a class="nav-link {{ request()->routeIs('admin.solicitudes.*') ? 'active' : '' }} position-relative"
                        href="{{ route('admin.solicitudes.index') }}">
                        <i class="bi bi-envelope me-1"></i>Solicitudes
                        @if($solicitudesPendientes > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size: 0.7rem;">
                            {{ $solicitudesPendientes }}
                            <span class="visually-hidden">solicitudes pendientes</span>
                        </span>
                        @endif
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::guard('admin')->user()->nombres ?? 'Admin' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
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

<style>
    .navbar-dark .nav-link {
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s;
    }

    .navbar-dark .nav-link:hover {
        color: #C4A857;
    }

    .navbar-dark .nav-link.active {
        color: #C4A857;
        font-weight: 600;
    }

    .badge.bg-danger {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }
    }
</style>