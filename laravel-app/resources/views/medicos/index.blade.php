@extends('layouts.app')

@section('title', 'Médicos')

@section('content')
    <div class="page-header">
        <h1>Administración</h1>
        <p>Gestión del personal y los médicos del centro.</p>
    </div>

    @include('partials.admin_nav')

    <h2 class="section-title">Médicos registrados</h2>

    <div class="tabla-container">
        @forelse ($medicos as $medico)
            @if ($loop->first)
                <table>
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Nombre</th>
                            <th>Especialidad</th>
                            <th>Consultas</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
            @endif
                        <tr>
                            <td>{{ $medico->matricula }}</td>
                            <td>{{ $medico->nombre }} {{ $medico->apellido }}</td>
                            <td>{{ $medico->especialidad ?: '—' }}</td>
                            <td><span class="badge">{{ $medico->consultas_count }}</span></td>
                            @php
                                $mv = [
                                    'matricula' => $medico->matricula,
                                    'nombre' => $medico->nombre,
                                    'apellido' => $medico->apellido,
                                    'especialidad' => $medico->especialidad,
                                ];
                            @endphp
                            <td class="cell-actions">
                                <button type="button" class="link-btn"
                                        data-modal-target="modal-medico"
                                        data-action="{{ route('medicos.update', $medico) }}"
                                        data-method="PUT"
                                        data-title="Editar a {{ $medico->nombre }} {{ $medico->apellido }}"
                                        data-values='@json($mv)'>Editar</button>
                            </td>
                        </tr>
            @if ($loop->last)
                    </tbody>
                </table>
            @endif
        @empty
            <p class="empty-state">No hay médicos registrados todavía.</p>
        @endforelse
    </div>

    {{ $medicos->links('partials.pagination') }}

    @include('medicos._edit_modal')
@endsection
