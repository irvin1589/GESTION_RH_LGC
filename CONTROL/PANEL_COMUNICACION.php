<?php
session_start();

include_once('../MODELO/CL_TABLA_REVISTA.php');
include_once('../VISTA/CL_INTERFAZ12.php');

// Verificación de sesión y tipo (si quieres)
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if (isset($_POST['click_regresar'])) {
    if ($_SESSION['tipo_usuario'] === 'Admin') {
        header('Location: ../CONTROL/PANEL_COMUNICACION_ADMIN.php');
    } elseif ($_SESSION['tipo_usuario'] === 'RH') {
        header('Location: ../CONTROL/PANEL_RH.php');
    } else {
        header('Location: ../CONTROL/PANEL_EMPLEADO.php');
    }
    exit();
}

// Obtener revistas
$tabla = new CL_TABLA_REVISTA();
$revistas = $tabla->listar_todas();

$html_revistas = "";
foreach ($revistas as $r) {
    $html_revistas .= "
        <div>
            <h2>{$r['titulo']}</h2>
            <p>{$r['contenido']}</p>
            <p><strong>Fecha:</strong> {$r['fecha_publicacion']}</p>
            <p><strong>Autor:</strong> {$r['autor']}</p>
            <a href=\"{$r['archivo_pdf']}\" class=\"btn-pdf\" target=\"_blank\">
              <i class=\"fas fa-file-pdf\"></i> Ver PDF
            </a>
            <hr>
        </div>
    ";
}

$vista = new CL_INTERFAZ12();
$vista->mostrar($html_revistas);
