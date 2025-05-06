<?php
include('../MODELO/CL_CONEXION.php');

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

// Verificar si el usuario tiene permisos
if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

// Verificar si se recibió el ID de la asignación
$id_asignacion = $_GET['id_asignacion'] ?? null;
if (!$id_asignacion) {
    echo "ID de asignación no especificado o inválido.";
    exit();
}

// Conexión a la base de datos
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

// Consultar las respuestas asociadas al ID de asignación
$stmt = $pdo->prepare("SELECT p.texto AS pregunta, r.respuesta, r.calificacion, r.observacion 
                      FROM RESPUESTA r
                      JOIN PREGUNTA p ON r.id_pregunta = p.id_pregunta
                      WHERE r.id_asignacion = ?");
$stmt->execute([$id_asignacion]);
$respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Depuración: Verifica si se obtuvieron resultados
if (empty($respuestas)) {
    echo "No se encontraron respuestas para el ID de asignación: " . htmlspecialchars($id_asignacion);
    exit();
}
if (isset($_POST['regresar'])) {
    header('Location: ver_usuarios_formulario.php');
    exit();
}

$stmt_form = $pdo->prepare("SELECT id_formulario FROM ASIGNACION_FORMULARIO WHERE id_asignacion = ?");
$stmt_form->execute([$id_asignacion]);
$id_formulario = $stmt_form->fetchColumn();

if (!$id_formulario) {
    echo "No se pudo encontrar el formulario relacionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Respuestas</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7fb;
        margin: 0;
        padding: 20px;
        color: #2c3e50;
    }

    .container {
        max-width: 1000px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #34495e;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    table thead {
        background-color: #3498db;
        color: #fff;
    }

    table th, table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        font-size: 15px;
        font-weight: 600;
        text-transform: uppercase;
        border: none;
        border-radius: 25px;
        background-color: transparent;
        color: #3498db;
        border: 2px solid #3498db;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn:hover {
        background-color: #3498db;
        color: #fff;
    }

    .btn i {
        color: #3498db;
        transition: color 0.3s ease;
    }

    .btn:hover i {
        color: #fff;
    }
</style>
</head>
<body>
    <div class="container">
        <h2>Respuestas del Formulario</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Pregunta</th>
                    <th>Respuesta</th>
                    <th>Calificación</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($respuestas as $respuesta): ?>
                    <tr>
                        <td><?= htmlspecialchars($respuesta['pregunta']) ?></td>
                        <td><?= htmlspecialchars($respuesta['respuesta']) ?></td>
                        <td><?= htmlspecialchars($respuesta['calificacion']) ?></td>
                        <td><?= htmlspecialchars($respuesta['observacion']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form action="ver_usuarios_formulario.php" method="get">
            <input type="hidden" name="id_formulario" value="<?= htmlspecialchars($id_formulario) ?>">
            <button type="submit" class="btn"><i class="fas fa-arrow-left"></i> Regresar</button>
        </form>
    </div>
</body>
</html>