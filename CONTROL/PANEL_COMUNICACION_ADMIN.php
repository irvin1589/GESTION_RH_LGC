<?php
ob_start();
session_start();


if (isset($_POST['click_regresar'])) {
    header('Location: ../CONTROL/PANEL_ADMIN.php');
    exit();
}
if (isset($_POST['click_ver_revista'])) {
    header('Location: PANEL_COMUNICACION.php');
    exit();
}

// 2) Carga de clases necesarias para guardar
include_once('../MODELO/CL_REVISTA.php');
include_once('../MODELO/CL_TABLA_REVISTA.php');

// 3) Procesar el "Guardar"
if (isset($_POST['click_guardar_revista'])) {
    // Sanitizar y validar inputs
    $nombre    = trim($_POST['caja_texto1'] ?? '');
    $contenido = trim($_POST['caja_texto2'] ?? '');

    if ($nombre === '' || $contenido === '') {
        echo "Por favor complete todos los campos.";
    } elseif (!isset($_FILES['archivo_pdf']) || $_FILES['archivo_pdf']['error'] !== UPLOAD_ERR_OK) {
        echo "Error en la carga del PDF (código " . ($_FILES['archivo_pdf']['error'] ?? 'sin archivo') . ").";
    } else {
        // Construir rutas
        $filename    = basename($_FILES['archivo_pdf']['name']);
        $storagePath = __DIR__ .'../../uploads/' . $filename;  // Ruta en disco
        $webPath     = '../uploads/'. $filename; 

        // Intentar mover el archivo
        if (!move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $storagePath)) {
            echo "Error al mover el archivo PDF.";
        } else {
            // Crear y poblar el objeto CL_REVISTA
            $revista = new CL_REVISTA();
            $revista->set_titulo($nombre);
            $revista->set_contenido($contenido);
            $revista->set_archivo_pdf($webPath);
            $revista->set_fecha_publicacion(date('Y-m-d'));
            $revista->set_autor("C.P. Maria de los Angeles Campos Robles-Departamento de Recursos Humanos");

            // Guardar en BD
            $tabla = new CL_TABLA_REVISTA();
            if ($tabla->guardar($revista)) {
                echo "Revista registrada con éxito.";
            } else {
                echo "Error al registrar la revista en la base de datos.";
            }
        }
    }
}

// 4) Finalmente, mostrar la interfaz (sin haber enviado cabeceras después)
include_once('../VISTA/CL_INTERFAZ11.php');
$form = new CL_INTERFAZ11();
$form->mostrar();

ob_end_flush();
