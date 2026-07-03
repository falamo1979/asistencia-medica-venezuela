@extends('layouts.public')

@section('title', 'Centro de Asistencia Médica')

@section('content')
    <section class="hero">
        <div class="hero-inner">
            <span class="hero-badge">Sistema de registro clínico</span>
            <h1 class="hero-title">Atención médica registrada de forma simple y segura</h1>
            <p class="hero-subtitle">
                Registrá consultas, pacientes, tratamientos y medicamentos en un solo lugar.
                Pensado para atención en terreno y emergencias, con historial por fecha y
                acceso protegido por roles.
            </p>
            <div class="hero-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary btn-lg">Ir al panel →</a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary btn-lg">Iniciar sesión</a>
                @endauth
            </div>
            <p class="hero-note">El acceso al sistema es exclusivo para personal autorizado.</p>
        </div>
    </section>

    <section class="features">
        <div class="feature-card">
            <div class="feature-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="8" y="2" width="8" height="4" rx="1"/>
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <path d="M9 12h6M9 16h4"/>
                </svg>
            </div>
            <h3>Registro de consultas</h3>
            <p>Formulario guiado por pasos: médico, paciente, tratamiento y medicamentos.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
            <h3>Historial por fecha</h3>
            <p>Consultá las atenciones de cualquier día y reutilizá fichas por DNI o matrícula.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>
            <h3>Acceso por roles</h3>
            <p>Administración, médicos y recepción, cada uno con su propio acceso seguro.</p>
        </div>
    </section>
@endsection
