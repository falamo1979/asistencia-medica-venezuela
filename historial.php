<?php

require_once 'config.php';
// Configuración de la base de datos (Usá las mismas que en procesar.php)

// Conexión a PostgreSQL


// Obtener la fecha del formulario (o usar la de hoy por defecto)
$fecha_busqueda = $_GET['fecha'] ?? date('Y-m-d');

// Consulta SQL: Buscamos las consultas de esa fecha y unimos con pacientes para ver el nombre
$sql = "
    SELECT 
        c.id, 
        p.nombre || ' ' || p.apellido AS paciente, 
        c.motivo_consulta, 
        c.tratamiento_aplicado, 
        c.fecha_registro 
    FROM consultas c
    JOIN pacientes p ON c.paciente_id = p.id
    WHERE c.fecha_registro::date = :fecha
    ORDER BY c.fecha_registro DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['fecha' => $fecha_busqueda]);
$registros = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximun-scale=5.0">
    <title>Historial de Atenciones</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos rápidos para la tabla */
        .tabla-container { margin-top: 30px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .filtros { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="tabla-container">
    <div class="container">
        <header>
            <h1>Historial de Atenciones</h1>
            <p>Buscar pacientes atendidos por fecha</p>
            <a href="index.php" style="color: #007bff; text-decoration: none;">&larr; Volver al formulario</a>
        </header>

        <!-- Formulario de búsqueda -->
        <form method="GET" class="filtros">
            <div class="form-group">
                <label for="fecha">Seleccionar Fecha:</label>
                <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($fecha_busqueda) ?>" required>
                <button type="submit" class="btn-primary" style="margin-left: 10px;">Buscar</button>
            </div>
        </form>

        <!-- Tabla de resultados -->
        <div class="tabla-container">
            <h3>Atenciones del <?= date('d/m/Y', strtotime($fecha_busqueda)) ?></h3>
            
            <?php if (count($registros) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Motivo</th>
                            <th>Tratamiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg): ?>
                            <tr>
                                <td><?= date('H:i', strtotime($reg['fecha_registro'])) ?></td>
                                <td><?= htmlspecialchars($reg['paciente']) ?></td>
                                <td><?= htmlspecialchars($reg['motivo_consulta']) ?></td>
                                <td><?= htmlspecialchars($reg['tratamiento_aplicado']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #666; margin-top: 20px;">
                    No se encontraron atenciones para esta fecha.
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>