<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir las clases necesarias
include_once('../VISTA/CL_INTERFAZ01.php');
include_once('../MODELO/CL_USUARIO.php'); // Incluir la clase CL_USUARIO
include_once('../MODELO/CL_TABLA_USUARIO.php'); // Incluir la clase CL_TABLA_USUARIO

// Inicializar la instancia de CL_INTERFAZ01
session_start();
$form_01 = new CL_INTERFAZ01();

$mensaje = "";
$tipo_mensaje = "";

// Verificar si se presionó el botón REGISTRAR
if (isset($_POST['click_registrar'])) {
    // Redirigir a CONTROL_REGISTRO.php
    header('Location: CONTROL_REGISTRO.php');
    exit();
}

// Verificar si se presionó el botón INICIAR SESIÓN
if (isset($_POST['click_iniciar_sesion'])) {
    $id_usuario = $form_01->get_caja_texto1();
    $contraseña = $form_01->get_caja_texto2();

    $usuario = new CL_USUARIO();
    $usuario->set_id_usuario($id_usuario);
    $usuario->set_contraseña($contraseña);

    $tabla_usuario = new CL_TABLA_USUARIO();
    
    // Verificar el usuario y obtener el tipo
    $tipo_usuario = $tabla_usuario->verificar_usuario($id_usuario, $contraseña);

    if ($tipo_usuario) {
        // Redirigir al panel correspondiente según el tipo de usuario
        $_SESSION['autenticado'] = true;
        $_SESSION['id_usuario'] = $id_usuario;
        $_SESSION['tipo_usuario'] = $tipo_usuario;
    
        switch ($tipo_usuario) {
            case 'Admin':
                header('Location: PANEL_ADMIN1.php');
                break;
            case 'RH':
                header('Location: PANEL_RH.php');
                break;
            case 'Empleado':
                header('Location: PANEL_EMPLEADO.php');
                break;
            default:
                $mensaje = "Error: Tipo de usuario desconocido.";
                $tipo_mensaje = "error";
                break;
        }
        exit();
    } else {
        $mensaje = "Error: Usuario o contraseña incorrectos.";
        $tipo_mensaje = "error";
    }
}

// Mostrar la interfaz inicial solo si no se presionó ningún botón
$form_01->mostrar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema RH</title>
    <style>
        /* Estilos para la notificación */
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
            background-color:  rgba(71, 102, 131, 0.9); /* Verde para éxito */
        }

        .notification.error {
            background-color:  rgba(71, 102, 131, 0.9); /* Rojo para error */
        }

        .notification button {
            margin-top: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color:rgb(255, 0, 0);
            color:rgb(255, 255, 255);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .notification button:hover {
            background-color:rgb(179, 11, 11);
        }
    </style>
</head>
<body>
    <!-- Notificación -->
    <div class="notification <?php echo $tipo_mensaje ?? ''; ?>" id="notification" style="display: <?php echo !empty($mensaje) ? 'block' : 'none'; ?>;">
        <p><?php echo $mensaje ?? ''; ?></p>
        <button onclick="closeNotification()">Cerrar</button>
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