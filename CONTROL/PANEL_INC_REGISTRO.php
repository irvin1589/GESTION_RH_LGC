<?php

include_once('../MODELO/CL_TABLA_DETALLE_INCIDENCIA.php');

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: ../CONTROL/SISTEMA_RH.php'); // O la página de inicio de sesión
    exit();
}

if (!isset($_SESSION['acceso_incidencias']) || $_SESSION['acceso_incidencias'] !== true && $_SESSION['tipo_usuario'] !== 'Empleado' ) {
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

        .container {
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            margin: 20px 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 600px; /* Ancho mínimo para mantener legibilidad */
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: left;
        }

        table th {
            background-color: #007BFF;
            color: white;
            position: sticky;
            top: 0;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #e9e9e9;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        /* Estilos responsivos */
        @media screen and (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            table {
                font-size: 14px;
            }
            
            table th, table td {
                padding: 8px 6px;
            }
        }

        @media screen and (max-width: 480px) {
            h1 {
                font-size: 24px;
            }
            
            button {
                width: 100%;
                padding: 12px;
            }
            
            table {
                min-width: 100%;
            }
            
            table th, table td {
                padding: 6px 4px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Última Incidencia Registrada</h1>
        <?php if (!empty($incidencia)): ?>
        <div class="table-responsive">
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
        </div>
        <?php else: ?>
            <p class="no-data">No se encontró ninguna incidencia registrada.</p>
        <?php endif; ?>
        <form method="POST" action="">
            <button type="submit" name="click_regresar"><i class="fas fa-arrow-left"></i> Regresar</button>
        </form>
    </div>
</body>
</html>