<?php
include_once('../MODELO/CL_TABLA_USUARIO.php');
include_once('../MODELO/CL_TABLA_FORMULARIO.php');
include_once('../MODELO/CL_TABLA_ASIGNACION_FORMULARIO.php');

if (isset($_POST['seleccionar_formulario'])) {
    $id_formulario = $_POST['id_formulario'];

    // Obtener los detalles del formulario
    $tablaFormulario = new CL_TABLA_FORMULARIO();
    $formulario = $tablaFormulario->obtener_formulario_por_id($id_formulario);

    if (!$formulario) {
        echo '<p>Error: No se pudo obtener los detalles del formulario.</p>';
        exit();
    }

    // Mostrar los detalles del formulario
    echo '<h2>Asignar Formulario: ' . htmlspecialchars($formulario['nombre']) . '</h2>';
    echo '<p>Descripción: ' . htmlspecialchars($formulario['descripcion']) . '</p>';
    echo '<p>Fecha Límite: ' . htmlspecialchars($formulario['fecha_limite']) . '</p>';

    // Filtrar usuarios por departamento, sucursal y puesto
    $id_departamento = $formulario['id_departamento'];
    $id_sucursal = $formulario['id_sucursal'];
    $id_puesto = $formulario['id_puesto'];

    $tablaUsuario = new CL_TABLA_USUARIO();
    $usuarios = $tablaUsuario->listar_usuarios_por_filtros($id_departamento, $id_sucursal, $id_puesto);

    // Mostrar el formulario para asignar a un usuario
    echo '<form method="POST" action="ASIGNAR_FORMULARIO.php">';
    echo '<input type="hidden" name="id_formulario" value="' . htmlspecialchars($id_formulario) . '">';

    echo '<label for="usuario">Usuario:</label>';
    echo '<select id="usuario" name="id_usuario" required>';
    echo '<option value="">Seleccione un usuario</option>';
    foreach ($usuarios as $usuario) {
        echo '<option value="' . htmlspecialchars($usuario['id_usuario']) . '">' . htmlspecialchars($usuario['nombre']) . '</option>';
    }
    echo '</select>';

    echo '<button type="submit" name="asignar_formulario">Asignar</button>';
    echo '</form>';
}

// Manejar la asignación del formulario al usuario
if (isset($_POST['asignar_formulario'])) {
    $id_formulario = $_POST['id_formulario'];
    $id_usuario = $_POST['id_usuario'];

    $tablaAsignacion = new CL_TABLA_ASIGNACION_FORMULARIO();
    $resultado = $tablaAsignacion->crear_asignacion($id_formulario, $id_usuario);

    if ($resultado) {
        echo '<p>Formulario asignado exitosamente al usuario.</p>';
    } else {
        echo '<p>Error al asignar el formulario.</p>';
    }
}
?>