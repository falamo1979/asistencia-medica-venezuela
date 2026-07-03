@extends('layouts.app')

@section('title', 'Historial de Atenciones')

@section('content')
    <div class="page-header">
        <h1>Historial de Atenciones</h1>
        <p>Filtrá las atenciones por rango de fechas y médico.</p>
    </div>

    <form method="GET" action="{{ route('historial.index') }}" class="filtros">
        <div class="filtros-grid">
            <div class="form-group">
                <label for="desde">Desde</label>
                <input type="date" id="desde" name="desde" value="{{ $desde }}">
            </div>
            <div class="form-group">
                <label for="hasta">Hasta</label>
                <input type="date" id="hasta" name="hasta" value="{{ $hasta }}">
            </div>
            <div class="form-group">
                <label for="medico_id">Médico</label>
                <select id="medico_id" name="medico_id">
                    <option value="">Todos</option>
                    @foreach ($medicos as $medico)
                        <option value="{{ $medico->id }}" @selected((int) $medicoId === $medico->id)>
                            {{ $medico->nombre }} {{ $medico->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group filtros-actions">
                <button type="submit" class="btn-primary">Filtrar</button>
                <a href="{{ route('historial.index') }}" class="btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>

    <div class="tabla-container">
        <h3>{{ $registros->total() }} atención(es) encontradas</h3>

        @forelse ($registros as $registro)
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
                            <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('pacientes.show', $registro->paciente) }}" class="link">
                                    {{ $registro->paciente->nombre_completo }}
                                </a>
                            </td>
                            <td>{{ $registro->medico->nombre }} {{ $registro->medico->apellido }}</td>
                            <td>{{ $registro->motivo_consulta }}</td>
                            <td class="cell-actions">
                                <a href="{{ route('consultas.show', $registro) }}" class="link">Ver</a>
                            </td>
                        </tr>
            @if ($loop->last)
                    </tbody>
                </table>
            @endif
        @empty
            <p class="empty-state">No se encontraron atenciones con esos filtros.</p>
        @endforelse
    </div>

    {{ $registros->links('partials.pagination') }}
@endsection
