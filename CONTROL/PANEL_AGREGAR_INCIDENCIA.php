<?php
include('../MODELO/CL_SUCURSAL.php');
include('../MODELO/CL_DEPARTAMENTO.php');
include('../MODELO/CL_USUARIO.php');
include('../MODELO/CL_TABLA_INCIDENCIA_TIPO.php');
include('../MODELO/CL_TABLA_DETALLE_INCIDENCIA.php');
include('../VISTA/CL_INTERFAZ17.php'); 
$form_17 = new CL_INTERFAZ17();


$mensaje = "";
$tipo_mensaje = ""; 
session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if (!isset($_SESSION['acceso_incidencias']) || !$_SESSION['acceso_incidencias'] || $_SESSION['tipo_usuario'] !== 'Colaborador') {
    header('Location: acceso_denegado.php');
    exit();
}


if (isset($_POST['click_regresar'])) {
    header('Location: ../CONTROL/PANEL_EMPLEADO.php');
    exit();
}


if (isset($_POST['click_registrar'])) {
    $id_usuario = $_POST["caja_opcion4"];
    $id_tipo_incidencia = $form_17->get_caja_opcion3();

    $tablaUsuario = new CL_TABLA_USUARIO();
    $usuario = $tablaUsuario->buscar_usuario_por_id($id_usuario);

    $salario_diario = $usuario['sueldo_diario'] ?? 0;

    

    $periodo = $_POST['periodo'];
    if (strpos($periodo, 'to') !== false) {
        list($fecha_inicio, $fecha_fin) = explode(' to ', $periodo);
    } else {
        $fecha_inicio = $fecha_fin = $periodo;
    }

    $fecha_inicio = trim($fecha_inicio);
    $fecha_fin = trim($fecha_fin);

    $tipo_incidencia = new CL_TABLA_INCIDENCIA_TIPO();
    $formula = $tipo_incidencia->es_variable($id_tipo_incidencia); 
    $descuento = 0;
    $cantidad = 1;


    if ($formula) {
        // Si hay fórmula, calcular el descuento basado en los días y salario
        $dias = $_POST['dias'] ?? 1;
        $cantidad = $dias;

        if ($id_tipo_incidencia == 8){
            $descuento = (($dias * $salario_diario))/$dias;
           
        } else{
        $descuento = -(($dias * $salario_diario)/$dias) ;
        }
    } else {
        // Si no hay fórmula, usar el descuento fijo de incidencia_tipo
        $descuento = $tipo_incidencia->get_descuento_fijo($id_tipo_incidencia); 
        $cantidad = 1; // La cantidad será 1
    }

    $tablaDetalleIncidencia = new CL_TABLA_DETALLE_INCIDENCIA();
    $registroExitoso = $tablaDetalleIncidencia->registrar_detalle_incidencia([
        'id_usuario' => $id_usuario,
        'id_incidencia_tipo' => $id_tipo_incidencia,
        'cantidad' => $cantidad,
        'descuento' => $descuento,
        'fecha_inicio' => $fecha_inicio,
        'fecha_termino' => $fecha_fin,
        'id_reporte' => NULL
    ]);

    if ($registroExitoso) {
        $mensaje = "Incidencia registrada exitosamente.";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al registrar la incidencia.";
        $tipo_mensaje = "error";
    }

    header('Location: ../CONTROL/PANEL_INC_REGISTRO.php?mensaje=' . urlencode($mensaje) . '&tipo_mensaje=' . urlencode($tipo_mensaje));
    exit();
}

    
$selectedSucursal = $_POST['caja_opcion1'] ?? '';
$selectedDepartamento = $_POST['caja_opcion2'] ?? '';
$selectedTipoIncidencia = $_POST['caja_opcion3'] ?? '';
$selectedUsuario = $_POST['caja_opcion4'] ?? '';


if ($selectedSucursal && !$selectedDepartamento) {
    $tablaDepartamento = new CL_TABLA_DEPARTAMENTO();
    $departamentosHtml = $tablaDepartamento->listar_departamentos($selectedSucursal);
    if (strpos($departamentosHtml, 'selected') !== false) {
        preg_match('/value=\'(\d+)\' selected/', $departamentosHtml, $matches);
        $selectedDepartamento = $matches[1] ?? '';
    }
}

if ($selectedSucursal && $selectedDepartamento && !$selectedUsuario) {
    $tablaUsuario = new CL_TABLA_USUARIO();
    $usuariosHtml = $tablaUsuario->listar_usuarios_por_sucursal_departamento($selectedSucursal, $selectedDepartamento);
    if (strpos($usuariosHtml, 'selected') !== false) {
        preg_match('/value=\'(\d+)\' selected/', $usuariosHtml, $matches);
        $selectedUsuario = $matches[1] ?? '';
    }
}


$form_17->mostrar($selectedSucursal, $selectedDepartamento, $selectedUsuario, $selectedTipoIncidencia);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('../IMG/puesto.jpg'); /* Imagen de fondo */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .notification {
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(31, 58, 84, 0.9); /* Fondo blanco semitransparente */
            padding: 50px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            display: none; /* Oculto por defecto */
            z-index: 1000;
        }

        .notification.success {
            color: white;
        }

        .notification.error {
            color: white;
        }

        .notification button {
            margin-top: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: rgb(248, 36, 36);
            color: white;
            cursor: pointer;
        }

        .notification button:hover {
            background-color: rgb(255, 0, 0);
        }
    </style>
</head>
<body>
    <?php if (!empty($mensaje)): ?>
        <div class="notification <?php echo $tipo_mensaje; ?>" id="notification">
            <p><?php echo $mensaje; ?></p>
            <button onclick="closeNotification()">Cerrar</button>
        </div>
    <?php endif; ?>

    <script>

        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.display = 'block';
        }

        function closeNotification() {
            if (notification) {
                notification.style.display = 'none';
            }
        }
    </script>
</body>
</html>
