<?php
include('../MODELO/CL_SUCURSAL.php');
include('../MODELO/CL_DEPARTAMENTO.php');
include('../MODELO/CL_TABLA_PUESTO.php');
include('../VISTA/CL_INTERFAZ07.php'); 
$form_07 = new CL_INTERFAZ07();


$mensaje = "";
$tipo_mensaje = ""; 
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

if (isset($_POST['click_registrar_puesto'])) {
  
    $id_puesto = $form_07->get_caja_texto1();
    $nombre = $form_07->get_caja_texto2();
    $id_sucursal = $form_07->get_caja_opcion1();
    $id_departamento = $form_07->get_caja_opcion2();

   
    $puesto = new CL_TABLA_PUESTO();

    
    if ($puesto->existe_puesto($id_puesto)) {
        $mensaje = "Error: El puesto ya existe. Por favor, elige otro ID.";
        $tipo_mensaje = "error";
    } else {
     
        if ($puesto->crear_puesto($id_puesto, $nombre, $id_departamento, $id_sucursal)) {
            $mensaje = "Puesto registrado exitosamente.";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al registrar el puesto.";
            $tipo_mensaje = "error";
        }
    }
}

$selectedSucursal = $_POST['caja_opcion1'] ?? '';
$selectedDepartamento = $_POST['caja_opcion2'] ?? '';


if ($selectedSucursal && !$selectedDepartamento) {
    $tablaDepartamento = new CL_TABLA_DEPARTAMENTO();
    $departamentosHtml = $tablaDepartamento->listar_departamentos($selectedSucursal);
    if (strpos($departamentosHtml, 'selected') !== false) {
        preg_match('/value=\'(\d+)\' selected/', $departamentosHtml, $matches);
        $selectedDepartamento = $matches[1] ?? '';
    }
}


$form_07->mostrar($selectedSucursal, $selectedDepartamento);
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
            background-image: url('../IMG/puesto.jpg'); /* Imagen de fondo */
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
            background-color: rgb(248, 36, 36);
            color: white;
            cursor: pointer;
        }

        .notification button:hover {
            background-color: rgb(255, 0, 0);
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
