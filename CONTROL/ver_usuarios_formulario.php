<?php
require_once '../MODELO/CL_CONEXION.php';
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

$idFormulario = $_GET['id_formulario'] ?? null;
if (!$idFormulario) exit("Formulario no especificado");

$stmt = $pdo->prepare("
    SELECT A.id_asignacion, U.nombre AS usuario, A.evaluado
    FROM ASIGNACION_FORMULARIO A
    JOIN USUARIO U ON A.id_usuario = U.id_usuario
    WHERE A.id_formulario = ? AND A.completado = 1
");
$stmt->execute([$idFormulario]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['click_regresar'])) {
    header('Location: ../CONTROL/VER_FORMULARIOS.php');
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios que respondieron</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fb;
            padding: 30px;
            color: #2c3e50;
        }

        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background-color: #ffffff;
            border: 1px solid #dcdfe6;
            padding: 15px 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        a {
            background-color: #27ae60;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #219150;
        }

        .evaluado {
            color: green;
            font-weight: bold;
        }
        button[name="click_regresar"] {
            padding: 10px 20px;
            background-color: #dc3545; 
            color: #fff;
            border: none;
            border-radius: 22px;
            font-size: 16px;
            cursor: pointer;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<h2>Usuarios que respondieron</h2>
<ul>
<?php if (empty($usuarios)): ?>
    <li>No hay respuestas aún.</li>
<?php else: ?>
    <?php foreach ($usuarios as $u): ?>
        <li>
            <?= htmlspecialchars($u['usuario']) ?>
            <?php if ($u['evaluado']): ?>
                <span class="evaluado">Evaluado</span>
            <?php else: ?>
                <a href="evaluar_respuestas.php?id_asignacion=<?= $u['id_asignacion'] ?>">Evaluar respuestas</a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
<?php endif; ?>
</ul>
<form method="POST" action="">
        <button type="submit" name="click_regresar"><i class="fas fa-arrow-left"></i> Regresar</button>
    </form>
</body>
</html>
