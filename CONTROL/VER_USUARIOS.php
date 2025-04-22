<?php
include_once('../MODELO/CL_TABLA_USUARIO.php');
include_once('../MODELO/CL_TABLA_DEPARTAMENTO.php');
include_once('../MODELO/CL_TABLA_PUESTO.php');
include_once('../MODELO/CL_TABLA_SUCURSAL.php');

$tablaUsuario = new CL_TABLA_USUARIO();
$tablaDepartamento = new CL_TABLA_DEPARTAMENTO();
$tablaPuesto = new CL_TABLA_PUESTO();
$tablaSucursal = new CL_TABLA_SUCURSAL();

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin') {
    header('Location: acceso_denegado.php');
    exit();
}

if (isset($_POST['eliminar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $resultado = $tablaUsuario->eliminar_usuario($id_usuario);
    if ($resultado) {
        header("Location: VER_USUARIOS.php?msg=success");
        exit();
    } else {
        header("Location: VER_USUARIOS.php?msg=error");
        exit();
    }
}

$usuarios = $tablaUsuario->listar_todos_los_usuarios_con_detalles();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VER USUARIOS | LA GRAN CIUDAD</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
         body {
            font-family: Arial, sans-serif;
            background-image: url('../IMG/DEPARTAMENTAL.jpg');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }

        .overlay {
            background-color: rgba(31, 58, 84, 0.3);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2; /* Encima del overlay */
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .table th,
        .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #003366; /* Cambia este color a lo que desees */
            color: #ffffff;
        }

        .table tr:hover {
            background-color: rgba(31, 58, 84, 0.1);
        }


        /* Estilo de los botones de eliminar y editar como iconos */
        form button {
            padding: 0;
            margin: 0;
            border: none;
            background: none;
            cursor: pointer;
            display: inline-block; /* Hace que los botones se muestren en línea */
        }

        
        .accion-botones {
            display: flex;
            justify-content: center; 
            gap: 10px; 
        }

        
        button[name="eliminar_usuario"] i {
            color: #dc3545; 
            font-size: 20px;
            transition: color 0.3s ease;
        }

        button[name="eliminar_usuario"]:hover i {
            color: #c82333; 
        }

        
        button[name="editar_usuario"] i {
            color: #007bff; /* Azul */
            font-size: 20px;
            transition: color 0.3s ease;
        }

        button[name="editar_usuario"]:hover i {
            color: #0056b3; 
        }

       
        button[name="regresar"] {
            padding: 10px 20px;
            background-color: #dc3545; 
            color: #fff;
            border: none;
            border-radius: 22px;
            font-size: 16px;
            cursor: pointer;
            text-transform: uppercase;
        }

        button[name="regresar"]:hover {
            background-color: #c82333; /* Rojo más oscuro */
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        .notification {
            width: 80%;
            margin: 20px auto;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
    .col-departamento,
    .col-puesto,
    .col-nombre,
    .col-apellido1,
    .col-apellido2,
    .col-contra,
    .col-tipo {
        display: none;
    }
}
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="content">
        <h2>Lista de Usuarios</h2>
    
    <?php
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] == 'success') {
            echo '<div class="notification success">Usuario eliminado correctamente.</div>';
        } elseif ($_GET['msg'] == 'error') {
            echo '<div class="notification error">Error al eliminar el usuario. Inténtalo de nuevo.</div>';
        }
    }
    ?>

    <table>
    <thead>
    <tr>
        <th class="col-sucursal">Sucursal</th>
        <th class="col-departamento">Departamento</th>
        <th class="col-puesto">Puesto</th>
        <th class="col-id">ID Usuario</th>
        <th class="col-nombre">Nombre</th>
        <th class="col-apellido1">Apellido Paterno</th>
        <th class="col-apellido2">Apellido Materno</th>
        <th class="col-contra">Contraseña</th>
        <th class="col-tipo">Tipo de Usuario</th>
        <th class="col-acciones">Acciones</th>
    </tr>
</thead>
<tbody>
<?php foreach ($usuarios as $usuario): ?>
    <tr>
        <td class="col-sucursal"><?= htmlspecialchars($usuario['nombre_sucursal']) ?></td>
        <td class="col-departamento"><?= htmlspecialchars($usuario['nombre_departamento']) ?></td>
        <td class="col-puesto"><?= htmlspecialchars($usuario['nombre_puesto']) ?></td>
        <td class="col-id"><?= htmlspecialchars($usuario['id_usuario']) ?></td>
        <td class="col-nombre"><?= htmlspecialchars($usuario['nombre']) ?></td>
        <td class="col-apellido1"><?= htmlspecialchars($usuario['apellido1']) ?></td>
        <td class="col-apellido2"><?= htmlspecialchars($usuario['apellido2']) ?></td>
        <td class="col-contra"><?= htmlspecialchars($usuario['contraseña']) ?></td>
        <td class="col-tipo"><?= htmlspecialchars($usuario['tipo_usuario']) ?></td>
        <td class="col-acciones">
            <div class="accion-botones">
                <form method="GET" action="EDITAR_USUARIO.php">
                    <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
                    <button type="submit" name="editar_usuario"><i class="fas fa-edit"></i></button>
                </form>
                <form method="POST" action="VER_USUARIOS.php">
                    <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
                    <button type="submit" name="eliminar_usuario"><i class="fas fa-trash-alt"></i></button>
                </form>
            </div>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>

    </table>

    <!-- Botón para regresar -->
    <form method="POST" action="../CONTROL/PANEL_ADMIN.php">
        <button type="submit" name="regresar">REGRESAR</button>
    </form>
    </div>
</body>
</html>
