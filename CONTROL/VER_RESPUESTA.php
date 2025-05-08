<?php
include('../MODELO/CL_CONEXION.php');

session_start();

// Verificación de autenticación (sin cambios)
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

$id_asignacion = $_GET['id_asignacion'] ?? null;
if (!$id_asignacion) {
    echo "ID de asignación no especificado o inválido.";
    exit();
}

// Conexión y consultas (sin cambios)
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

$stmt = $pdo->prepare("SELECT p.texto AS pregunta, r.respuesta, r.calificacion, r.observacion 
                      FROM RESPUESTA r
                      JOIN PREGUNTA p ON r.id_pregunta = p.id_pregunta
                      WHERE r.id_asignacion = ?");
$stmt->execute([$id_asignacion]);
$respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    /* Manteniendo tus colores originales */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7fb;
        margin: 0;
        padding: 20px;
        color: #2c3e50;
    }

    .container {
        max-width: 100%;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #34495e;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
    }

    .table-responsive {
        overflow-x: auto;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        min-width: 600px;
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

    table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    table tbody tr:hover {
        background-color: #e9ecef;
    }

    .btn-container {
        text-align: center;
        margin-top: 20px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 4px;
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
        transition: color 0.3s ease;
    }

    .btn:hover i {
        color: #fff;
    }

    /* Estilos responsivos */
    @media (max-width: 768px) {
        .container {
            padding: 15px;
            border-radius: 0;
        }
        
        h2 {
            font-size: 1.5rem;
        }
        
        table th, table td {
            padding: 8px 10px;
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        body {
            padding: 10px;
        }
        
        .container {
            padding: 10px;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
            padding: 12px;
        }
    }

    /* Estilo para móviles muy pequeños */
    @media (max-width: 480px) {
        table {
            display: block;
            width: 100%;
        }
        
        table thead {
            display: none;
        }
        
        table tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            text-align: right;
            border-bottom: 1px solid #eee;
        }
        
        table tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 10px;
            text-align: left;
        }
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Respuestas del Formulario</h2>
        
        <div class="table-responsive">
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
                            <td data-label="Pregunta"><?= htmlspecialchars($respuesta['pregunta']) ?></td>
                            <td data-label="Respuesta"><?= htmlspecialchars($respuesta['respuesta']) ?></td>
                            <td data-label="Calificación"><?= $respuesta['calificacion'] == 1 ? 'Bien' : 'Mal' ?></td>
                            <td data-label="Observación"><?= htmlspecialchars($respuesta['observacion']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="btn-container">
            <form action="ver_usuarios_formulario.php" method="get">
                <input type="hidden" name="id_formulario" value="<?= htmlspecialchars($id_formulario) ?>">
                <button type="submit" class="btn"><i class="fas fa-arrow-left"></i> Regresar</button>
            </form>
        </div>
    </div>
</body>
</html>