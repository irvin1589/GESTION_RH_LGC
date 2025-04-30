<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('../VISTA/CL_INTERFAZ13.php'); 

$form_13 = new CL_INTERFAZ13();

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); // O la página de inicio de sesión
    exit();
}

// Opcional: puedes validar el tipo de usuario también
if ($_SESSION['tipo_usuario'] !== 'Empleado') {
    header('Location: acceso_denegado.php');
    exit();
}

if (isset($_POST['click_evaluaciones'])) {
    header('Location: PANEL_EVALUACIONES.php');
    exit();
}

if (isset($_POST['click_panel_comunicacion'])) {
    header('Location: PANEL_COMUNICACION.php');
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

if (isset($_POST['click_panel_incidencias'])) {
    // Redirigir a la página que solicita la contraseña
    header('Location: solicitar_contraseña.php');
    exit();
}

$form_13->mostrar();
?>
