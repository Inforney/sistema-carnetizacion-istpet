<nav class="navbar navbar-expand-lg navbar-dark" style="background: #1a2342;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('estudiante.dashboard') }}">
            <i class="bi bi-mortarboard-fill me-2" style="color: #C4A857;"></i>
            ISTPET Estudiante
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
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

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('estudiante.acceso.escanear') ? 'active' : '' }}"
                        href="{{ route('estudiante.acceso.escanear') }}">
                        <i class="bi bi-qr-code-scan me-1"></i>Registrar Acceso
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('estudiante.acceso.historial') ? 'active' : '' }}"
                        href="{{ route('estudiante.acceso.historial') }}">
                        <i class="bi bi-clock-history me-1"></i>Mi Historial
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav">
                {{-- Notificación de Acceso Activo --}}
                @php
                $accesoActivo = \App\Models\Acceso::where('usuario_id', Auth::user()->id)
                ->whereNull('hora_salida')
                ->where('marcado_ausente', false)
                ->whereDate('fecha_entrada', today())
                ->with('laboratorio')
                ->first();
                @endphp

                @if($accesoActivo)
                <li class="nav-item">
                    <a class="nav-link text-warning"
                        href="{{ route('estudiante.acceso.escanear') }}"
                        data-bs-toggle="tooltip"
                        title="Tienes un acceso activo en {{ $accesoActivo->laboratorio->nombre }}">
                        <i class="bi bi-clock-fill me-1"></i>
                        <span class="badge bg-warning text-dark">En Lab</span>
                    </a>
                </li>
                @endif

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::user()->nombres ?? 'Estudiante' }}
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

    .badge.bg-warning {
        animation: blink 1.5s infinite;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }
</style>

<script>
    // Activar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>