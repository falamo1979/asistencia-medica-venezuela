<div class="modal-overlay" id="modal-usuario" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true">
        <div class="modal-head">
            <span class="modal-title">Usuario</span>
            <button type="button" class="modal-close" data-close aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="editing_url" value="{{ old('editing_url') }}">

                <div class="grid-2">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="@error('name') is-invalid @enderror">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="@error('email') is-invalid @enderror">
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Rol *</label>
                        <select name="role" required class="@error('role') is-invalid @enderror">
                            @foreach (\App\Models\User::ROLES as $value => $label)
                                <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Médico vinculado</label>
                        <select name="medico_id">
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
                        <label>Contraseña <span class="pw-hint muted">(dejar en blanco para no cambiarla)</span></label>
                        <input type="password" name="password" autocomplete="new-password"
                               data-required-on="create"
                               class="@error('password') is-invalid @enderror">
                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" autocomplete="new-password">
                    </div>
                </div>

                <div class="wizard-nav">
                    <button type="button" class="btn-secondary" data-close>Cancelar</button>
                    <span class="wizard-spacer"></span>
                    <button type="submit" class="btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any() && old('editing_url'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const m = document.getElementById('modal-usuario');
            const form = m.querySelector('form');
            form.action = @json(old('editing_url'));
            form.querySelector('[name="_method"]').value = @json(old('_method', 'POST'));
            openModal(m);
        });
    </script>
@endif
