@extends('layouts.app')

@section('title', 'Pacientes')

@section('content')
    <div class="page-header">
        <h1>Pacientes</h1>
        <p>Buscá y consultá las fichas de los pacientes registrados.</p>
    </div>

    <form method="GET" action="{{ route('pacientes.index') }}" class="filtros">
        <div class="form-group">
            <label for="q">Buscar por nombre, apellido o DNI</label>
            <input type="search" id="q" name="q" value="{{ $q }}" placeholder="Ej: González o 12345678">
            <button type="submit" class="btn-primary">Buscar</button>
            @if ($q !== '')
                <a href="{{ route('pacientes.index') }}" class="btn-secondary">Limpiar</a>
            @endif
        </div>
    </form>

    <div class="tabla-container">
        <h3>
            @if ($q !== '')
                Resultados para “{{ $q }}”
            @else
                Todos los pacientes
            @endif
        </h3>

        @forelse ($pacientes as $paciente)
            @if ($loop->first)
                <table>
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>DNI</th>
                            <th>Sexo</th>
                            <th>Teléfono</th>
                            <th>Consultas</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
            @endif
                        <tr>
                            <td>
                                <a href="{{ route('pacientes.show', $paciente) }}" class="link">
                                    {{ $paciente->nombre_completo }}
                                </a>
                            </td>
                            <td>{{ $paciente->dni ?: '—' }}</td>
                            <td>{{ $paciente->sexo_label ?: '—' }}</td>
                            <td>{{ $paciente->telefono ?: '—' }}</td>
                            <td><span class="badge">{{ $paciente->consultas_count }}</span></td>
                            @php
                                $pv = [
                                    'nombre' => $paciente->nombre,
                                    'apellido' => $paciente->apellido,
                                    'dni' => $paciente->dni,
                                    'fecha_nacimiento' => optional($paciente->fecha_nacimiento)->format('Y-m-d'),
                                    'sexo' => $paciente->sexo,
                                    'telefono' => $paciente->telefono,
                                    'rasgos_particulares' => $paciente->rasgos_particulares,
                                ];
                            @endphp
                            <td class="cell-actions">
                                <a href="{{ route('pacientes.show', $paciente) }}" class="link">Ver</a>
                                <button type="button" class="link-btn"
                                        data-modal-target="modal-paciente"
                                        data-action="{{ route('pacientes.update', $paciente) }}"
                                        data-method="PUT"
                                        data-title="Editar a {{ $paciente->nombre_completo }}"
                                        data-values='@json($pv)'>Editar</button>
                            </td>
                        </tr>
            @if ($loop->last)
                    </tbody>
                </table>
            @endif
        @empty
            <p class="empty-state">
                @if ($q !== '')
                    No se encontraron pacientes para “{{ $q }}”.
                @else
                    Todavía no hay pacientes registrados.
                @endif
            </p>
        @endforelse
    </div>

    {{ $pacientes->links('partials.pagination') }}

    @include('pacientes._edit_modal')
@endsection
