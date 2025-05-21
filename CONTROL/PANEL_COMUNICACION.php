<?php
session_start();

include_once('../MODELO/CL_TABLA_REVISTA.php');
include_once('../VISTA/CL_INTERFAZ12.php');

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


$tabla = new CL_TABLA_REVISTA();
$revistas = $tabla->listar_todas();

$html_revistas = "";
$html_revistas = "";

$html_revistas .= "<div class=\"revistas-grid\">";

foreach ($revistas as $index => $r) {
    $portada = "../IMG/portada1.png"; // Asegúrate de tener una imagen por defecto o dinámica
    $delay = ($index + 1) * 0.1; // Para animaciones
    $html_revistas .= "
    <div class=\"revista-card\" style=\"animation-delay: {$delay}s\">
        <div class=\"revista-portada\">
            <img src=\"$portada\" alt=\"Portada de la revista\">
        </div>
        <div class=\"revista-content\">
            <div class=\"revista-titulo\">{$r['titulo']}</div>
            <div class=\"revista-descripcion\">{$r['contenido']}</div>
            <div class=\"revista-fecha\"><strong>Publicado:</strong> {$r['fecha_publicacion']}<br><strong>Autor:</strong> {$r['autor']}</div>
            <div class=\"revista-acciones\">
                <a href=\"{$r['archivo_pdf']}\" class=\"btn-pdf\" target=\"_blank\">
                    <i class=\"fas fa-file-pdf\"></i> Ver PDF
                </a>
                <form method=\"POST\" action=\"../CONTROL/ELIMINAR_REVISTA.php\">
                    <input type=\"hidden\" name=\"id_revista\" value=\"{$r['id_revista']}\">
                </form>
            </div>
        </div>
    </div>";
}

$html_revistas .= "</div>"; // Cierra la cuadrícula

$vista = new CL_INTERFAZ12();
$vista->mostrar($html_revistas);
