@extends('layouts.app')

@section('title', 'Ficha de ' . $paciente->nombre_completo)

@section('content')
    <div class="detail-head">
        <div>
            <a href="{{ route('pacientes.index') }}" class="link">← Pacientes</a>
            <h1>{{ $paciente->nombre_completo }}</h1>
        </div>
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
        <button type="button" class="btn-secondary"
                data-modal-target="modal-paciente"
                data-action="{{ route('pacientes.update', $paciente) }}"
                data-method="PUT"
                data-title="Editar ficha de {{ $paciente->nombre_completo }}"
                data-values='@json($pv)'>Editar ficha</button>
    </div>

    {{-- Datos personales --}}
    <div class="info-card">
        <dl class="info-grid">
            <div>
                <dt>DNI / Cédula</dt>
                <dd>{{ $paciente->dni ?: '—' }}</dd>
            </div>
            <div>
                <dt>Sexo</dt>
                <dd>{{ $paciente->sexo_label ?: '—' }}</dd>
            </div>
            <div>
                <dt>Fecha de nacimiento</dt>
                <dd>
                    @if ($paciente->fecha_nacimiento)
                        {{ $paciente->fecha_nacimiento->format('d/m/Y') }}
                        <span class="muted">({{ $paciente->edad }} años)</span>
                    @else
                        —
                    @endif
                </dd>
            </div>
            <div>
                <dt>Teléfono</dt>
                <dd>{{ $paciente->telefono ?: '—' }}</dd>
            </div>
            <div class="info-wide">
                <dt>Rasgos particulares</dt>
                <dd>{{ $paciente->rasgos_particulares ?: '—' }}</dd>
            </div>
        </dl>
    </div>

    {{-- Historial clínico --}}
    <h2 class="section-title">Historial clínico ({{ $paciente->consultas->count() }})</h2>

    @forelse ($paciente->consultas as $consulta)
        <article class="consulta-card {{ $consulta->isAnulada() ? 'is-anulada' : '' }}">
            <header class="consulta-head">
                <span class="consulta-date">
                    {{ $consulta->created_at->format('d/m/Y · H:i') }}
                    @if ($consulta->isAnulada())
                        <span class="tag tag-anulada">Anulada</span>
                    @endif
                    <a href="{{ route('consultas.show', $consulta) }}" class="link" style="font-weight:500; margin-left:6px;">Ver detalle</a>
                </span>
                <span class="consulta-medico">
                    Dr(a). {{ $consulta->medico->nombre }} {{ $consulta->medico->apellido }}
                    @if ($consulta->medico->especialidad)
                        · {{ $consulta->medico->especialidad }}
                    @endif
                </span>
            </header>

            <dl class="consulta-body">
                @if ($consulta->motivo_consulta)
                    <dt>Motivo</dt><dd>{{ $consulta->motivo_consulta }}</dd>
                @endif
                @if ($consulta->diagnostico)
                    <dt>Diagnóstico</dt><dd>{{ $consulta->diagnostico }}</dd>
                @endif
                <dt>Tratamiento</dt><dd>{{ $consulta->tratamiento_aplicado }}</dd>
                @if ($consulta->observaciones)
                    <dt>Observaciones</dt><dd>{{ $consulta->observaciones }}</dd>
                @endif
            </dl>

            @if ($consulta->medicamentosSuministrados->isNotEmpty())
                <div class="med-list">
                    <span class="med-list-title">Medicamentos</span>
                    <ul>
                        @foreach ($consulta->medicamentosSuministrados as $ms)
                            <li>
                                <strong>{{ $ms->medicamento->nombre }}</strong>
                                @if ($ms->dosis) · {{ $ms->dosis }} @endif
                                @if ($ms->frecuencia) · {{ $ms->frecuencia }} @endif
                                @if ($ms->duracion) · {{ $ms->duracion }} @endif
                                @if ($ms->indicaciones)
                                    <span class="muted">— {{ $ms->indicaciones }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </article>
    @empty
        <p class="empty-state">Este paciente no tiene consultas registradas.</p>
    @endforelse

    @include('pacientes._edit_modal')
@endsection
