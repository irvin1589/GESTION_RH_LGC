<?php
include('../MODELO/CL_SUCURSAL.php');
include('../VISTA/CL_INTERFAZ02.php');
include_once('../VISTA/CL_INTERFAZ05.php'); // Ya está incluida la clase
include_once('../MODELO/CL_TABLA_SUCURSAL.php');

// Variable para almacenar el mensaje de notificación
$mensaje = "";
$tipo_mensaje = ""; // success o error

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
if (isset($_POST['click_regresar'])) {
    header('Location: ../CONTROL/PANEL_ADMIN.php');
    exit();
}

if (isset($_POST['click_registrar_sucursal'])) {
    // Obtener los valores del formulario
    $id_sucursal = $_POST['caja_texto1'];
    $nombre = $_POST['caja_texto2'];
    $direccion = $_POST['caja_texto3'];
    $telefono = $_POST['caja_texto4'];

    // Crear objeto de sucursal
    $sucursal = new CL_SUCURSAL();
    $sucursal->set_id_sucursal($id_sucursal);
    $sucursal->set_nombre($nombre);
    $sucursal->set_direccion($direccion);
    $sucursal->set_telefono($telefono);

    // Crear instancia de la tabla sucursal
    $tablaSucursal = new CL_TABLA_SUCURSAL();

    // Verificar si el ID de la sucursal ya existe
    if ($tablaSucursal->existe_sucursal($id_sucursal)) {
        $mensaje = "Error: La sucursal ya existe. Por favor, elige otro ID.";
        $tipo_mensaje = "error";
    } else {
        // Guardar la sucursal en la base de datos
        if ($tablaSucursal->guardar_sucursal($sucursal)) {
            $mensaje = "Sucursal registrada exitosamente.";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al registrar la sucursal.";
            $tipo_mensaje = "error";
        }
    }
}

$form_05 = new CL_INTERFAZ05();
$form_05->mostrar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('../IMG/fondo_1.jpg'); /* Imagen de fondo */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .notification {
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(31, 58, 84, 0.9); /* Fondo blanco semitransparente */
            padding: 50px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            display: none; /* Oculto por defecto */
            z-index: 1000;
        }

        .notification.success {
            color: white;
        }

        .notification.error {
            color: white;
        }

        .notification button {
            margin-top: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color:rgb(248, 36, 36);
            color: white;
            cursor: pointer;
        }

        .notification button:hover {
            background-color:rgb(255, 0, 0);
        }
    </style>
</head>
<body>
    <?php if (!empty($mensaje)): ?>
        <div class="notification <?php echo $tipo_mensaje; ?>" id="notification">
            <p><?php echo $mensaje; ?></p>
            <button onclick="closeNotification()">Cerrar</button>
        </div>
    <?php endif; ?>

    <script>
        // Mostrar la notificación si existe
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.display = 'block';
        }

        // Función para cerrar la notificación
        function closeNotification() {
            if (notification) {
                notification.style.display = 'none';
            }
        }
    </script>
</body>
</html>