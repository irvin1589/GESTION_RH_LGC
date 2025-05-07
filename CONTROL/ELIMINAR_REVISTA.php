<?php
session_start();

include_once('../MODELO/CL_TABLA_REVISTA.php');
include_once('../VISTA/CL_INTERFAZ19.php');

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}
if ($_SESSION['tipo_usuario'] !== 'Admin') {
    header('Location: acceso_denegado.php');
    exit();
}

if (isset($_POST['click_regresar'])){
    header('Location: ../CONTROL/PANEL_COMUNICACION_ADMIN.php');
    exit();
}

if (isset($_POST['id_revista'])) {
    $id_revista = $_POST['id_revista'];

    $tabla = new CL_TABLA_REVISTA();
    $revista = $tabla->buscar_por_id($id_revista);

    if ($revista) {
        // Eliminar archivo PDF si existe
        if (file_exists($revista['archivo_pdf'])) {
            unlink($revista['archivo_pdf']);
        }

        // Eliminar de la base de datos
        $tabla->eliminar_revista($id_revista);
    }

    header('Location: ELIMINAR_REVISTA.php');
exit();
}

    


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
            <form method=\"POST\" action=\"../CONTROL/ELIMINAR_REVISTA.php\">
                <input type=\"hidden\" name=\"id_revista\" value=\"{$r['id_revista']}\">
                <button type=\"submit\" class=\"btn-eliminar\"> <i class=\"fas fa-trash\"></i> Eliminar</button>
            </form>
            <hr>
        </div>
    ";
}

$vista = new CL_INTERFAZ19();
$vista->mostrar($html_revistas);
