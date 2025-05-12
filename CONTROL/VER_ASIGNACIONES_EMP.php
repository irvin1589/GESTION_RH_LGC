<?php
include_once('../MODELO/CL_TABLA_ASIGNACION_FORMULARIO.php');
include_once('../MODELO/CL_TABLA_FORMULARIO.php');
include_once('../MODELO/CL_TABLA_USUARIO.php');

$tablaAsignacion = new CL_TABLA_ASIGNACION_FORMULARIO();

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); 
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Colaborador') {
    header('Location: acceso_denegado.php');
    exit();
}
