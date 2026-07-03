<div class="modal-overlay" id="modal-paciente" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true">
        <div class="modal-head">
            <span class="modal-title">Editar ficha del paciente</span>
            <button type="button" class="modal-close" data-close aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="editing_url" value="{{ old('editing_url') }}">

                <div class="grid-2">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required
                               class="@error('nombre') is-invalid @enderror">
                        @error('nombre') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Apellido *</label>
                        <input type="text" name="apellido" value="{{ old('apellido') }}" required
                               class="@error('apellido') is-invalid @enderror">
                        @error('apellido') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>DNI / Cédula</label>
                        <input type="text" name="dni" value="{{ old('dni') }}" pattern="[0-9]+" title="Solo números"
                               class="@error('dni') is-invalid @enderror">
                        @error('dni') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <select name="sexo">
                            <option value="">Seleccione...</option>
                            <option value="M" @selected(old('sexo') === 'M')>Masculino</option>
                            <option value="F" @selected(old('sexo') === 'F')>Femenino</option>
                            <option value="O" @selected(old('sexo') === 'O')>Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" name="telefono" value="{{ old('telefono') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Rasgos o Señas Particulares</label>
                    <textarea name="rasgos_particulares" rows="2">{{ old('rasgos_particulares') }}</textarea>
                </div>

                <div class="wizard-nav">
                    <button type="button" class="btn-secondary" data-close>Cancelar</button>
                    <span class="wizard-spacer"></span>
                    <button type="submit" class="btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any() && old('editing_url'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const m = document.getElementById('modal-paciente');
            m.querySelector('form').action = @json(old('editing_url'));
            openModal(m);
        });
    </script>
@endif
