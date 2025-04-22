<?php
include('../MODELO/CL_FORMULARIO.php');
include('../MODELO/CL_SUCURSAL.php');
include('../MODELO/CL_DEPARTAMENTO.php');
include('../MODELO/CL_PUESTO.php');
include('../VISTA/CL_INTERFAZ02.php');
include_once('../VISTA/CL_INTERFAZ04.php'); // Ya está incluida la clase
include_once('../MODELO/CL_TABLA_FORMULARIO.php');

// Si presionas el botón "regresar"
if (isset($_POST['click_regresar'])) {
    var_dump(realpath('../CONTROL/PANEL_ADMIN.php')); // Verifica la ruta absoluta
    header('Location: ../CONTROL/PANEL_ADMIN.php');
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
?>