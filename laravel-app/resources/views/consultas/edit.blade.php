@extends('layouts.app')

@section('title', 'Editar consulta N.º ' . $consulta->id)

@section('content')
    @php
        $meds = old('medicamentos', $consulta->medicamentosSuministrados->map(fn ($m) => [
            'nombre'       => $m->medicamento->nombre,
            'dosis'        => $m->dosis,
            'frecuencia'   => $m->frecuencia,
            'duracion'     => $m->duracion,
            'indicaciones' => $m->indicaciones,
        ])->values()->toArray());
        if (empty($meds)) {
            $meds = [['nombre' => '', 'dosis' => '', 'frecuencia' => '', 'duracion' => '', 'indicaciones' => '']];
        }
    @endphp

    <div class="page-header">
        <a href="{{ route('consultas.show', $consulta) }}" class="link">← Volver a la consulta</a>
        <h1>Editar consulta N.º {{ $consulta->id }}</h1>
        <p>
            Paciente: <strong>{{ $consulta->paciente->nombre_completo }}</strong> ·
            Médico: {{ $consulta->medico->nombre }} {{ $consulta->medico->apellido }}
        </p>
    </div>

    <form method="POST" action="{{ route('consultas.update', $consulta) }}">
        @csrf
        @method('PUT')

        <fieldset>
            <legend>Tratamiento</legend>

            <div class="grid-2">
                <div class="form-group">
                    <label for="motivo_consulta">Motivo de Consulta</label>
                    <textarea id="motivo_consulta" name="motivo_consulta" rows="2">{{ old('motivo_consulta', $consulta->motivo_consulta) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="diagnostico">Diagnóstico</label>
                    <textarea id="diagnostico" name="diagnostico" rows="2">{{ old('diagnostico', $consulta->diagnostico) }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="tratamiento">Descripción del Tratamiento Aplicado *</label>
                <textarea id="tratamiento" name="tratamiento" rows="4" required
                          class="@error('tratamiento') is-invalid @enderror">{{ old('tratamiento', $consulta->tratamiento_aplicado) }}</textarea>
                @error('tratamiento') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones Adicionales</label>
                <textarea id="observaciones" name="observaciones" rows="2">{{ old('observaciones', $consulta->observaciones) }}</textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend>Medicamentos suministrados</legend>

            <div id="medicamentos-container">
                @foreach ($meds as $i => $med)
                    <div class="medicamento-item">
                        <div class="form-group">
                            <label>Nombre del Medicamento</label>
                            <input type="text" name="medicamentos[{{ $i }}][nombre]" value="{{ $med['nombre'] ?? '' }}">
                        </div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Dosis</label>
                                <input type="text" name="medicamentos[{{ $i }}][dosis]" value="{{ $med['dosis'] ?? '' }}" placeholder="Ej: 500mg">
                            </div>
                            <div class="form-group">
                                <label>Frecuencia</label>
                                <input type="text" name="medicamentos[{{ $i }}][frecuencia]" value="{{ $med['frecuencia'] ?? '' }}" placeholder="Ej: Cada 8 horas">
                            </div>
                            <div class="form-group">
                                <label>Duración</label>
                                <input type="text" name="medicamentos[{{ $i }}][duracion]" value="{{ $med['duracion'] ?? '' }}" placeholder="Ej: 7 días">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Indicaciones</label>
                            <textarea name="medicamentos[{{ $i }}][indicaciones]" rows="2">{{ $med['indicaciones'] ?? '' }}</textarea>
                        </div>
                        <button type="button" class="btn-danger btn-eliminar-medicamento">Eliminar</button>
                    </div>
                @endforeach
            </div>

            <button type="button" id="agregar-medicamento" class="btn-secondary">+ Agregar otro medicamento</button>
        </fieldset>

        <div class="wizard-nav">
            <a href="{{ route('consultas.show', $consulta) }}" class="btn-secondary">Cancelar</a>
            <span class="wizard-spacer"></span>
            <button type="submit" class="btn-primary">Guardar cambios</button>
        </div>
    </form>
@endsection
