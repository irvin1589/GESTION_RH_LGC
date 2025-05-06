<?php
session_start();
require_once '../MODELO/CL_CONEXION.php';
require_once '../MODELO/CL_TABLA_FORMULARIO.php';

if (!isset($_GET['id_asignacion'])) {
    echo "Formulario no especificado.";
    exit;
}

session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

$idAsignacion = $_GET['id_asignacion'];
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

$stmt = $pdo->prepare("SELECT id_formulario FROM ASIGNACION_FORMULARIO WHERE id_asignacion = ?");
$stmt->execute([$idAsignacion]);
$formulario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$formulario) {
    echo "Asignación no encontrada.";
    exit;
}

$idFormulario = $formulario['id_formulario'];

$t_formulario = new CL_TABLA_FORMULARIO();
$datosFormulario = $t_formulario->obtener_formulario_por_id($idFormulario);
echo "<h2>Formulario: " . htmlspecialchars($datosFormulario['nombre']) . "</h2>";

$stmt = $pdo->prepare("SELECT * FROM PREGUNTA WHERE id_formulario = ? ORDER BY orden");
$stmt->execute([$idFormulario]);
$preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT id_pregunta, respuesta FROM RESPUESTA WHERE id_asignacion = ?");
$stmt->execute([$idAsignacion]);
$respuestas = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Respondido</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .contenedor {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        .pregunta {
            margin-bottom: 20px;
        }
        .pregunta label {
            font-weight: bold;
        }
        .respuesta {
            margin-top: 5px;
            color: #333;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<div class="contenedor">
    <?php foreach ($preguntas as $pregunta): ?>
        <div class="pregunta">
            <label><?= htmlspecialchars($pregunta['texto']) ?></label>
            <div class="respuesta">
                <?= isset($respuestas[$pregunta['id_pregunta']]) 
                        ? htmlspecialchars($respuestas[$pregunta['id_pregunta']]) 
                        : '<em>No respondida</em>' ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
