@extends('layouts.app')

@section('title', 'Nueva Consulta')

@section('content')
    <div class="page-header">
        <h1>Nueva Consulta</h1>
        <p>Completá los datos en 4 pasos. Solo los campos con * son obligatorios.</p>
    </div>

    <div class="wizard" id="wizard">
        <ol class="stepper">
            <li class="stepper-item is-active">
                <span class="stepper-num">1</span>
                <span class="stepper-label">Médico</span>
            </li>
            <li class="stepper-item">
                <span class="stepper-num">2</span>
                <span class="stepper-label">Paciente</span>
            </li>
            <li class="stepper-item">
                <span class="stepper-num">3</span>
                <span class="stepper-label">Tratamiento</span>
            </li>
            <li class="stepper-item">
                <span class="stepper-num">4</span>
                <span class="stepper-label">Medicamentos</span>
            </li>
        </ol>

        <form id="formularioAsistencia" action="{{ route('consultas.store') }}" method="POST">
            @csrf

            {{-- Paso 1: Médico --}}
            <section class="form-step is-active">
                <h2 class="step-title">Médico tratante</h2>
                <p class="step-desc">Identificá al profesional. Si la matrícula ya existe, se reutiliza su ficha.</p>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="medico_matricula">Matrícula Profesional *</label>
                        <input type="text" id="medico_matricula" name="medico_matricula"
                               value="{{ old('medico_matricula') }}" required
                               pattern="[A-Za-z0-9]+" title="Solo letras y números"
                               class="@error('medico_matricula') is-invalid @enderror">
                        @error('medico_matricula') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="medico_especialidad">Especialidad</label>
                        <input type="text" id="medico_especialidad" name="medico_especialidad" value="{{ old('medico_especialidad') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="medico_nombre">Nombre Completo</label>
                    <input type="text" id="medico_nombre" name="medico_nombre" value="{{ old('medico_nombre') }}">
                    <small>Obligatorio solo si la matrícula no está registrada aún.</small>
                </div>
            </section>

            {{-- Paso 2: Paciente --}}
            <section class="form-step">
                <h2 class="step-title">Datos del paciente</h2>
                <p class="step-desc">Si tiene identificación, se reutiliza su ficha por DNI/cédula.</p>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="paciente_nombre">Nombre *</label>
                        <input type="text" id="paciente_nombre" name="paciente_nombre"
                               value="{{ old('paciente_nombre') }}" required
                               class="@error('paciente_nombre') is-invalid @enderror">
                        @error('paciente_nombre') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="paciente_apellido">Apellido *</label>
                        <input type="text" id="paciente_apellido" name="paciente_apellido"
                               value="{{ old('paciente_apellido') }}" required
                               class="@error('paciente_apellido') is-invalid @enderror">
                        @error('paciente_apellido') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="paciente_dni">DNI / Cédula</label>
                        <input type="text" id="paciente_dni" name="paciente_dni"
                               value="{{ old('paciente_dni') }}" pattern="[0-9]+" title="Solo números"
                               class="@error('paciente_dni') is-invalid @enderror">
                        <small>Dejar vacío si no tiene identificación.</small>
                        @error('paciente_dni') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="paciente_fecha_nac">Fecha de Nacimiento</label>
                        <input type="date" id="paciente_fecha_nac" name="paciente_fecha_nac" value="{{ old('paciente_fecha_nac') }}">
                    </div>

                    <div class="form-group">
                        <label for="paciente_sexo">Sexo</label>
                        <select id="paciente_sexo" name="paciente_sexo">
                            <option value="">Seleccione...</option>
                            <option value="M" @selected(old('paciente_sexo') === 'M')>Masculino</option>
                            <option value="F" @selected(old('paciente_sexo') === 'F')>Femenino</option>
                            <option value="O" @selected(old('paciente_sexo') === 'O')>Otro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="paciente_telefono">Teléfono</label>
                        <input type="tel" id="paciente_telefono" name="paciente_telefono" value="{{ old('paciente_telefono') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="paciente_rasgos">Rasgos o Señas Particulares</label>
                    <textarea id="paciente_rasgos" name="paciente_rasgos" rows="2"
                              placeholder="Rasgos físicos distintivos si el paciente no tiene identificación">{{ old('paciente_rasgos') }}</textarea>
                </div>
            </section>

            {{-- Paso 3: Tratamiento --}}
            <section class="form-step">
                <h2 class="step-title">Tratamiento aplicado</h2>
                <p class="step-desc">Registrá el motivo, diagnóstico y lo realizado en la consulta.</p>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="motivo_consulta">Motivo de Consulta</label>
                        <textarea id="motivo_consulta" name="motivo_consulta" rows="2">{{ old('motivo_consulta') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="diagnostico">Diagnóstico</label>
                        <textarea id="diagnostico" name="diagnostico" rows="2">{{ old('diagnostico') }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tratamiento">Descripción del Tratamiento Aplicado *</label>
                    <textarea id="tratamiento" name="tratamiento" rows="4" required
                              placeholder="Describa el procedimiento o tratamiento realizado"
                              class="@error('tratamiento') is-invalid @enderror">{{ old('tratamiento') }}</textarea>
                    @error('tratamiento') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="observaciones">Observaciones Adicionales</label>
                    <textarea id="observaciones" name="observaciones" rows="2">{{ old('observaciones') }}</textarea>
                </div>
            </section>

            {{-- Paso 4: Medicamentos --}}
            <section class="form-step">
                <h2 class="step-title">Medicamentos suministrados</h2>
                <p class="step-desc">Opcional. Agregá uno o varios medicamentos indicados.</p>

                <div id="medicamentos-container">
                    <div class="medicamento-item">
                        <div class="form-group">
                            <label>Nombre del Medicamento</label>
                            <input type="text" name="medicamentos[0][nombre]" value="{{ old('medicamentos.0.nombre') }}">
                        </div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Dosis</label>
                                <input type="text" name="medicamentos[0][dosis]" value="{{ old('medicamentos.0.dosis') }}" placeholder="Ej: 500mg">
                            </div>
                            <div class="form-group">
                                <label>Frecuencia</label>
                                <input type="text" name="medicamentos[0][frecuencia]" value="{{ old('medicamentos.0.frecuencia') }}" placeholder="Ej: Cada 8 horas">
                            </div>
                            <div class="form-group">
                                <label>Duración</label>
                                <input type="text" name="medicamentos[0][duracion]" value="{{ old('medicamentos.0.duracion') }}" placeholder="Ej: 7 días">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Indicaciones</label>
                            <textarea name="medicamentos[0][indicaciones]" rows="2">{{ old('medicamentos.0.indicaciones') }}</textarea>
                        </div>
                    </div>
                </div>

                <button type="button" id="agregar-medicamento" class="btn-secondary">
                    + Agregar otro medicamento
                </button>
            </section>

            {{-- Navegación del wizard --}}
            <div class="wizard-nav">
                <button type="button" class="btn-secondary" id="wizard-prev">← Anterior</button>
                <span class="wizard-spacer"></span>
                <button type="button" class="btn-primary" id="wizard-next">Siguiente →</button>
                <button type="submit" class="btn-primary" id="wizard-submit" hidden>Registrar Consulta</button>
            </div>
        </form>
    </div>
@endsection
