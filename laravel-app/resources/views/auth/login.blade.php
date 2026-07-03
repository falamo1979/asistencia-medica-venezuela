<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión · Centro de Asistencia Médica</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <div class="auth-split">
        <aside class="auth-brand">
            <a href="{{ route('landing') }}" class="auth-brand-top" style="text-decoration:none; color:inherit;">
                <span class="auth-brand-logo">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M9 3h6v6h6v6h-6v6H9v-6H3V9h6V3z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="auth-brand-name">
                    Centro de Asistencia Médica
                    <small>Registro clínico</small>
                </span>
            </a>

            <div class="auth-brand-body">
                <h2>Cada atención,<br>registrada y a mano.</h2>
                <p>Accedé al sistema para registrar consultas, gestionar pacientes y consultar el historial clínico del centro.</p>
                <ul class="auth-brand-list">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Registro de consultas guiado por pasos
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Historial clínico por paciente y por fecha
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Acceso seguro con roles diferenciados
                    </li>
                </ul>
            </div>

            <div class="auth-brand-foot">
                Uso interno · {{ date('Y') }}
            </div>
        </aside>

        <main class="auth-form-side">
            <div class="auth-form-box">
                <h1 class="auth-title">Iniciar sesión</h1>
                <p class="auth-subtitle">Ingresá con tu cuenta del centro médico.</p>

                @if ($errors->any())
                    <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               required autofocus autocomplete="username"
                               class="@error('email') is-invalid @enderror">
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password"
                               required autocomplete="current-password">
                    </div>

                    <label class="checkbox-row">
                        <input type="checkbox" name="remember" value="1">
                        <span>Mantener sesión iniciada</span>
                    </label>

                    <button type="submit" class="btn-primary btn-lg" style="width:100%; margin-top:8px;">
                        Entrar
                    </button>
                </form>

                <div class="auth-demo">
                    <strong>Cuentas de prueba</strong>
                    <ul>
                        <li>admin@centro.med</li>
                        <li>medico@centro.med</li>
                        <li>recepcion@centro.med</li>
                    </ul>
                    <span>Contraseña para todas: <code>password</code></span>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
