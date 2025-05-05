<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once('../VISTA/CL_INTERFAZ14.php'); 
include('../MODELO/CL_TABLA_ASIGNACION_FORMULARIO.php');
include('../MODELO/CL_TABLA_FORMULARIO.php');

$form_14 = new CL_INTERFAZ14();

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
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
    session_unset();
    session_destroy();
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/SISTEMA_RH.php');
    exit();
}

// Mostrar la interfaz
$form_14->mostrar();

// Obtener asignaciones del usuario
$t_asignacion = new CL_TABLA_ASIGNACION_FORMULARIO();
$id_usuario = $_SESSION['id_usuario'];

$asignaciones = $t_asignacion->get_asignaciones_user($id_usuario);

// Mostrar las asignaciones
echo "<main class='contenedor'>";
echo "<h2>Formularios Asignados</h2>";
if ($asignaciones && count($asignaciones) > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr>
            
            <th>Formulario</th>
            <th>Descripción</th>
            <th>Fecha Asignación</th>
            <th>Fecha Límite</th>
            <th>Completado</th>
            <th>Acciones</th>
          </tr>";
    foreach ($asignaciones as $asig) {
        echo "<tr>";
        // echo "<td>{$asig['id_asignacion']}</td>";
        echo "<td>{$asig['nombre_formulario']}</td>";
        echo "<td>{$asig['descripcion']}</td>";
        echo "<td>{$asig['fecha_asignacion']}</td>";
        echo "<td>{$asig['fecha_limite']}</td>";
        echo "<td>" . ($asig['completado'] ? 'Sí' : 'No') . "</td>";

        $id_asignacion = $asig['id_asignacion'];
        $_SESSION['id_asignacion'] = $id_asignacion;

        if ($asig['completado']) {
            echo "<td><a href='VER_FORM.php?id_asignacion={$id_asignacion}' class='btn-ver'>Ver Respuestas</a></td>";
        } else {
            echo "<td><a href='RESPONDER_FORM.php?id_asignacion={$id_asignacion}' class='btn-completar'>Completar</a></td>";
        }
        // echo "<td><button class='btn-responder' data-id='{$asig['id_asignacion']}'>Responder</button></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</main>";
} else {
    echo "<p>No tienes formularios asignados.</p>";
}
?>
