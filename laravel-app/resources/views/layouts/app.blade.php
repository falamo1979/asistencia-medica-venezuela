<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Asistencia Médica')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('consultas.create') }}" class="brand">
                <span class="brand-logo" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M9 3h6v6h6v6h-6v6H9v-6H3V9h6V3z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="brand-text">
                    <strong>Centro de Asistencia Médica</strong>
                    <small>Registro clínico</small>
                </span>
            </a>

            <div class="nav-links">
                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Panel
                </a>
                <a href="{{ route('consultas.create') }}"
                   class="nav-item {{ request()->routeIs('consultas.create') ? 'active' : '' }}">
                    Nueva consulta
                </a>
                <a href="{{ route('pacientes.index') }}"
                   class="nav-item {{ request()->routeIs('pacientes.*') ? 'active' : '' }}">
                    Pacientes
                </a>
                <a href="{{ route('historial.index') }}"
                   class="nav-item {{ request()->routeIs('historial.*') ? 'active' : '' }}">
                    Historial
                </a>
                @if (auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('usuarios.index') }}"
                       class="nav-item {{ request()->routeIs('usuarios.*', 'medicos.*') ? 'active' : '' }}">
                        Administración
                    </a>
                @endif

                @auth
                    <div class="user-menu">
                        <span class="user-info">
                            <span class="user-name">{{ auth()->user()->name }}</span>
                            <span class="user-role">{{ auth()->user()->role_label }}</span>
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-logout" title="Cerrar sesión">Salir</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="page">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    Revisá los siguientes campos:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>

        <footer class="page-footer">
            Centro de Asistencia Médica · Uso interno · {{ date('Y') }}
        </footer>
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
