<?php
ob_start();
include('../MODELO/CL_CONEXION.php');

session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

$connexion = new CL_CONEXION();
$conn = $connexion->conectar();

// Obtener sucursales y departamentos para todo el script
$query_sucursales = "SELECT * FROM sucursal";
$stmt_sucursales = $conn->prepare($query_sucursales);
$stmt_sucursales->execute();
$sucursales = $stmt_sucursales->fetchAll(PDO::FETCH_ASSOC);

$query_departamentos = "SELECT * FROM departamento";
$stmt_departamentos = $conn->prepare($query_departamentos);
$stmt_departamentos->execute();
$departamentos = $stmt_departamentos->fetchAll(PDO::FETCH_ASSOC);

// Mostrar formulario de fechas, sucursal y departamento
if (!isset($_GET['fecha_inicio']) || !isset($_GET['fecha_fin']) || !isset($_GET['id_sucursal']) || !isset($_GET['id_departamento'])) {
    // Obtener sucursales y departamentos disponibles
    $connexion = new CL_CONEXION();
    $conn = $connexion->conectar();

    // Obtener todas las sucursales
    $query_sucursales = "SELECT * FROM sucursal";
    $stmt_sucursales = $conn->prepare($query_sucursales);
    $stmt_sucursales->execute();
    $sucursales = $stmt_sucursales->fetchAll(PDO::FETCH_ASSOC);

    // Obtener todos los departamentos
    $query_departamentos = "SELECT * FROM departamento";
    $stmt_departamentos = $conn->prepare($query_departamentos);
    $stmt_departamentos->execute();
    $departamentos = $stmt_departamentos->fetchAll(PDO::FETCH_ASSOC);

    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FILTROS INCIDENCIAS | LA GRAN CIUDAD RH</title>
        <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            :root {
                --primary-color: #1f3a54;
                --secondary-color: #941C82;
                --light-color: #f8f9fa;
                --dark-color: #343a40;
                --border-radius: 8px;
                --box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }
            body {
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f0f2f5;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                padding: 20px;
            }
            .form-container {
                background-color: white;
                padding: 30px;
                border-radius: var(--border-radius);
                box-shadow: var(--box-shadow);
                width: 100%;
                max-width: 500px;
            }
            .form-header {
                text-align: center;
                margin-bottom: 25px;
                color: var(--primary-color);
            }
            .form-header h2 {
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }
            .form-group {
                margin-bottom: 20px;
            }
            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: var(--primary-color);
            }
            .form-control {
                width: 100%;
                padding: 12px 15px;
                border: 1px solid #ddd;
                border-radius: var(--border-radius);
                font-size: 1rem;
                background-color: var(--light-color);
            }
            .btn-submit {
                width: 100%;
                padding: 12px;
                background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
                color: white;
                border: none;
                border-radius: var(--border-radius);
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .btn-submit:hover {
                opacity: 0.9;
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <div class="form-container">
            <div class="form-header">
                <h2><i class="fas fa-filter"></i> FILTROS DE REPORTE</h2>
                <p>Seleccione los parámetros para generar el reporte</p>
            </div>
            <form method="get">
                <div class="form-group">
                    <label for="fecha_inicio"><i class="fas fa-calendar-day"></i> Fecha inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="fecha_fin"><i class="fas fa-calendar-check"></i> Fecha fin</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="id_sucursal"><i class="fas fa-store"></i> Sucursal</label>
                    <select id="id_sucursal" name="id_sucursal" class="form-control" required>';
                    foreach ($sucursales as $sucursal) {
                        echo "<option value='" . $sucursal['id_sucursal'] . "'>" . htmlspecialchars($sucursal['nombre']) . "</option>";
                    }
                    echo '</select>
                </div>
                <div class="form-group">
                    <label for="id_departamento"><i class="fas fa-building"></i> Departamento</label>
                    <select id="id_departamento" name="id_departamento" class="form-control" required>';
                    foreach ($departamentos as $departamento) {
                        echo "<option value='" . $departamento['id_departamento'] . "'>" . htmlspecialchars($departamento['nombre']) . "</option>";
                    }
                    echo '</select>
                </div>
                <button type="submit" class="btn-submit">Generar Reporte</button>
            </form>
        </div>
    </body>
    </html>';
    exit;
}

$fecha_inicio = $_GET['fecha_inicio'];
$fecha_fin = $_GET['fecha_fin'];
$id_sucursal = $_GET['id_sucursal'];
$id_departamento = $_GET['id_departamento'];

// Validación básica
if ($fecha_inicio > $fecha_fin) {
    echo '<div style="padding: 20px; background-color: #f8d7da; color: #721c24; border-radius: 5px; max-width: 500px; margin: 20px auto; text-align: center;">
            <i class="fas fa-exclamation-triangle"></i> La fecha de inicio no puede ser mayor que la fecha de fin.
          </div>';
    exit;
}

$connexion = new CL_CONEXION();
$conn = $connexion->conectar();

$query = "
    SELECT 
        di.id_detalle_incidencia,
        u.id_usuario,
        u.nombre,
        u.apellido1,
        u.apellido2,
        s.nombre AS sucursal,
        d.nombre AS departamento,
        i.codigo AS codigo_incidencia,
        it.descripcion AS descripcion_incidencia,
        di.cantidad,
        di.fecha_inicio,
        di.fecha_termino,
        di.descuento
    FROM detalle_incidencia di
    JOIN usuario u ON di.id_usuario = u.id_usuario
    JOIN sucursal s ON u.id_sucursal = s.id_sucursal
    JOIN departamento d ON u.id_departamento = d.id_departamento
    JOIN incidencia_tipo it ON di.id_incidencia_tipo = it.id_incidencia_tipo
    JOIN incidencia i ON it.codigo_incidencia = i.codigo
    WHERE di.fecha_inicio >= :fecha_inicio AND di.fecha_termino <= :fecha_fin
    AND u.id_sucursal = :id_sucursal
    AND u.id_departamento = :id_departamento
    ORDER BY u.id_usuario, i.codigo
";

$stmt = $conn->prepare($query);
$stmt->bindParam(':fecha_inicio', $fecha_inicio);
$stmt->bindParam(':fecha_fin', $fecha_fin);
$stmt->bindParam(':id_sucursal', $id_sucursal);
$stmt->bindParam(':id_departamento', $id_departamento);
$stmt->execute();

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE INCIDENCIAS | LA GRAN CIUDAD RH</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1f3a54;
            --secondary-color: #941C82;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 8px;
            --box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: var(--dark-color);
            margin: 0;
            padding: 20px;
        }
        .report-container {
            max-width: 100%;
            margin: 0 auto;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            overflow-x: auto;
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .report-header h2 {
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .report-info {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }
        .info-card {
            flex: 1;
            min-width: 200px;
            background-color: var(--light-color);
            padding: 15px;
            border-radius: var(--border-radius);
        }
        .info-card h3 {
            color: var(--primary-color);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9rem;
        }
        th {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }
        tr:nth-child(even) {
            background-color: var(--light-color);
        }
        tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .currency {
            text-align: right;
        }
        .total-row {
            background-color: rgba(31, 58, 84, 0.1);
            font-weight: bold;
        }
        .total-row td {
            padding: 15px;
        }
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: flex-end;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        .btn-success:hover {
            opacity: 0.9;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        @media (max-width: 768px) {
            .report-container {
                padding: 15px;
            }
            .button-group {
                flex-direction: column;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h2><i class="fas fa-file-alt"></i> REPORTE DE INCIDENCIAS</h2>
        </div>
        
        <div class="report-info">
            <div class="info-card">
                <h3>Periodo del reporte</h3>
                <p>'.htmlspecialchars($fecha_inicio).' - '.htmlspecialchars($fecha_fin).'</p>
            </div>
            <div class="info-card">
                <h3>Sucursal</h3>
                <p>';
                foreach ($sucursales as $sucursal) {
                    if ($sucursal['id_sucursal'] == $id_sucursal) {
                        echo htmlspecialchars($sucursal['nombre']);
                        break;
                    }
                }
                echo '</p>
            </div>
            <div class="info-card">
                <h3>Departamento</h3>
                <p>';
                foreach ($departamentos as $departamento) {
                    if ($departamento['id_departamento'] == $id_departamento) {
                        echo htmlspecialchars($departamento['nombre']);
                        break;
                    }
                }
                echo '</p>
            </div>
        </div>';

if ($stmt->rowCount() > 0) {
    echo '<table>
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Sucursal</th>
                    <th>Departamento</th>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Fechas</th>
                    <th class="currency">Descuento</th>
                    <th class="currency">Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $total_descuento = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $descuento_total = $row['descuento'] * $row['cantidad'];
        $total_descuento += $descuento_total;

        echo '<tr>
                <td>'.htmlspecialchars($row['id_usuario']).'</td>
                <td>'.htmlspecialchars($row['nombre']).'</td>
                <td>'.htmlspecialchars($row['apellido1'].' '.$row['apellido2']).'</td>
                <td>'.htmlspecialchars($row['sucursal']).'</td>
                <td>'.htmlspecialchars($row['departamento']).'</td>
                <td>'.htmlspecialchars($row['codigo_incidencia']).'</td>
                <td>'.htmlspecialchars($row['descripcion_incidencia']).'</td>
                <td>'.htmlspecialchars($row['cantidad']).'</td>
                <td>'.htmlspecialchars($row['fecha_inicio']).'<br>'.htmlspecialchars($row['fecha_termino']).'</td>
                <td class="currency">$'.number_format($row['descuento'], 2).'</td>
                <td class="currency">$'.number_format($descuento_total, 2).'</td>
                <td>
                     <a href="editar_incidencia.php?id=' .$row['id_detalle_incidencia']. '" class="btn-edit">
                    <i class="fas fa-edit"></i> Editar
                    </a>

                </td>
              </tr>';
    }
}

    echo '<tr class="total-row">
            <td colspan="9"></td>
            <td style="text-align: right; font-weight: bold;">Total:</td>
            <td class="currency">$'.number_format($total_descuento, 2).'</td>
          </tr>
          </tbody>
        </table>
        
        <div class="button-group">
            <form method="post">
                <button type="submit" name="exportar_excel" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </button>
            </form>
            <form method="post">
                <button type="submit" name="click_regresar" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Regresar
                </button>
            </form>
        </div>';
} else {
    echo '<div class="no-data">
            <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 15px;"></i>
            <p>No se encontraron incidencias en el rango de fechas seleccionado.</p>
            <form method="post" style="margin-top: 20px;">
                <button type="submit" name="click_regresar" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Regresar
                </button>
            </form>
          </div>';
}

echo '</div></body></html>';

$conn = null;

if (isset($_POST['click_regresar'])) {
    ob_clean();
    header('Location: ../CONTROL/PANEL_INCIDENCIAS.php');
    exit();
}

if (isset($_POST['exportar_excel'])) {
    ob_clean();

    $nombre_archivo = "reporte_incidencias_" . $fecha_inicio . "_a_" . $fecha_fin . ".xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$nombre_archivo");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Cambia esta ruta a la URL completa o base64 si es necesario
    $logo_url = "https://coral-walrus-273461.hostingersite.com/IMG/LOGO_LGC__AZUL.jpg"; // Usa una URL accesible públicamente

    echo '
    <table width="100%" style="font-family: Arial; margin-bottom: 20px;">
        <tr>
            <td style="width: 10%;" align="center">
                <img src="'.$logo_url.'" alt="Logo" style="height:8px; width:4px;">
            </td>
            <td style="width: 80%;" align="center">
                <h2 style="color: #2F4F4F; margin: 0;">REPORTE DE INCIDENCIAS</h2>
                <p style="font-size: 14px; margin: 5px 0;">
                    <strong>Periodo del reporte:</strong> '.htmlspecialchars($fecha_inicio).' al '.htmlspecialchars($fecha_fin).'<br>
                    <strong>Sucursal:</strong> '.htmlspecialchars($sucursal['nombre']).'<br>
                    <strong>Departamento:</strong> '.htmlspecialchars($departamento['nombre']).'
                </p>
            </td>
        </tr>
    </table>';

    echo '<table border="1" style="border-collapse: collapse; font-family: Arial, sans-serif;">
            <thead>
                <tr style="background-color: #1F3A54; color: white; text-align: center;">
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Sucursal</th>
                    <th>Departamento</th>
                    <th>Código Incidencia</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Término</th>
                    <th>Descuento</th>
                    <th>Descuento Total</th>
                </tr>
            </thead>
            <tbody>';

    $total_descuento = 0;
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $descuento_total = $row['descuento'] * $row['cantidad'];
        $total_descuento += $descuento_total;

        echo '<tr>
                <td style="text-align:center;">'.htmlspecialchars($row['id_usuario']).'</td>
                <td>'.htmlspecialchars($row['nombre']).'</td>
                <td>'.htmlspecialchars($row['apellido1'].' '.$row['apellido2']).'</td>
                <td>'.htmlspecialchars($row['sucursal']).'</td>
                <td>'.htmlspecialchars($row['departamento']).'</td>
                <td style="text-align:center;">'.htmlspecialchars($row['codigo_incidencia']).'</td>
                <td>'.htmlspecialchars($row['descripcion_incidencia']).'</td>
                <td style="text-align:center;">'.htmlspecialchars($row['cantidad']).'</td>
                <td style="text-align:center;">'.htmlspecialchars($row['fecha_inicio']).'</td>
                <td style="text-align:center;">'.htmlspecialchars($row['fecha_termino']).'</td>
                <td style="text-align:right;">$'.number_format($row['descuento'], 2).'</td>
                <td style="text-align:right;">$'.number_format($descuento_total, 2).'</td>
              </tr>';
    }

    echo '<tr style="background-color: #f2f2f2;">
            <td colspan="11" style="text-align:right; font-weight:bold;">Total Descuento:</td>
            <td style="text-align:right; font-weight:bold;">$'.number_format($total_descuento, 2).'</td>
          </tr>
          </tbody>
        </table>';

    exit();
}



ob_end_flush();