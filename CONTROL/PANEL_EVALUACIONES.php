<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('../VISTA/CL_INTERFAZ14.php'); 

$form_14 = new CL_INTERFAZ14();

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); // O la página de inicio de sesión
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Empleado') {
    header('Location: acceso_denegado.php');
    exit();
}

if (isset($_POST['click_regresar'])) {
    header('Location: PANEL_EMPLEADO.php');
    exit();
}

if (isset($_POST['click_ver_evaluaciones'])) {
    header('Location: VER_ASIGNACIONES_EMP.php');
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

$form_14->mostrar();
?>