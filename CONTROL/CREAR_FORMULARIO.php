<?php
include('../MODELO/CL_FORMULARIO.php');
include('../MODELO/CL_SUCURSAL.php');
include('../MODELO/CL_DEPARTAMENTO.php');
include('../MODELO/CL_PUESTO.php');
include('../VISTA/CL_INTERFAZ02.php');
include_once('../VISTA/CL_INTERFAZ04.php');
include_once('../MODELO/CL_TABLA_FORMULARIO.php');

session_start();
ob_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); // O la página de inicio de sesión
    exit();
}

// Permitir acceso a Admin y RH
if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

// Si presionas el botón "regresar"
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
// Inicializar la instancia de CL_INTERFAZ03
$form_04 = new CL_INTERFAZ04();


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
$form_04->mostrar($selectedSucursal, $selectedDepartamento, $selectedPuesto);


if(isset($_POST['click_siguiente'])){
    // header('Location: ../CONTROL/PANEL_ADMIN.php');

    if (isset($_POST['click_siguiente'])) {
       
        $idSucursal = $_POST['caja_opcion1'];
        $idDepartamento = $_POST['caja_opcion2'];
        $idPuesto = $_POST['caja_opcion3'];
        $nombreFormulario = $_POST['caja_texto1'];
        $descripcion = $_POST['caja_texto2'];
        $fechaLimite = $_POST['caja_texto3'];

        $formulario = new CL_FORMULARIO();
        $formulario->set_nombre($nombreFormulario);
        $formulario->set_descripcion($descripcion);
        $formulario->set_fecha_limite($fechaLimite);
        $formulario->set_sucursal_id($idSucursal);
        $formulario->set_departamentoId($idDepartamento);
        $formulario->set_puestoId($idPuesto);

        $t_formulario = new CL_TABLA_FORMULARIO();
        $idFormulario = $t_formulario->guardar_formulario($formulario);

        $_SESSION['formularioId'] = $idFormulario;
        header('Location: ../CONTROL/CREAR_PREGUNTAS.php');
        exit();
        
        
    }
    


}
?>