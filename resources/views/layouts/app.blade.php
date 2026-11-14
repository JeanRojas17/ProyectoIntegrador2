<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Transportes RBL')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .navbar-brand { font-weight: 700; letter-spacing: .5px; }
        .card-stat { border: 0; border-left: 4px solid #0d6efd; }
        .table-actions { white-space: nowrap; }

        .app-sidebar {
            --bs-offcanvas-width: 260px;
            width: 260px;
            background-color: #212529;
        }
        .app-sidebar .offcanvas-header { padding: 1rem 1.25rem; }
        .app-sidebar .nav-link {
            color: rgba(255, 255, 255, .7);
            border-radius: .5rem;
            padding: .55rem .85rem;
            display: flex;
            align-items: center;
            gap: .65rem;
        }
        .app-sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, .08);
        }
        .app-sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .app-sidebar .sidebar-heading {
            color: rgba(255, 255, 255, .4);
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 0 .85rem;
        }

        @media (min-width: 992px) {
            .app-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 1030;
                width: 260px !important;
                max-width: 260px !important;
                display: flex;
                flex-direction: column;
                overflow-y: auto;
                visibility: visible;
                transform: none;
                background-color: #212529 !important;
            }
            .app-sidebar .offcanvas-header { display: flex; }
            .app-sidebar .offcanvas-body {
                flex-grow: 1;
                padding: 1rem 1rem .35rem;
                background-color: #212529 !important;
            }
            .app-main { margin-left: 260px; width: auto; }
        }
    </style>
</head>
<body>
@auth
    @php($usuario = auth()->user())
    <aside class="offcanvas-lg offcanvas-start app-sidebar text-white" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header border-bottom border-secondary">
            <a class="navbar-brand text-white mb-0" href="{{ route('dashboard') }}">
                <i class="bi bi-truck"></i> Transportes RBL
            </a>
            <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-3">
            <p class="sidebar-heading mb-2">Menú</p>
            <ul class="nav nav-pills flex-column gap-1 mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Panel
                    </a>
                </li>

                @if ($usuario->esAdministrador() || $usuario->esConsulta())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                            <i class="bi bi-people"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('conductores.*') ? 'active' : '' }}" href="{{ route('conductores.index') }}">
                            <i class="bi bi-person-badge"></i> Conductores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vehiculos.*') ? 'active' : '' }}" href="{{ route('vehiculos.index') }}">
                            <i class="bi bi-truck"></i> Vehículos
                        </a>
                    </li>
                @endif

                @if ($usuario->esAdministrador() || $usuario->esConsulta() || $usuario->esConductor() || $usuario->esCliente())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('envios.*') ? 'active' : '' }}" href="{{ route('envios.index') }}">
                            <i class="bi bi-box-seam"></i> Envíos
                        </a>
                    </li>
                @endif

                @if ($usuario->esAdministrador() || $usuario->esConsulta() || $usuario->esConductor())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('rutas.*') ? 'active' : '' }}" href="{{ route('rutas.index') }}">
                            <i class="bi bi-signpost-split"></i> Rutas
                        </a>
                    </li>
                @endif

                @if ($usuario->esAdministrador() || $usuario->esConsulta())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                            <i class="bi bi-bar-chart"></i> Reportes
                        </a>
                    </li>
                @endif

                @if ($usuario->esAdministrador() || $usuario->esConsulta() || $usuario->esConductor())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('mimaparbl.*') ? 'active' : '' }}" href="{{ route('mimaparbl.index') }}">
                            <i class="bi bi-map"></i> MiMapaRBL
                        </a>
                    </li>
                @endif
            </ul>

            <div class="mt-auto pt-3 border-top border-secondary">
                <div class="text-white-50 small mb-2">
                    <i class="bi bi-person-circle"></i> {{ $usuario->name }}
                    <span class="d-block ps-4 text-capitalize">{{ $usuario->rol }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-light w-100" type="submit">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <nav class="navbar navbar-dark bg-dark shadow-sm d-lg-none">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-truck"></i> Transportes RBL
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
@endauth

<main class="{{ auth()->check() ? 'app-main container-fluid py-4 px-3 px-lg-4' : 'container py-4' }}">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Revisa los siguientes errores:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
