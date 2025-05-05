<?php
require_once '../MODELO/CL_CONEXION.php';
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

$stmt = $pdo->prepare("
    SELECT A.id_asignacion, F.id_formulario, F.nombre AS nombre_formulario
    FROM ASIGNACION_FORMULARIO A
    JOIN FORMULARIO F ON A.id_formulario = F.id_formulario
    GROUP BY F.id_formulario
");
$stmt->execute();
$formularios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formularios Disponibles</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            color: #333;
            padding: 30px;
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
            background-color: #3498db;
            color: #fff;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<h2>Formularios Disponibles</h2>
<ul>
<?php foreach ($formularios as $f): ?>
    <li>
        <?= htmlspecialchars($f['nombre_formulario']) ?>
        <a href="ver_usuarios_formulario.php?id_formulario=<?= $f['id_formulario'] ?>">Ver usuarios</a>
    </li>
<?php endforeach; ?>
</ul>

</body>
</html>
