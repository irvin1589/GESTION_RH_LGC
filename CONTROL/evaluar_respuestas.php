<?php
session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

require_once '../MODELO/CL_CONEXION.php';

if (!isset($_GET['id_asignacion'])) {
    echo "Asignación no especificada.";
    exit;
}

$idAsignacion = $_GET['id_asignacion'];
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();


$stmt = $pdo->prepare("
    SELECT R.id_respuesta, R.id_pregunta, P.texto AS pregunta, R.respuesta, R.calificacion, R.observacion
    FROM RESPUESTA R
    JOIN PREGUNTA P ON R.id_pregunta = P.id_pregunta
    WHERE R.id_asignacion = ?
");
$stmt->execute([$idAsignacion]);
$respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluar Formulario</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        form { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h2 { text-align: center; }
        .bloque { margin-bottom: 25px; }
        .respuesta { margin: 5px 0 10px 0; background: #f0f0f0; padding: 10px; border-radius: 5px; }
        textarea, input[type="number"] { width: 100%; padding: 8px; margin-top: 5px; }
        button { background: #007BFF; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .calificacion-switch {
    margin: 15px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: bold;
}

.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 4px;
    bottom: 3px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #28a745;
}

input:checked + .slider:before {
    transform: translateX(24px);
}


    </style>
</head>
<body>
    <form action="guardar_evaluacion.php" method="post">
        <input type="hidden" name="id_asignacion" value="<?= $idAsignacion ?>">
        <h2>Evaluación de Respuestas</h2>
        <?php foreach ($respuestas as $r): ?>
            <div class="bloque">
                <strong><?= htmlspecialchars($r['pregunta']) ?></strong>
                <div class="respuesta"><?= htmlspecialchars($r['respuesta']) ?></div>
                <div class="calificacion">
                <div class="calificacion-switch">
    <label for="calificacion_<?= $r['id_respuesta'] ?>">¿Respuesta correcta?</label>
    <label class="switch">
        <input type="checkbox" name="calificacion_<?= $r['id_respuesta'] ?>" value="1" <?= $r['calificacion'] == 1 ? 'checked' : '' ?>>
        <span class="slider round"></span>
    </label>
</div>

</div>

                <br>
                <label>Observación:</label>
                <textarea name="observacion_<?= $r['id_respuesta'] ?>"><?= htmlspecialchars($r['observacion'] ?? '') ?></textarea>
            </div>
        <?php endforeach; ?>
        <button type="submit">Guardar Evaluación</button>
        <button type="button" onclick="window.location.href='VER_FORMULARIOS.php'">Regresar</button>
    </form>
</body>
</html>
