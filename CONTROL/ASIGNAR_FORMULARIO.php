<?php
session_start();
ob_start();
include_once('../MODELO/CL_TABLA_DEPARTAMENTO.php');
include_once('../MODELO/CL_TABLA_PUESTO.php');
include_once('../MODELO/CL_TABLA_SUCURSAL.php');
include_once('../VISTA/CL_INTERFAZ08.php');
include_once('../MODELO/CL_TABLA_FORMULARIO.php');
include_once('../MODELO/CL_TABLA_USUARIO.php');
include_once('../MODELO/CL_TABLA_ASIGNACION_FORMULARIO.php');


$form_08 = new CL_INTERFAZ08();
$form_08->mostrar();


if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); 
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

if (isset($_POST['seleccionar_formulario'])) {
    $id_formulario = $_POST['id_formulario'];

    $tablaFormulario = new CL_TABLA_FORMULARIO();
    $formulario = $tablaFormulario->obtener_formulario_por_id($id_formulario);

    if (!$formulario) {
        echo '<div class="notification error">Error: No se pudo obtener los detalles del formulario.</div>';
        exit();
    }
   
    echo '
    <style>
    h2 {
        text-align: center;
        color: #333;
    }
    p {
        text-align: center;
        color: #555;
    }
    </style>
    ';

    echo '<h2>Asignar Formulario: ' . htmlspecialchars($formulario['nombre']) . '</h2>';
    echo '<p>Descripción: ' . htmlspecialchars($formulario['descripcion']) . '</p>';
    echo '<p>Fecha Límite: ' . htmlspecialchars($formulario['fecha_limite']) . '</p>';

   
    $id_departamento = $formulario['id_departamento'];
    $id_sucursal = $formulario['id_sucursal'];
    $id_puesto = $formulario['id_puesto'];

    $tablaUsuario = new CL_TABLA_USUARIO();
    $usuarios = $tablaUsuario->listar_usuarios_por_filtros($id_departamento, $id_sucursal, $id_puesto);

   
    echo '<form method="POST" action="ASIGNAR_FORMULARIO.php">';
    echo '<input type="hidden" name="id_formulario" value="' . htmlspecialchars($id_formulario) . '">';

    echo '
    <style>
    label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }
        select {
        width: 20%;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
        button {
        padding: 10px 20px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
        button:hover {
        background-color: #0056b3;
    }

        button[name="click_regresar_1"] {
        background-color:rgb(232, 27, 27);
        margin-left: 10px;
    }
        button[name="click_regresar_1"]:hover {
        background-color:rgb(176, 46, 46);
    }
        </style>';

    echo '<label for="usuario">Usuario:</label>';
    echo '<select id="usuario" name="id_usuario">';
    echo '<option value="">Seleccione un usuario</option>';
    foreach ($usuarios as $usuario) {
        echo '<option value="' . htmlspecialchars($usuario['id_usuario']) . '">' . htmlspecialchars($usuario['nombre']) . '</option>';
    }
    echo '</select>';

    echo '<button type="submit" name="asignar_formulario">Asignar</button>';
    echo '<button type="submit" name="click_regresar_1">Regresar</button>';
    echo '</form>';
}

if (isset($_POST['click_regresar_1'])) {
    if ($_SESSION['tipo_usuario'] === 'Admin') {
        header('Location: ../CONTROL/ASIGNAR_FORMULARIO.php');
    } elseif ($_SESSION['tipo_usuario'] === 'RH') {
        header('Location: ../CONTROL/ASIGNAR_FORMULARIO.php');
    } else {
        header('Location: acceso_denegado.php'); 
    }
    exit();
}
if (isset($_POST['asignar_formulario'])) {
    $id_formulario = $_POST['id_formulario'];
    $id_usuario = $_POST['id_usuario'];

    $tablaAsignacion = new CL_TABLA_ASIGNACION_FORMULARIO();
    $resultado = $tablaAsignacion->crear_asignacion($id_formulario, $id_usuario);

    if ($resultado) {
        echo '<div class="notification success">Formulario asignado exitosamente al usuario.</div>';
    } else {
        echo '<div class="notification error">Error al asignar el formulario. Por favor, inténtelo de nuevo.</div>';
    }
}

if (isset($_POST['click_regresar'])) {
    if ($_SESSION['tipo_usuario'] === 'Admin') {
        header('Location: ../CONTROL/PANEL_ADMIN.php');
    } elseif ($_SESSION['tipo_usuario'] === 'RH') {
        header('Location: ../CONTROL/PANEL_DESARROLLO.php');
    } else {
        header('Location: acceso_denegado.php'); // Opcional: para otros tipos
    }
    exit();
}

if (isset($_POST['ver_formulario'])) {
    $id_formulario = $_POST['id_formulario'];

    $tablaAsignacion = new CL_TABLA_ASIGNACION_FORMULARIO();
    $asignaciones = $tablaAsignacion->listar_asignaciones_por_formulario($id_formulario);

    if (!empty($asignaciones)) {
        $primeraAsignacion = $asignaciones[0];
        $id_asignacion = $primeraAsignacion['id_asignacion'];

        header("Location: http://localhost/GESTION_RH_LGC/CONTROL/RESPONDER_FORM.php?id_asignacion=" . $id_asignacion);
        exit();
    } else {
        echo "No hay una vista previa.";
    }
}

if (isset($_POST['eliminar_formulario'])) {
    $id_formulario = $_POST['id_formulario'];
    $conexion = new mysqli("localhost", "root", "", "gestion_rh_lgc");

    if ($conexion->connect_error) {
        die('<div class="notification error">Error de conexión: ' . $conexion->connect_error . '</div>');
    }

    $exito = true;

    $asignaciones = $conexion->query("SELECT id_asignacion FROM asignacion_formulario WHERE id_formulario = '$id_formulario'");
    if ($asignaciones) {
        while ($row = $asignaciones->fetch_assoc()) {
            $id_asignacion = $row['id_asignacion'];
            if (
                !$conexion->query("DELETE FROM respuesta WHERE id_asignacion = '$id_asignacion'") ||
                !$conexion->query("DELETE FROM evaluacion WHERE id_asignacion = '$id_asignacion'")
            ) {
                $exito = false;
            }
        }
    }

    if (!$conexion->query("DELETE FROM asignacion_formulario WHERE id_formulario = '$id_formulario'")) {
        $exito = false;
    }

    $preguntas = $conexion->query("SELECT id_pregunta FROM pregunta WHERE id_formulario = '$id_formulario'");
    if ($preguntas) {
        while ($row = $preguntas->fetch_assoc()) {
            $id_pregunta = $row['id_pregunta'];
            if (!$conexion->query("DELETE FROM opcion_pregunta WHERE id_pregunta = '$id_pregunta'")) {
                $exito = false;
            }
        }
    }

    if (!$conexion->query("DELETE FROM pregunta WHERE id_formulario = '$id_formulario'")) {
        $exito = false;
    }


    if (!$conexion->query("DELETE FROM formulario WHERE id_formulario = '$id_formulario'")) {
        $exito = false;
    }

    $conexion->close();

    if ($exito) {
        echo '<div class="notification success">Formulario y sus asignaciones han sido eliminados correctamente.</div>';
    } else {
        echo '<div class="notification error">Error: No se pudieron eliminar todos los datos relacionados con el formulario.</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Formulario</title>
    <style>
/* Estilo general del contenedor */
.notification {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    max-width: 90%;
    width: 400px;
    padding: 15px 20px;
    border-radius: 10px;
    font-family: 'Segoe UI', sans-serif;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    display: block;
    opacity: 0.95;
}

/* Tipos de notificación */
.notification.success {
    background-color: #e8f5e9;
    color: #2e7d32;
    border-left: 6px solid #2e7d32;
}

.notification.error {
    background-color: #ffebee;
    color: #c62828;
    border-left: 6px solid #c62828;
}

/* Responsive */
@media (max-width: 600px) {
    .notification {
        width: 95%;
        font-size: 14px;
        padding: 12px 16px;
    }
}

    </style>
</head>
<body>
<script>
  setTimeout(() => {
    const notif = document.querySelector('.notification');
    if (notif) notif.style.display = 'none';
  }, 3000); // 3 segundos
</script>
</body>
</html>