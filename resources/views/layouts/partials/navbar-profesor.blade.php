<nav class="navbar navbar-expand-lg navbar-dark" style="background: #1a2342;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('profesor.dashboard') }}">
            <i class="bi bi-mortarboard-fill me-2" style="color: #C4A857;"></i>
            ISTPET Profesor
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profesor.dashboard') ? 'active' : '' }}"
                        href="{{ route('profesor.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profesor.accesos.index') ? 'active' : '' }}"
                        href="{{ route('profesor.accesos.index') }}">
                        <i class="bi bi-door-open me-1"></i>Control de Accesos
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('profesor.accesos.estudiantes-lab') ? 'active' : '' }}"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-building me-1"></i>Laboratorios
                    </a>
                    <ul class="dropdown-menu">
                        @php
                        $laboratorios = \App\Models\Laboratorio::where('estado', 'activo')->get();
                        @endphp
                        @foreach($laboratorios as $lab)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('profesor.accesos.estudiantes-lab', $lab->id) }}">
                                <i class="bi bi-people me-2"></i>{{ $lab->nombre }}
                                @php
                                $ocupacion = $lab->accesos()
                                ->whereNull('hora_salida')
                                ->where('marcado_ausente', false)
                                ->whereDate('fecha_entrada', today())
                                ->count();
                                @endphp
                                @if($ocupacion > 0)
                                <span class="badge bg-success ms-2">{{ $ocupacion }}</span>
                                @endif
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profesor.accesos.historial') ? 'active' : '' }}"
                        href="{{ route('profesor.accesos.historial') }}">
                        <i class="bi bi-clock-history me-1"></i>Historial
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::guard('profesor')->user()->nombres ?? 'Profesor' }}
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
</style>