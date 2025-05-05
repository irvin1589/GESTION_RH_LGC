<?php
require_once '../MODELO/CL_CONEXION.php';
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

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
    </style>
</head>
<body>

<h2>Usuarios que respondieron</h2>
<ul>
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
</ul>

</body>
</html>
