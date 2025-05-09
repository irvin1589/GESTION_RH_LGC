<?php
require_once '../MODELO/CL_CONEXION.php';
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

$idFormulario = $_GET['id_formulario'] ?? null;
if (!$idFormulario) exit("Formulario no especificado");

$stmt = $pdo->prepare("
    SELECT A.id_asignacion, U.nombre AS usuario, A.evaluado
    FROM asigancion_formulario A
    JOIN usuario U ON A.id_usuario = U.id_usuario
    WHERE A.id_formulario = ? AND A.completado = 1
");
$stmt->execute([$idFormulario]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['click_regresar'])) {
    header('Location: ../CONTROL/VER_FORMULARIOS.php');
    exit();
}
$_POST['id_formulario'] = $idFormulario;
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios que respondieron</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOM8d7j3z2l5e5c5e5e5e5e5e5e5e5e5e5e5e5" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        button{
            padding: 10px 20px;
            background-color:rgba(0, 123, 255, 0); 
            color: #fff;
            border: none;
            border-radius: 22px;
            font-size: 16px;
            cursor: pointer;
            text-transform: uppercase;
        }

        .evaluado-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

button.icono {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
}

button.icono i {
    font-size: 18px;
    color:rgb(0, 149, 255); /* verde */
    transition: color 0.3s ease;
}

button.icono i:hover {
    color:rgb(10, 88, 184); /* verde más oscuro al pasar el mouse */
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
                <div class="evaluado-container">
                    <span class="evaluado">Evaluado</span>
                    <button class="icono" type="button" onclick="window.location.href='VER_RESPUESTA.php?id_asignacion=<?= $u['id_asignacion'] ?>'">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
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
