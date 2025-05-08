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

session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

if (isset($_POST['click_regresar'])) {
    if ($_SESSION['tipo_usuario'] === 'Admin') {
        header('Location: ../CONTROL/PANEL_ADMIN.php');
    } elseif ($_SESSION['tipo_usuario'] === 'RH') {
        header('Location: ../CONTROL/PANEL_DESARROLLO.php');
    } else {
        header('Location: acceso_denegado.php');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formularios Disponibles</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

<h2>Formularios Disponibles</h2>
<ul>
<?php foreach ($formularios as $f): ?>
    <li>
        <?= htmlspecialchars($f['nombre_formulario']) ?>
        <a href="ver_usuarios_formulario1.php?id_formulario=<?= $f['id_formulario'] ?>">Ver usuarios</a>
    </li>
<?php endforeach; ?>
</ul>
<form method="POST" action="">
        <button type="submit" name="click_regresar"><i class="fas fa-arrow-left"></i> Regresar</button>
    </form>
</body>
</html>
