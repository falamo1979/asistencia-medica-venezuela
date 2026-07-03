@extends('layouts.app')

@section('title', 'Editar ' . $paciente->nombre_completo)

@section('content')
    <div class="page-header">
        <a href="{{ route('pacientes.show', $paciente) }}" class="link">← Volver a la ficha</a>
        <h1>Editar ficha del paciente</h1>
    </div>

    <form method="POST" action="{{ route('pacientes.update', $paciente) }}">
        @csrf
        @method('PUT')

        <div class="grid-2">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre"
                       value="{{ old('nombre', $paciente->nombre) }}" required
                       class="@error('nombre') is-invalid @enderror">
                @error('nombre') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="apellido">Apellido *</label>
                <input type="text" id="apellido" name="apellido"
                       value="{{ old('apellido', $paciente->apellido) }}" required
                       class="@error('apellido') is-invalid @enderror">
                @error('apellido') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="dni">DNI / Cédula</label>
                <input type="text" id="dni" name="dni"
                       value="{{ old('dni', $paciente->dni) }}" pattern="[0-9]+" title="Solo números"
                       class="@error('dni') is-invalid @enderror">
                @error('dni') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                       value="{{ old('fecha_nacimiento', optional($paciente->fecha_nacimiento)->format('Y-m-d')) }}"
                       class="@error('fecha_nacimiento') is-invalid @enderror">
                @error('fecha_nacimiento') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="sexo">Sexo</label>
                <select id="sexo" name="sexo">
                    <option value="">Seleccione...</option>
                    <option value="M" @selected(old('sexo', $paciente->sexo) === 'M')>Masculino</option>
                    <option value="F" @selected(old('sexo', $paciente->sexo) === 'F')>Femenino</option>
                    <option value="O" @selected(old('sexo', $paciente->sexo) === 'O')>Otro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" value="{{ old('telefono', $paciente->telefono) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="rasgos_particulares">Rasgos o Señas Particulares</label>
            <textarea id="rasgos_particulares" name="rasgos_particulares" rows="3">{{ old('rasgos_particulares', $paciente->rasgos_particulares) }}</textarea>
        </div>

        <div class="wizard-nav">
            <a href="{{ route('pacientes.show', $paciente) }}" class="btn-secondary">Cancelar</a>
            <span class="wizard-spacer"></span>
            <button type="submit" class="btn-primary">Guardar cambios</button>
        </div>
    </form>
@endsection
