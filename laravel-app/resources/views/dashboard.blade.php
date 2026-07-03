@extends('layouts.app')

@section('title', 'Panel')

@section('content')
    <div class="page-header">
        <h1>Hola, {{ auth()->user()->name }}</h1>
        <p>Resumen de la actividad del centro.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-value">{{ $stats['consultas_hoy'] }}</span>
            <span class="stat-label">Consultas hoy</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">{{ $stats['consultas_total'] }}</span>
            <span class="stat-label">Consultas totales</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">{{ $stats['pacientes_total'] }}</span>
            <span class="stat-label">Pacientes</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">{{ $stats['medicos_total'] }}</span>
            <span class="stat-label">Médicos</span>
        </div>
    </div>

    <div class="quick-actions">
        <a href="{{ route('consultas.create') }}" class="btn-primary">+ Nueva consulta</a>
        <a href="{{ route('historial.index') }}" class="btn-secondary">Ver historial</a>
    </div>

    <div class="tabla-container">
        <h3>Últimas consultas</h3>
        @forelse ($ultimas as $c)
            @if ($loop->first)
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Motivo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
            @endif
                        <tr>
                            <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('pacientes.show', $c->paciente) }}" class="link">
                                    {{ $c->paciente->nombre_completo }}
                                </a>
                            </td>
                            <td>{{ $c->medico->nombre }} {{ $c->medico->apellido }}</td>
                            <td>{{ $c->motivo_consulta }}</td>
                            <td class="cell-actions">
                                <a href="{{ route('consultas.show', $c) }}" class="link">Ver</a>
                            </td>
                        </tr>
            @if ($loop->last)
                    </tbody>
                </table>
            @endif
        @empty
            <p class="empty-state">Todavía no hay consultas registradas.</p>
        @endforelse
    </div>
@endsection
