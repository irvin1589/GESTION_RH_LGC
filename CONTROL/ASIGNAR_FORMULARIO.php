<?php
include_once('../MODELO/CL_TABLA_DEPARTAMENTO.php');
include_once('../MODELO/CL_TABLA_PUESTO.php');
include_once('../MODELO/CL_TABLA_SUCURSAL.php');
include_once('../VISTA/CL_INTERFAZ08.php');
include_once('../MODELO/CL_TABLA_FORMULARIO.php');
include_once('../MODELO/CL_TABLA_USUARIO.php');
include_once('../MODELO/CL_TABLA_ASIGNACION_FORMULARIO.php');

// Mostrar la lista de formularios
$form_08 = new CL_INTERFAZ08();
$form_08->mostrar();

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); // O la página de inicio de sesión
    exit();
}

// Permitir acceso a Admin y RH
if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}
// Manejar la selección de un formulario
if (isset($_POST['seleccionar_formulario'])) {
    $id_formulario = $_POST['id_formulario'];

    // Obtener los detalles del formulario
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

// Botón para regresar al panel de administración
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Formulario</title>
    <style>
        .notification {
            width: 80%;
            margin: 20px auto;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <!-- Aquí va el resto del contenido -->
</body>
</html>