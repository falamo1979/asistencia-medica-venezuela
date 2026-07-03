<div class="modal-overlay" id="modal-medico" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true">
        <div class="modal-head">
            <span class="modal-title">Editar médico</span>
            <button type="button" class="modal-close" data-close aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="editing_url" value="{{ old('editing_url') }}">

                <div class="grid-2">
                    <div class="form-group">
                        <label>Matrícula *</label>
                        <input type="text" name="matricula" value="{{ old('matricula') }}" required
                               pattern="[A-Za-z0-9]+" title="Solo letras y números"
                               class="@error('matricula') is-invalid @enderror">
                        @error('matricula') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Especialidad</label>
                        <input type="text" name="especialidad" value="{{ old('especialidad') }}">
                    </div>
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required
                               class="@error('nombre') is-invalid @enderror">
                        @error('nombre') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" name="apellido" value="{{ old('apellido') }}">
                    </div>
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
            const m = document.getElementById('modal-medico');
            m.querySelector('form').action = @json(old('editing_url'));
            openModal(m);
        });
    </script>
@endif
