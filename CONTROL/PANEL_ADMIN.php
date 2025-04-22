<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir las clases necesarias
include_once('../VISTA/CL_INTERFAZ02.php'); 
include_once('../MODELO/CL_TABLA_SUCURSAL.php');

$tablaSucursal = new CL_TABLA_SUCURSAL();
$sucursales = $tablaSucursal->listar_todo_sucursales(); // Obtener la lista de sucursales

$mensaje = "";
$tipo_mensaje = "";

if (isset($_GET['mensaje'])) {
    if ($_GET['mensaje'] === 'success') {
        $mensaje = "Sucursal eliminada correctamente.";
        $tipo_mensaje = "success";
    } elseif ($_GET['mensaje'] === 'error') {
        $mensaje = "Error al eliminar la sucursal.";
        $tipo_mensaje = "error";
    }
}

if (isset($_POST['click_crear_formulario'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/CREAR_FORMULARIO.php');
    exit();
}

if (isset($_POST['click_crear_sucursal'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/CREAR_SUCURSAL.php');
    exit();
}

if (isset($_POST['click_crear_departamento'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/CREAR_DEPARTAMENTO.php');
    exit();
}

if (isset($_POST['click_crear_puesto'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/CREAR_PUESTO.php');
    exit();
}

if (isset($_POST['click_asignar_formulario'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/ASIGNAR_FORMULARIO.php');
    exit();
}

if (isset($_POST['click_evaluar'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/EVALUAR_FORMULARIO.php');
    exit();
}

if (isset($_POST['click_resultados'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/RESULTADOS.php');
    exit();
}

if (isset($_POST['click_cerrar_sesion'])) {
    session_start();
    session_unset();
    session_destroy();
    echo "Redirigiendo...";
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/SISTEMA_RH.php');
    exit();
}

if (isset($_POST['click_eliminar_sucursal'])) {
    $id_sucursal = $_POST['id_sucursal'] ?? '';

    if (!empty($id_sucursal)) {
        $resultado = $tablaSucursal->eliminar_sucursal($id_sucursal);
        if ($resultado) {
            header("Location: PANEL_ADMIN.php?mensaje=success");
            exit();
        } else {
            header("Location: PANEL_ADMIN.php?mensaje=error");
            exit();
        }
    }
}

if (isset($_POST['click_editar_sucursal'])) {
    $id_sucursal = $_POST['id_sucursal'] ?? '';

    if (!empty($id_sucursal)) {
        header("Location: http://localhost/GESTION_RH_LGC/CONTROL/EDITAR_SUCURSAL.php?id_sucursal=$id_sucursal");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PANEL ADMINISTRADOR | LA GRAN CIUDAD</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <!-- Enlace a Font Awesome para los íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-image: url('../IMG/DEPARTAMENTAL.jpg'); /* Imagen de fondo */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: rgba(31, 58, 84, 0.9); /* Fondo azul con transparencia */
            color: #ffffff;
            padding: 20px 30px;
            text-align: center;
        }

        .container {
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.5); /* Fondo blanco semitransparente */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 1200px;
        }

        .button-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* Cuatro columnas */
            gap: 20px; /* Espacio entre los botones */
            margin-top: 20px;
        }

        button {
            background-color: rgba(31, 58, 84, 0.6);
            color: #ffffff;
            border: 1px solid #003f7f;
            border-radius: 25px;
            padding: 15px 20px; /* Aumenta el tamaño de los botones */
            font-size: 18px; /* Aumenta el tamaño del texto de los botones */
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:active {
            background-color: #003f7f;
        }

        .logout-button {
            background-color: #dc3545;
            border: 1px solid #dc3545;
            font-size: 16px; /* Aumenta el tamaño del texto del botón de cerrar sesión */
            padding: 10px 20px; /* Aumenta el tamaño del botón de cerrar sesión */
            margin-top: 30px;
            width: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .logout-button:hover {
            background-color: #a71d2a;
        }

        .logout-button:active {
            background-color: #7f1420;
        }

        .sucursales-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .sucursales-table th,
        .sucursales-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .sucursales-table th {
            background-color: rgba(31, 58, 84, 0.9);
            color: white;
        }

        .sucursales-table tr:hover {
            background-color: rgba(31, 58, 84, 0.1);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            background: none; /* Sin fondo */
            border: none; /* Sin bordes */
            cursor: pointer;
            padding: 0; /* Sin relleno */
            transition: transform 0.2s ease; /* Efecto al pasar el mouse */
        }

        .action-buttons button:hover {
            transform: scale(1.2); /* Aumenta el tamaño al pasar el mouse */
        }

        .action-buttons .edit i {
            color:  #007bff; /* Verde para el ícono de editar */
            font-size: 18px; /* Tamaño del ícono */
        }

        .action-buttons .delete i {
            color: #f44336; /* Rojo para el ícono de eliminar */
            font-size: 18px; /* Tamaño del ícono */
        }

        .action-buttons .edit i:hover {
            color:  #007bff; /* Verde más claro al pasar el mouse */
        }

        .action-buttons .delete i:hover {
            color: #e53935; /* Rojo más claro al pasar el mouse */
        }

        @media (max-width: 768px) {
        /* Ocultar columnas Dirección, Teléfono y Acciones en pantallas pequeñas */
        .sucursales-table th:nth-child(3),
        .sucursales-table th:nth-child(4),
        .sucursales-table td:nth-child(3),
        .sucursales-table td:nth-child(4) {
            display: none;
        }
    }

    .notification {
        position: fixed;
        top: 20%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(31, 58, 84, 0.9); /* Fondo azul con transparencia */
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-align: center;
        z-index: 1000;
        color: white;
        display: none; /* Oculto por defecto */
    }

    .notification.success {
        background-color: rgba(31, 58, 84, 0.9); /* Verde para éxito */
    }

    .notification.error {
        background-color: rgba(31, 58, 84, 0.9); /* Rojo para error */
    }

    .notification button {
        margin-top: 10px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #ffffff;
        color: #333333;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .notification button:hover {
        background-color: #dddddd;
    }
    </style>
</head>
<body>
    <div class="header">
        <h1>Panel de Administración</h1>
    </div>

    <div class="container">
        <!-- Botones de acciones -->
        <form method="POST" action="">
            <div class="button-container">
                <button type="submit" name="click_crear_formulario">Crear Formulario</button>
                <button type="submit" name="click_crear_sucursal">Crear Sucursal</button>
                <button type="submit" name="click_crear_departamento">Crear Departamento</button>
                <button type="submit" name="click_crear_puesto">Crear Puesto</button>
                <button type="submit" name="click_asignar_formulario">Asignar Formulario</button>
                <button type="submit" name="click_evaluar">Evaluar Formulario</button>
                <button type="submit" name="click_resultados">Resultados</button>
            </div>
            <button type="submit" name="click_cerrar_sesion" class="logout-button">Cerrar Sesión</button>
        </form>

        <!-- Notificación -->
        <div class="notification <?php echo $tipo_mensaje ?? ''; ?>" id="notification" style="display: <?php echo !empty($mensaje) ? 'block' : 'none'; ?>;">
            <p><?php echo $mensaje ?? ''; ?></p>
            <button onclick="closeNotification()">Cerrar</button>
        </div>

        <!-- Tabla de sucursales -->
        <h2>Lista de Sucursales</h2>
        <table class="sucursales-table">
            <thead>
                <tr>
                    <th>ID Sucursal</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($sucursales)) {
                    foreach ($sucursales as $sucursal) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($sucursal['id_sucursal']) . "</td>";
                        echo "<td>" . htmlspecialchars($sucursal['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($sucursal['direccion']) . "</td>";
                        echo "<td>" . htmlspecialchars($sucursal['telefono']) . "</td>";
                        echo "<td class='action-buttons'>";
                        echo "<form method='POST' action='' style='display:inline;'>";
                        echo "<input type='hidden' name='id_sucursal' value='" . htmlspecialchars($sucursal['id_sucursal']) . "'>";
                        echo "<button type='submit' name='click_editar_sucursal' class='edit'><i class='fas fa-edit'></i></button>";
                        echo "</form>";
                        echo "<form method='POST' action='' style='display:inline;'>";
                        echo "<input type='hidden' name='id_sucursal' value='" . htmlspecialchars($sucursal['id_sucursal']) . "'>";
                        echo "<button type='submit' name='click_eliminar_sucursal' class='delete'><i class='fas fa-trash'></i></button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay sucursales registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        // Función para cerrar la notificación
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }
    </script>
</body>
</html>