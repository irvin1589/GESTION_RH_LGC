<?php
include('../MODELO/CL_USUARIO.php');
include('../MODELO/CL_SUCURSAL.php');
include('../MODELO/CL_DEPARTAMENTO.php');
include('../MODELO/CL_PUESTO.php');
include('../VISTA/CL_INTERFAZ01.php');
include_once('../VISTA/CL_INTERFAZ03.php'); // Ya está incluida la clase
include_once('../MODELO/CL_TABLA_USUARIO.php');


session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: ../CONTROL/SISTEMA_RH.php'); // O la página de inicio de sesión
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: ../CONTROL/acceso_denegado.php');
    exit();
}


if (isset($_POST['click_regresar'])) {
    if ($_SESSION['tipo_usuario'] === 'Admin') {
        header('Location: ../CONTROL/PANEL_ADMIN.php');
    } elseif ($_SESSION['tipo_usuario'] === 'RH') {
        header('Location: ../CONTROL/PANEL_DESARROLLO.php');
    } else {
        header('Location: ../CONTROL/acceso_denegado.php');
    }
    exit();
}

// Inicializar la instancia de CL_INTERFAZ03
$form_03 = new CL_INTERFAZ03();

// Si presionas el botón "registrar"
if (isset($_POST['click_registrar'])) {
    // Obtener los valores del formulario
    $id_usuario = $form_03->get_caja_texto1();
    $nombre = $form_03->get_caja_texto2();
    $apellido1 = $form_03->get_caja_texto3();
    $apellido2 = $form_03->get_caja_texto4();
    $contraseña = $form_03->get_caja_texto5();
    $contraseña_verificacion = $form_03->get_caja_texto6();
    $id_sucursal = $form_03->get_caja_opcion1();
    $id_departamento = $form_03->get_caja_opcion2();
    $id_puesto = $form_03->get_caja_opcion3();
    $tipo_usuario = $form_03->get_caja_opcion4();

    // Validar que el tipo de usuario sea válido
    $tipos_validos = ['Admin', 'RH', 'Empleado'];
    if (!in_array($tipo_usuario, $tipos_validos)) {
        echo "Error: Tipo de usuario no válido.";
        exit;
    }

    if ($contraseña !== $contraseña_verificacion) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Crear objeto de usuario
    $usuario = new CL_USUARIO();
    $usuario->set_id_usuario($id_usuario);
    $usuario->set_nombre($nombre);
    $usuario->set_apellido1($apellido1);
    $usuario->set_apellido2($apellido2);
    $usuario->set_contraseña($contraseña);
    $usuario->set_tipo_usuario($tipo_usuario);
    
    // Crear objetos de sucursal, departamento y puesto
    $sucursal = new CL_SUCURSAL();
    $sucursal->set_id_sucursal($id_sucursal);

    $departamento = new CL_DEPARTAMENTO();
    $departamento->set_id_departamento($id_departamento);

    $puesto = new CL_PUESTO();
    $puesto->set_id_puesto($id_puesto);

    // Guardar el usuario
    $tablaUsuario = new CL_TABLA_USUARIO();
    if ($tablaUsuario->guardar($usuario, $sucursal, $puesto, $departamento)) {
       header('Location: ../CONTROL/SISTEMA_RH.php');
       exit(); // Asegúrate de salir después de redirigir
    } else {
        echo "Error al registrar el usuario.";
}
}

// Inicializar la variable de selección de sucursal, departamento y puesto
$selectedSucursal = $_POST['caja_opcion1'] ?? '';
$selectedDepartamento = $_POST['caja_opcion2'] ?? '';
$selectedPuesto = $_POST['caja_opcion3'] ?? '';

// Si hay una sucursal seleccionada pero no un departamento, selecciona automáticamente el único departamento disponible
if ($selectedSucursal && !$selectedDepartamento) {
    $tablaDepartamento = new CL_TABLA_DEPARTAMENTO();
    $departamentosHtml = $tablaDepartamento->listar_departamentos($selectedSucursal);
    if (strpos($departamentosHtml, 'selected') !== false) {
        preg_match('/value=\'(\d+)\' selected/', $departamentosHtml, $matches);
        $selectedDepartamento = $matches[1] ?? '';
    }
}

// Si hay un departamento seleccionado pero no un puesto, selecciona automáticamente el único puesto disponible
if ($selectedDepartamento && !$selectedPuesto) {
    $tablaPuesto = new CL_TABLA_PUESTO();
    $puestosHtml = $tablaPuesto->listar_puestos($selectedSucursal, $selectedDepartamento);
    if (strpos($puestosHtml, 'selected') !== false) {
        preg_match('/value=\'(\d+)\' selected/', $puestosHtml, $matches);
        $selectedPuesto = $matches[1] ?? '';
    }
}

// Crear la instancia de la clase CL_INTERFAZ03 y mostrar el formulario con las opciones seleccionadas
$form_03->mostrar($selectedSucursal, $selectedDepartamento, $selectedPuesto);
?>
