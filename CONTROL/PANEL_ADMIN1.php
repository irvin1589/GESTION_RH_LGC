<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('../VISTA/CL_INTERFAZ09.php'); 

$form_09 = new CL_INTERFAZ09();

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
if (isset($_POST['click_panel_incidencias'])) {
    header('Location: PANEL_INCIDENCIAS.php');
    exit();
}

if (isset($_POST['click_panel_admin'])) {
    header('Location: PANEL_ADMIN.php');
    exit();
}
if (isset($_POST['click_panel_comunicacion'])) {
    header('Location: PANEL_COMUNICACION_ADMIN.php');
    exit();
}

if (isset($_POST['click_cerrar_sesion'])) {
    session_start();
    session_unset();
    session_destroy();
    echo "Redirigiendo...";
    header('Location: SISTEMA_RH.php');
    exit();
}

$form_09->mostrar();
?>