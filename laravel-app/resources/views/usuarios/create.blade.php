@extends('layouts.app')

@section('title', 'Nuevo usuario')

@section('content')
    <div class="page-header">
        <a href="{{ route('usuarios.index') }}" class="link">← Usuarios</a>
        <h1>Nuevo usuario</h1>
    </div>

    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf

        <div class="grid-2">
            <div class="form-group">
                <label for="name">Nombre *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="@error('name') is-invalid @enderror">
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="@error('email') is-invalid @enderror">
                @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="role">Rol *</label>
                <select id="role" name="role" required class="@error('role') is-invalid @enderror">
                    @foreach (\App\Models\User::ROLES as $value => $label)
                        <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="medico_id">Médico vinculado</label>
                <select id="medico_id" name="medico_id">
                    <option value="">Ninguno</option>
                    @foreach ($medicos as $medico)
                        <option value="{{ $medico->id }}" @selected((int) old('medico_id') === $medico->id)>
                            {{ $medico->nombre }} {{ $medico->apellido }} ({{ $medico->matricula }})
                        </option>
                    @endforeach
                </select>
                <small>Solo aplica si el rol es Médico.</small>
            </div>

            <div class="form-group">
                <label for="password">Contraseña *</label>
                <input type="password" id="password" name="password" required autocomplete="new-password"
                       class="@error('password') is-invalid @enderror">
                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            </div>
        </div>

        <div class="wizard-nav">
            <a href="{{ route('usuarios.index') }}" class="btn-secondary">Cancelar</a>
            <span class="wizard-spacer"></span>
            <button type="submit" class="btn-primary">Crear usuario</button>
        </div>
    </form>
@endsection
