<?php
require_once '../MODELO/CL_CONEXION.php';
require_once '../MODELO/CL_TABLA_FORMULARIO.php';

session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if (!isset($_GET['id_asignacion'])) {
    echo "Formulario no especificado.";
    exit;
}


$idAsignacion = $_GET['id_asignacion'];
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

// Obtener el id_formulario desde la tabla ASIGNACION_FORMULARIO
$stmt = $pdo->prepare("SELECT id_formulario FROM ASIGNACION_FORMULARIO WHERE id_asignacion = ?");
$stmt->execute([$idAsignacion]);
$formulario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$formulario) {
    echo "Asignación no encontrada.";
    exit;
}

$idFormulario = $formulario['id_formulario'];

$t_formulario = new CL_TABLA_FORMULARIO();
$formulario = $t_formulario->obtener_formulario_por_id($idFormulario);
echo "<h2> FORMULARIO ". $formulario['nombre'] . " </h2>";

// Obtener preguntas del formulario
$stmt = $pdo->prepare("SELECT * FROM PREGUNTA WHERE id_formulario = ? ORDER BY orden");
$stmt->execute([$idFormulario]);
$preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FORMULARIO</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        form {
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        div {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #444;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            
        }

        input[type="radio"] {
            margin: 10px;
            accent-color: #007BFF;
        }

        .pregunta{
            display: flex;
            align-items: center;
            
        }

        button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .button-regresar {
            background-color: #dc3545;
        }

        .button-regresar:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<form action="guardar_respuestas.php" method="post">
    <input type="hidden" name="id_asignacion" value="<?= $idAsignacion ?>">
    <?php foreach ($preguntas as $pregunta): ?>
        <div>
            <label><?= htmlspecialchars($pregunta['texto']) ?></label>

            <?php
            $stmtOp = $pdo->prepare("SELECT * FROM OPCION_PREGUNTA WHERE id_pregunta = ?");
            $stmtOp->execute([$pregunta['id_pregunta']]);
            $opciones = $stmtOp->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if ($opciones): ?>
                <?php foreach ($opciones as $opcion): ?>
                    <div class="pregunta">
                        <input type="radio" name="respuesta_<?= $pregunta['id_pregunta'] ?>" 
                               value="<?= htmlspecialchars($opcion['valor_opcion']) ?>" 
                               id="opcion_<?= $opcion['id_opcion'] ?>">
                        <label for="opcion_<?= $opcion['id_opcion'] ?>">
                            <?= htmlspecialchars($opcion['texto_opcion']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <input type="text" name="respuesta_<?= $pregunta['id_pregunta'] ?>">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH'): ?>
        <button type="submit">Enviar respuestas</button>
<?php else: ?>
    <button type="button" class="button-regresar" onclick="window.location.href='ASIGNAR_FORMULARIO.php'">Regresar</button>
<?php endif; ?>
</form>

</body>
</html>
