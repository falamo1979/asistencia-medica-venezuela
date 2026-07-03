<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Centro de Asistencia Médica')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="public-body">
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('landing') }}" class="brand">
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
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-item active">Ir al panel</a>
                @else
                    <a href="{{ route('login') }}" class="nav-item active">Iniciar sesión</a>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="page-footer">
        Centro de Asistencia Médica · Uso interno · {{ date('Y') }}
    </footer>
</body>
</html>
