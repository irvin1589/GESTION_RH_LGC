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
    header('Location: SISTEMA_RH.php');
    exit();
}

// Añadir estilos responsive
echo '
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
    }
    
    .contenedor {
        width: 95%;
        max-width: 1200px;
        margin: 20px auto;
        padding: 15px;
    }
    
    h2 {
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    
    th {
        background-color: #f8f8f8;
        font-weight: bold;
        color: #333;
    }
    
    tr:hover {
        background-color: #f5f5f5;
    }
    
    .btn-completar, .btn-ver {
        display: inline-block;
        padding: 8px 15px;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        text-align: center;
        font-size: 14px;
        transition: background-color 0.3s;
    }
    
    .btn-completar {
        background-color: #4CAF50;
    }
    
    .btn-ver {
        background-color: #2196F3;
    }
    
    .btn-completar:hover {
        background-color: #3e8e41;
    }
    
    .btn-ver:hover {
        background-color: #0b7dda;
    }
    
    /* Estilos responsive */
    @media screen and (max-width: 768px) {
        table {
            border: 0;
            box-shadow: none;
        }
        
        table thead {
            display: none; /* Ocultar encabezados en pantallas pequeñas */
        }
        
        table tr {
            margin-bottom: 20px;
            display: block;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        table td {
            display: block;
            text-align: right;
            font-size: 14px;
            border-bottom: 1px dotted #ccc;
            position: relative;
            padding-left: 50%;
        }
        
        table td:last-child {
            border-bottom: 0;
        }
        
        table td:before {
            content: attr(data-label);
            position: absolute;
            left: 12px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            text-align: left;
            font-weight: bold;
        }
        
        .btn-completar, .btn-ver {
            display: block;
            width: 100%;
            margin: 5px 0;
        }
    }
    
    @media screen and (max-width: 480px) {
        .contenedor {
            width: 100%;
            padding: 10px;
        }
        
        h2 {
            font-size: 20px;
        }
    }
</style>
';

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
    echo "<table>";
    echo "<thead>
            <tr>
                <th>Formulario</th>
                <th>Descripción</th>
                <th>Fecha Asignación</th>
                <th>Fecha Límite</th>
                <th>Completado</th>
                <th>Acciones</th>
            </tr>
          </thead>";
    echo "<tbody>";
    foreach ($asignaciones as $asig) {
        echo "<tr>";
        echo "<td data-label='Formulario'>{$asig['nombre_formulario']}</td>";
        echo "<td data-label='Descripción'>{$asig['descripcion']}</td>";
        echo "<td data-label='Fecha Asignación'>{$asig['fecha_asignacion']}</td>";
        echo "<td data-label='Fecha Límite'>{$asig['fecha_limite']}</td>";
        echo "<td data-label='Completado'>" . ($asig['completado'] ? 'Sí' : 'No') . "</td>";
        
        $id_asignacion = $asig['id_asignacion'];
        $_SESSION['id_asignacion'] = $id_asignacion;
        
        if ($asig['completado']) {
            echo "<td data-label='Acciones'><a href='VER_FORM.php?id_asignacion={$id_asignacion}' class='btn-ver'>Ver Respuestas</a></td>";
        } else {
            echo "<td data-label='Acciones'><a href='RESPONDER_FORM.php?id_asignacion={$id_asignacion}' class='btn-completar'>Completar</a></td>";
        }
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<div class='mensaje-vacio'>
            <p>No tienes formularios asignados.</p>
          </div>";
}
echo "</main>";
?>