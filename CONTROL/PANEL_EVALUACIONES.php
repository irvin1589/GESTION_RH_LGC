<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once('../VISTA/CL_INTERFAZ14.php'); 
include('../MODELO/CL_TABLA_ASIGNACION_FORMULARIO.php');
include('../MODELO/CL_TABLA_FORMULARIO.php');
$form_14 = new CL_INTERFAZ14();
session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}
if ($_SESSION['tipo_usuario'] !== 'Colaborador') {
    header('Location: acceso_denegado.php');
    exit();
}
if (isset($_POST['click_regresar'])) {
    header('Location: PANEL_EMPLEADO.php');
    exit();
}
if (isset($_POST['click_ver_evaluaciones'])) {
    header('Location: VER_ASIGNACIONES_EMP.php');
    exit();
}
if (isset($_POST['click_cerrar_sesion'])) {
    session_unset();
    session_destroy();
    header('Location: SISTEMA_RH.php');
    exit();
}


echo '
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
    }
    
    .contenedor {
        width: 95%;
        max-width: 1200px;
        margin: 20px auto;
        padding: 15px;
    }
    
    h2 {
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    
    th {
        background-color: #f8f8f8;
        font-weight: bold;
        color: #333;
    }
    
    tr:hover {
        background-color: #f5f5f5;
    }
    
    .btn-completar, .btn-ver {
        display: inline-block;
        padding: 8px 15px;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        text-align: center;
        font-size: 14px;
        transition: background-color 0.3s;
    }
    
    .btn-completar {
        background-color: #4CAF50;
    }
    
    .btn-ver {
        background-color: #2196F3;
    }
    
    .btn-completar:hover {
        background-color: #3e8e41;
    }
    
    .btn-ver:hover {
        background-color: #0b7dda;
    }
    
    /* Estilos responsive */
    @media screen and (max-width: 768px) {
        table {
            border: 0;
            box-shadow: none;
        }
        
        table thead {
            display: none; /* Ocultar encabezados en pantallas pequeñas */
        }
        
        table tr {
            margin-bottom: 20px;
            display: block;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        table td {
            display: block;
            text-align: right;
            font-size: 14px;
            border-bottom: 1px dotted #ccc;
            position: relative;
            padding-left: 50%;
        }
        
        table td:last-child {
            border-bottom: 0;
        }
        
        table td:before {
            content: attr(data-label);
            position: absolute;
            left: 12px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            text-align: left;
            font-weight: bold;
        }
        
        .btn-completar, .btn-ver {
            display: block;
            width: 100%;
            margin: 5px 0;
        }
    }
    
    @media screen and (max-width: 480px) {
        .contenedor {
            width: 100%;
            padding: 10px;
        }
        
        h2 {
            font-size: 20px;
        }
    }
</style>
';

// Mostrar la interfaz
$form_14->mostrar();

// Obtener asignaciones del usuario
$t_asignacion = new CL_TABLA_ASIGNACION_FORMULARIO();
$id_usuario = $_SESSION['id_usuario'];
$asignaciones = $t_asignacion->get_asignaciones_user($id_usuario);

// Mostrar las asignaciones
echo "<main class='contenedor-principal fade-in'>"; 
echo "<h2>Formularios Asignados</h2>";

if ($asignaciones && count($asignaciones) > 0) {
    echo "<table>";
    echo "<thead>
            <tr>
                <th>Formulario</th>
                <th>Descripción</th>
                <th>Fecha Asignación</th>
                <th>Fecha Límite</th>
                <th>Completado</th>
                <th>Acciones</th>
            </tr>
          </thead>";
    echo "<tbody>";
    foreach ($asignaciones as $asig) {
        echo "<tr>";
        echo "<td data-label='Formulario'>{$asig['nombre_formulario']}</td>";
        echo "<td data-label='Descripción'>{$asig['descripcion']}</td>";
        echo "<td data-label='Fecha Asignación'>{$asig['fecha_asignacion']}</td>";
        echo "<td data-label='Fecha Límite'>{$asig['fecha_limite']}</td>";
        echo "<td data-label='Completado'>" . ($asig['completado'] ? 'Sí' : 'No') . "</td>";
        
        $id_asignacion = $asig['id_asignacion'];
        $_SESSION['id_asignacion'] = $id_asignacion;
        
        if ($asig['completado']) {
            echo "<td data-label='Acciones'><a href='VER_FORM.php?id_asignacion={$id_asignacion}' class='btn-ver'>Ver Respuestas</a></td>";
        } else {
            echo "<td data-label='Acciones'><a href='RESPONDER_FORM.php?id_asignacion={$id_asignacion}' class='btn-completar'>Completar</a></td>";
        }
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<div class='mensaje-vacio' style='display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 20px;'>
  <div class='imagen-contenedor' style='margin-bottom: 15px;'>
    <img src='../IMG/form.png' alt='Imagen descriptiva' style='max-width: 200px; height: auto;'>
  </div>
  <p>No tienes formularios asignados.</p>
</div>";
}
echo "</main>";

echo "<footer class='footer'>
    <div class='footer__contenedor'>
        <div class='footer__grid'>
            <!-- Columna de contacto -->
            <div class='footer__columna'>
                <h3 class='footer__titulo'>Contacto</h3>
                <ul class='footer__lista'>
                    <li class='footer__item'>
                        <i class='fas fa-map-marker-alt footer__icono'></i>
                        <span>Doctor Jorge Jiménez Cantú S/N, Felipe Ureña, 50450 Atlacomulco de Fabela, Méx.</span>
                    </li>
                    <li class='footer__item'>
                        <i class='fas fa-phone-alt footer__icono'></i>
                        <span>+52 712 120 0590 EXT: 210</span>
                    </li>
                    <li class='footer__item'>
                        <i class='fas fa-envelope footer__icono'></i>
                        <span>scruz@lagranciudad.mx</span>
                    </li>
                    <li class='footer__item'>
                        <i class='fas fa-clock footer__icono'></i>
                        <span>Lun-Vie: 9:00 - 18:00 hrs</span>
                    </li>
                </ul>
            </div>

            <!-- Columna de redes sociales -->
            <div class='footer__columna'>
                <h3 class='footer__titulo'>Síguenos</h3>
                <div class='footer__redes'>
                    <a href='https://www.facebook.com/LaGranCiudadDepartamental' class='footer__social'><i class='fab fa-facebook-f'></i></a>
                    <a href='https://www.youtube.com/channel/UCFxxT07KLHl7Mr3U7bO7WwA' class='footer__social'><i class='fab fa-youtube'></i></a>
                    <a href='https://www.instagram.com/lagranciudaddepartamental/' class='footer__social'><i class='fab fa-instagram'></i></a>
                    <a href='https://www.tiktok.com/@lagranciudad' class='footer__social'><i class='fab fa-tiktok'></i></a>
                </div>
            </div>

        <div class='footer__columna'>
         <div class='footer__mapa'>
        <h3 class='footer__titulo'>Nuestra Ubicación</h3>
        <div class='mapa-contenedor'>
            <iframe 
                src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7508.011606429436!2d-99.87793970642088!3d19.797351300000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d258e8778db873%3A0x41e5479a09c3bd62!2sLa%20Gran%20Ciudad%20Outlet%20Atlacomulco!5e0!3m2!1ses!2smx!4v1748882909860!5m2!1ses!2smx' 
                width='100%' 
                height='250' 
                style='border:0;' 
                allowfullscreen='' 
                loading='lazy' 
                referrerpolicy='no-referrer-when-downgrade'
                class='mapa-iframe'>
            </iframe>
        </div>
    </div>
        </div>
    </div>

        <!-- Derechos reservados -->
        <div class='footer__derechos'>
            <p>&copy; " . date('Y') . " LA GRAN CIUDAD RH. Todos los derechos reservados.</p>
            <div class='footer__legal'>
                <a href='#'>Términos de servicio</a>
                <a href='#'>Política de privacidad</a>
            </div>
        </div>
</footer>";
?>