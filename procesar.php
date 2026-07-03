<?php
// Configuración de seguridad
header('Content-Type: application/json');
session_start();
require_once 'config.php';

// Configuración de la base de datos
//$db_host = 'localhost';
//$db_name = 'asistencia_medica';
//$db_user = 'admin';
//$db_pass = 'admin';

// Función para sanitizar inputs
function sanitizar($dato) {
    return htmlspecialchars(strip_tags(trim($dato)), ENT_QUOTES, 'UTF-8');
}

try {
    // Verificar método POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Verificar token CSRF
    //if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
      //  $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      //  throw new Exception('Token CSRF inválido');
   // }

    // Conexión a PostgreSQL con PDO (prepared statements)
    $dsn = "pgsql:host=$db_host;dbname=$db_name";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    // Procesar datos del médico (SIN CONTRASEÑA)
    $medico_matricula = sanitizar($_POST['medico_matricula']);
    $medico_nombre = sanitizar($_POST['medico_nombre'] ?? '');
    $medico_especialidad = sanitizar($_POST['medico_especialidad'] ?? '');

    // Verificar si el médico existe por matrícula
    $stmt = $pdo->prepare("SELECT id FROM medicos WHERE matricula = ?");
    $stmt->execute([$medico_matricula]);
    $medico = $stmt->fetch();

    if ($medico) {
        // Médico ya existe, usar su ID
        $medico_id = $medico['id'];
        
        // Opcional: Actualizar datos del médico si cambió algo
        if (!empty($medico_nombre) || !empty($medico_especialidad)) {
            $stmt = $pdo->prepare("
                UPDATE medicos 
                SET nombre = COALESCE(NULLIF(?, ''), nombre),
                    especialidad = COALESCE(NULLIF(?, ''), especialidad)
                WHERE id = ?
            ");
            $stmt->execute([$medico_nombre, $medico_especialidad, $medico_id]);
        }
    } else {
        // Crear nuevo médico (sin contraseña)
        if (empty($medico_nombre)) {
            throw new Exception('El nombre del médico es obligatorio para registro nuevo');
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO medicos (matricula, nombre, apellido, especialidad) 
            VALUES (?, ?, ?, ?)
            RETURNING id
        ");
        $stmt->execute([
            $medico_matricula,
            $medico_nombre,
            '', // apellido opcional
            $medico_especialidad
        ]);
        $medico_id = $stmt->fetchColumn();
    }

    // Procesar datos del paciente
    $paciente_dni = sanitizar($_POST['paciente_dni'] ?? '');
    
    // Buscar paciente por DNI o crear nuevo
    if (!empty($paciente_dni)) {
        $stmt = $pdo->prepare("SELECT id FROM pacientes WHERE dni = ?");
        $stmt->execute([$paciente_dni]);
        $paciente = $stmt->fetch();
        
        if ($paciente) {
            $paciente_id = $paciente['id'];
        }
    }

    if (!isset($paciente_id)) {
        // Crear nuevo paciente
        $stmt = $pdo->prepare("
            INSERT INTO pacientes (nombre, apellido, dni, fecha_nacimiento, sexo, telefono, rasgos_particulares)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            RETURNING id
        ");
        $stmt->execute([
            sanitizar($_POST['paciente_nombre']),
            sanitizar($_POST['paciente_apellido']),
            $paciente_dni ?: null,
            $_POST['paciente_fecha_nac'] ?: null,
            sanitizar($_POST['paciente_sexo'] ?? ''),
            sanitizar($_POST['paciente_telefono'] ?? ''),
            sanitizar($_POST['paciente_rasgos'] ?? '')
        ]);
        $paciente_id = $stmt->fetchColumn();
    }

    // Registrar consulta
    $stmt = $pdo->prepare("
        INSERT INTO consultas (paciente_id, medico_id, motivo_consulta, diagnostico, tratamiento_aplicado, observaciones)
        VALUES (?, ?, ?, ?, ?, ?)
        RETURNING id
    ");
    $stmt->execute([
        $paciente_id,
        $medico_id,
        sanitizar($_POST['motivo_consulta'] ?? ''),
        sanitizar($_POST['diagnostico'] ?? ''),
        sanitizar($_POST['tratamiento']),
        sanitizar($_POST['observaciones'] ?? '')
    ]);
    $consulta_id = $stmt->fetchColumn();

    // Procesar medicamentos
    if (isset($_POST['medicamentos']) && is_array($_POST['medicamentos'])) {
        foreach ($_POST['medicamentos'] as $med) {
            if (empty($med['nombre'])) continue;

            // Buscar o crear medicamento
            $stmt = $pdo->prepare("SELECT id FROM medicamentos WHERE nombre = ?");
            $stmt->execute([sanitizar($med['nombre'])]);
            $medicamento = $stmt->fetch();

            if (!$medicamento) {
                $stmt = $pdo->prepare("
                    INSERT INTO medicamentos (nombre) VALUES (?) RETURNING id
                ");
                $stmt->execute([sanitizar($med['nombre'])]);
                $medicamento_id = $stmt->fetchColumn();
            } else {
                $medicamento_id = $medicamento['id'];
            }

            // Registrar medicamento suministrado
            $stmt = $pdo->prepare("
                INSERT INTO medicamentos_suministrados (consulta_id, medicamento_id, dosis, frecuencia, duracion, indicaciones)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $consulta_id,
                $medicamento_id,
                sanitizar($med['dosis'] ?? ''),
                sanitizar($med['frecuencia'] ?? ''),
                sanitizar($med['duracion'] ?? ''),
                sanitizar($med['indicaciones'] ?? '')
            ]);
        }
    }

    // Regenerar token CSRF después de uso exitoso
    unset($_SESSION['csrf_token']);

    echo json_encode([
        'success' => true,
        'message' => 'Consulta registrada exitosamente',
        'consulta_id' => $consulta_id
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error de Base de Datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error General: ' . $e->getMessage()
    ]);
}
?>