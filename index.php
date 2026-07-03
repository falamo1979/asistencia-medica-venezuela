<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximun-scale=5.0">
    <title>Registro de Asistencia Médica</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Sistema de Asistencia Médica</h1>
            <p>Registro de consultas y tratamientos</p>
        </header>

        <form id="formularioAsistencia" action="procesar.php" method="POST">
            <!-- Token CSRF -->
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <!-- Sección Médico -->
            <fieldset>
                <legend>Datos del Médico Tratante</legend>
                
                <div class="form-group">
                    <label for="medico_matricula">Matrícula Profesional *</label>
                    <input type="text" id="medico_matricula" name="medico_matricula" required 
                           pattern="[A-Za-z0-9]+" title="Solo letras y números">
                </div>

                <div class="form-group">
                    <label for="medico_nombre">Nombre Completo</label>
                    <input type="text" id="medico_nombre" name="medico_nombre">
                </div>

                <div class="form-group">
                    <label for="medico_especialidad">Especialidad</label>
                    <input type="text" id="medico_especialidad" name="medico_especialidad">
                </div>
            </fieldset>

            <!-- Sección Paciente -->
            <fieldset>
                <legend>Datos del Paciente</legend>
                
                <div class="form-group">
                    <label for="paciente_nombre">Nombre *</label>
                    <input type="text" id="paciente_nombre" name="paciente_nombre" required>
                </div>

                <div class="form-group">
                    <label for="paciente_apellido">Apellido *</label>
                    <input type="text" id="paciente_apellido" name="paciente_apellido" required>
                </div>

                <div class="form-group">
                    <label for="paciente_dni">DNI</label>
                    <input type="text" id="paciente_dni" name="paciente_dni" 
                           pattern="[0-9]+" title="Solo números">
                    <small>Dejar vacío si no tiene identificación</small>
                </div>

                <div class="form-group">
                    <label for="paciente_fecha_nac">Fecha de Nacimiento</label>
                    <input type="date" id="paciente_fecha_nac" name="paciente_fecha_nac">
                </div>

                <div class="form-group">
                    <label for="paciente_sexo">Sexo</label>
                    <select id="paciente_sexo" name="paciente_sexo">
                        <option value="">Seleccione...</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="O">Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="paciente_telefono">Teléfono</label>
                    <input type="tel" id="paciente_telefono" name="paciente_telefono">
                </div>

                <div class="form-group">
                    <label for="paciente_rasgos">Rasgos o Señas Particulares</label>
                    <textarea id="paciente_rasgos" name="paciente_rasgos" rows="3" 
                              placeholder="Describa rasgos físicos distintivos si el paciente no tiene identificación"></textarea>
                </div>
            </fieldset>

            <!-- Sección Tratamiento -->
            <fieldset>
                <legend>Tratamiento Aplicado</legend>
                
                <div class="form-group">
                    <label for="motivo_consulta">Motivo de Consulta</label>
                    <textarea id="motivo_consulta" name="motivo_consulta" rows="2"></textarea>
                </div>

                <div class="form-group">
                    <label for="diagnostico">Diagnóstico</label>
                    <textarea id="diagnostico" name="diagnostico" rows="2"></textarea>
                </div>

                <div class="form-group">
                    <label for="tratamiento">Descripción del Tratamiento Aplicado *</label>
                    <textarea id="tratamiento" name="tratamiento" rows="4" required 
                              placeholder="Describa el procedimiento o tratamiento realizado"></textarea>
                </div>

                <div class="form-group">
                    <label for="observaciones">Observaciones Adicionales</label>
                    <textarea id="observaciones" name="observaciones" rows="2"></textarea>
                </div>
            </fieldset>

            <!-- Sección Medicamentos -->
            <fieldset>
                <legend>Medicamentos Suministrados</legend>
                
                <div id="medicamentos-container">
                    <div class="medicamento-item">
                        <div class="form-group">
                            <label>Nombre del Medicamento *</label>
                            <input type="text" name="medicamentos[0][nombre]" required>
                        </div>
                        <div class="form-group">
                            <label>Dosis</label>
                            <input type="text" name="medicamentos[0][dosis]" placeholder="Ej: 500mg">
                        </div>
                        <div class="form-group">
                            <label>Frecuencia</label>
                            <input type="text" name="medicamentos[0][frecuencia]" placeholder="Ej: Cada 8 horas">
                        </div>
                        <div class="form-group">
                            <label>Duración</label>
                            <input type="text" name="medicamentos[0][duracion]" placeholder="Ej: 7 días">
                        </div>
                        <div class="form-group">
                            <label>Indicaciones</label>
                            <textarea name="medicamentos[0][indicaciones]" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                
                <button type="button" id="agregar-medicamento" class="btn-secondary">
                    + Agregar Otro Medicamento
                </button>
            </fieldset>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Registrar Consulta</button>
                <button type="reset" class="btn-secondary">Limpiar Formulario</button>
            </div>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>