<?php
include_once('../MODELO/CL_TABLA_ASIGNACION_FORMULARIO.php');
include_once('../MODELO/CL_TABLA_FORMULARIO.php');
include_once('../MODELO/CL_TABLA_USUARIO.php');

$tablaAsignacion = new CL_TABLA_ASIGNACION_FORMULARIO();

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); // O la página de inicio de sesión
    exit();
}

// Opcional: puedes validar el tipo de usuario también
if ($_SESSION['tipo_usuario'] !== 'Admin') {
    header('Location: acceso_denegado.php');
    exit();
}

// Manejar la eliminación de una asignación
if (isset($_POST['eliminar_asignacion'])) {
    $id_asignacion = $_POST['id_asignacion'];
    $resultado = $tablaAsignacion->eliminar_asignacion($id_asignacion);

    if ($resultado) {
        echo '<div class="notification success">Asignación eliminada exitosamente.</div>';
    } else {
        echo '<div class="notification error">Error al eliminar la asignación. Por favor, inténtelo de nuevo.</div>';
    }
}

// Obtener la lista de asignaciones
$asignaciones = $tablaAsignacion->listar_asignaciones();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VER ASIGNACIONES | LA GRAN CIUDAD</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Botón de eliminar */
        button[name="eliminar_asignacion"] {
            padding: 0;
            margin: 0;
            border: none;
            background: none;
            cursor: pointer;
        }

        button[name="eliminar_asignacion"] i {
            color: #dc3545; /* Rojo */
            font-size: 18px;
        }

        button[name="eliminar_asignacion"] i:hover {
            color: #c82333; /* Rojo más oscuro al pasar el cursor */
        }

        /* Botón de regresar */
        button[name="regresar"] {
            padding: 10px 20px;
            background-color: #dc3545; /* Rojo */
            color: #fff;
            border: none;
            border-radius: 5px;
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
    </style>
</head>
<body>
    <h2>Lista de Asignaciones</h2>
    <table>
        <thead>
            <tr>
                <th>ID Asignación</th>
                <th>Formulario</th>
                <th>Usuario</th>
                <th>Fecha de Asignación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($asignaciones as $asignacion): ?>
                <tr>
                    <td><?= htmlspecialchars($asignacion['id_asignacion']) ?></td>
                    <td><?= htmlspecialchars($asignacion['nombre_formulario']) ?></td>
                    <td><?= htmlspecialchars($asignacion['nombre_usuario']) ?></td>
                    <td><?= htmlspecialchars($asignacion['fecha_asignacion']) ?></td>
                    <td><?= $asignacion['completado'] ? 'Completado' : 'Pendiente' ?></td>
                    <td>
                        <form method="POST" action="VER_ASIGNACIONES.php">
                            <input type="hidden" name="id_asignacion" value="<?= htmlspecialchars($asignacion['id_asignacion']) ?>">
                            <button type="submit" name="eliminar_asignacion">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Botón para regresar -->
    <form method="POST" action="../HTML/form_08.php">
        <button type="submit" name="regresar">REGRESAR</button>
    </form>
</body>
</html>