@extends('layouts.app')

@section('title', 'Editar médico')

@section('content')
    <div class="page-header">
        <a href="{{ route('medicos.index') }}" class="link">← Médicos</a>
        <h1>Editar médico</h1>
    </div>

    <form method="POST" action="{{ route('medicos.update', $medico) }}">
        @csrf
        @method('PUT')

        <div class="grid-2">
            <div class="form-group">
                <label for="matricula">Matrícula *</label>
                <input type="text" id="matricula" name="matricula" value="{{ old('matricula', $medico->matricula) }}"
                       required pattern="[A-Za-z0-9]+" title="Solo letras y números"
                       class="@error('matricula') is-invalid @enderror">
                @error('matricula') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="especialidad">Especialidad</label>
                <input type="text" id="especialidad" name="especialidad" value="{{ old('especialidad', $medico->especialidad) }}">
            </div>

            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $medico->nombre) }}" required
                       class="@error('nombre') is-invalid @enderror">
                @error('nombre') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $medico->apellido) }}">
            </div>
        </div>

        <div class="wizard-nav">
            <a href="{{ route('medicos.index') }}" class="btn-secondary">Cancelar</a>
            <span class="wizard-spacer"></span>
            <button type="submit" class="btn-primary">Guardar cambios</button>
        </div>
    </form>
@endsection
