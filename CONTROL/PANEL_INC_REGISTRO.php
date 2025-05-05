<?php

include_once('../MODELO/CL_TABLA_DETALLE_INCIDENCIA.php');

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: ../CONTROL/SISTEMA_RH.php'); // O la página de inicio de sesión
    exit();
}

// Permitir acceso a Admin y RH
if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH' && $_SESSION['tipo_usuario'] !== 'Empleado') {
    header('Location: ../CONTROL/acceso_denegado.php');
    exit();
}

if (isset($_POST['click_regresar'])) {
    if ($_SESSION['tipo_usuario'] === 'Admin') {
        header('Location: ../CONTROL/PANEL_AGREGAR_INCIDENCIA.php');
    } elseif ($_SESSION['tipo_usuario'] === 'RH') {
        header('Location: ../CONTROL/PANEL_AGREGAR_INCIDENCIA.php');
    } else {
        header('Location: ../CONTROL/PANEL_AGREGAR_INCIDENCIA.php');
    }
    exit();
}

// Obtener la última incidencia registrada
$tablaDetalleIncidencia = new CL_TABLA_DETALLE_INCIDENCIA();
$incidencia = $tablaDetalleIncidencia->obtener_incidencia();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Última Incidencia Registrada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #007BFF;
            color: white;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Última Incidencia Registrada</h1>
        <?php if (!empty($incidencia)): ?>
    <table>
        <thead>
            <tr>
                <th>ID Usuario</th>
                <th>Tipo de Incidencia</th>
                <th>Cantidad</th>
                <th>Descuento</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($incidencia['id_usuario'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($incidencia['id_incidencia_tipo'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($incidencia['cantidad'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($incidencia['descuento'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($incidencia['fecha_inicio'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($incidencia['fecha_termino'] ?? 'N/A'); ?></td>
            </tr>
        </tbody>
    </table>
<?php else: ?>
    <p>No se encontró ninguna incidencia registrada.</p>
<?php endif; ?>
<form method="POST" action="">
        <button type="submit" name="click_regresar"><i class="fas fa-arrow-left"></i> Regresar</button>
    </form>
    </div>
</body>
</html>