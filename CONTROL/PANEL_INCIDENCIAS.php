<?php
include('../MODELO/CL_SUCURSAL.php');
include('../MODELO/CL_DEPARTAMENTO.php');
include('../VISTA/CL_INTERFAZ16.php'); 
$form_16 = new CL_INTERFAZ16();


$mensaje = "";
$tipo_mensaje = ""; 
session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH' && $_SESSION['tipo_usuario'] !== 'Colaborador') { 
    header('Location: acceso_denegado.php');
    exit();
}

if (isset($_POST['click_regresar'])) {
    if ($_SESSION['tipo_usuario'] === 'Admin') {
        header('Location: PANEL_ADMIN1.php');
    } elseif ($_SESSION['tipo_usuario'] === 'RH') {
        header('Location: PANEL_RH.php');
    } else {
        header('Location: acceso_denegado.php');
    }
    exit();
}

if (isset($_POST['click_siguiente'])) {
    $id_sucursal = $form_16->get_caja_opcion1();
    $id_departamento = $form_16->get_caja_opcion2();

    $periodo = $_POST['periodo'];

    list($fecha_inicio, $fecha_fin) = explode('to', $periodo);
    $fecha_inicio = trim($fecha_inicio);
    $fecha_fin = trim($fecha_fin);

    $sucursal = new CL_SUCURSAL();
    $sucursal->set_id_sucursal($id_sucursal);

    $departamento = new CL_DEPARTAMENTO();
    $departamento->set_id_departamento($id_departamento);

    header('Location: ../CONTROL/PANEL_DETALLE_INCIDENCIAS.php?id_sucursal=' . $id_sucursal . '&id_departamento=' . $id_departamento . '&fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin);
    exit();
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


$form_16->mostrar($selectedSucursal, $selectedDepartamento);
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
