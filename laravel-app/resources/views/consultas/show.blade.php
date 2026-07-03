@extends('layouts.app')

@section('title', 'Consulta N.º ' . $consulta->id)

@section('content')
    @php $canManage = auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_MEDICO); @endphp

    <div class="detail-head">
        <div>
            <a href="{{ route('historial.index') }}" class="link">← Historial</a>
            <h1>
                Consulta N.º {{ $consulta->id }}
                @if ($consulta->isAnulada())
                    <span class="tag tag-anulada">Anulada</span>
                @endif
            </h1>
            <p class="muted">{{ $consulta->created_at->format('d/m/Y · H:i') }}</p>
        </div>
        <div class="detail-actions">
            <a href="{{ route('consultas.pdf', $consulta) }}" class="btn-secondary" target="_blank">Imprimir / PDF</a>
            @if ($canManage && ! $consulta->isAnulada())
                <button type="button" class="btn-secondary" data-modal-target="modal-consulta">Editar</button>
            @endif
        </div>
    </div>

    @if ($consulta->isAnulada())
        <div class="alert alert-error">
            <strong>Consulta anulada</strong>
            el {{ $consulta->anulada_at->format('d/m/Y H:i') }}
            @if ($consulta->anuladaPor) por {{ $consulta->anuladaPor->name }} @endif.<br>
            Motivo: {{ $consulta->motivo_anulacion }}
        </div>
    @endif

    <div class="info-card">
        <dl class="info-grid">
            <div>
                <dt>Paciente</dt>
                <dd>
                    <a href="{{ route('pacientes.show', $consulta->paciente) }}" class="link">
                        {{ $consulta->paciente->nombre_completo }}
                    </a>
                    @if ($consulta->paciente->dni) <span class="muted">· DNI {{ $consulta->paciente->dni }}</span> @endif
                </dd>
            </div>
            <div>
                <dt>Médico tratante</dt>
                <dd>
                    {{ $consulta->medico->nombre }} {{ $consulta->medico->apellido }}
                    @if ($consulta->medico->especialidad) <span class="muted">· {{ $consulta->medico->especialidad }}</span> @endif
                </dd>
            </div>
        </dl>
    </div>

    <h2 class="section-title">Detalle clínico</h2>
    <div class="consulta-card">
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
                            @if ($ms->indicaciones) <span class="muted">— {{ $ms->indicaciones }}</span> @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    @if ($canManage && ! $consulta->isAnulada())
        <details class="anular-box">
            <summary>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <path d="M12 9v4M12 17h.01"/>
                </svg>
                Anular esta consulta
            </summary>
            <form method="POST" action="{{ route('consultas.anular', $consulta) }}">
                @csrf
                @method('PATCH')
                <p class="muted">La consulta no se elimina: queda registrada como anulada y deja de contar en el historial.</p>
                <div class="form-group">
                    <label for="motivo_anulacion">Motivo de la anulación *</label>
                    <textarea id="motivo_anulacion" name="motivo_anulacion" rows="2" required
                              placeholder="Ej: registro duplicado / error de carga">{{ old('motivo_anulacion') }}</textarea>
                    @error('motivo_anulacion') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn-danger">Confirmar anulación</button>
            </form>
        </details>
    @endif

    @if ($canManage && ! $consulta->isAnulada())
        @include('consultas._edit_modal')
    @endif
@endsection
