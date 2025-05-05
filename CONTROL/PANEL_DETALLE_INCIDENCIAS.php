<?php
ob_start();
include('../MODELO/CL_CONEXION.php');

// Mostrar formulario de fechas, sucursal y departamento
if (!isset($_GET['fecha_inicio']) || !isset($_GET['fecha_fin']) || !isset($_GET['id_sucursal']) || !isset($_GET['id_departamento'])) {
    // Obtener sucursales y departamentos disponibles
    $connexion = new CL_CONEXION();
    $conn = $connexion->conectar();

    // Obtener todas las sucursales
    $query_sucursales = "SELECT * FROM SUCURSAL";
    $stmt_sucursales = $conn->prepare($query_sucursales);
    $stmt_sucursales->execute();
    $sucursales = $stmt_sucursales->fetchAll(PDO::FETCH_ASSOC);

    // Obtener todos los departamentos
    $query_departamentos = "SELECT * FROM DEPARTAMENTO";
    $stmt_departamentos = $conn->prepare($query_departamentos);
    $stmt_departamentos->execute();
    $departamentos = $stmt_departamentos->fetchAll(PDO::FETCH_ASSOC);

    ob_start();

    echo '<form method="get">
            <label>Fecha inicio: <input type="date" name="fecha_inicio" required></label>
            <label>Fecha fin: <input type="date" name="fecha_fin" required></label>
            <label>Sucursal: 
                <select name="id_sucursal" required>';
                foreach ($sucursales as $sucursal) {
                    echo "<option value='" . $sucursal['id_sucursal'] . "'>" . htmlspecialchars($sucursal['nombre']) . "</option>";
                }
    echo    '</select>
            </label>
            <label>Departamento: 
                <select name="id_departamento" required>';
                foreach ($departamentos as $departamento) {
                    echo "<option value='" . $departamento['id_departamento'] . "'>" . htmlspecialchars($departamento['nombre']) . "</option>";
                }
    echo    '</select>
            </label>
            <button type="submit">Generar reporte</button>
          </form>';
    exit;
}

$fecha_inicio = $_GET['fecha_inicio'];
$fecha_fin = $_GET['fecha_fin'];
$id_sucursal = $_GET['id_sucursal'];
$id_departamento = $_GET['id_departamento'];

// Validación básica
if ($fecha_inicio > $fecha_fin) {
    echo "La fecha de inicio no puede ser mayor que la fecha de fin.";
    exit;
}

$connexion = new CL_CONEXION();
$conn = $connexion->conectar();

// Modificar consulta para incluir filtro por sucursal y departamento
$query = "
    SELECT 
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
    FROM DETALLE_INCIDENCIA di
    JOIN USUARIO u ON di.id_usuario = u.id_usuario
    JOIN SUCURSAL s ON u.id_sucursal = s.id_sucursal
    JOIN DEPARTAMENTO d ON u.id_departamento = d.id_departamento
    JOIN INCIDENCIA_TIPO it ON di.id_incidencia_tipo = it.id_incidencia_tipo
    JOIN INCIDENCIA i ON it.codigo_incidencia = i.codigo
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

echo '
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DETALLE INCIDENCIA | LA GRAN CIUDAD RH</title>
  <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />';
echo "<h2>Reporte de Incidencias desde $fecha_inicio hasta $fecha_fin</h2>";

echo '
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        font-family: Arial, sans-serif;
    }
    th, td {
        border: 1px solid #cccccc;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #007BFF;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    h2 {
        font-family: Arial, sans-serif;
        color: #333333;
    }
    form {
        margin-top: 20px;
    }
    button {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
        background-color: #218838;
    }
</style>';

if ($stmt->rowCount() > 0) {
    echo "<table border='1'>
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Apellido 1</th>
                    <th>Apellido 2</th>
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
            <tbody>";

    $total_descuento = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Calcular el descuento total por incidencia (descuento * cantidad)
        $descuento_total = $row['descuento'] * $row['cantidad'];

        // Acumular el total descuento
        $total_descuento += $descuento_total;

        echo "<tr>
                <td>" . htmlspecialchars($row['id_usuario']) . "</td>
                <td>" . htmlspecialchars($row['nombre']) . "</td>
                <td>" . htmlspecialchars($row['apellido1']) . "</td>
                <td>" . htmlspecialchars($row['apellido2']) . "</td>
                <td>" . htmlspecialchars($row['sucursal']) . "</td>
                <td>" . htmlspecialchars($row['departamento']) . "</td>
                <td>" . htmlspecialchars($row['codigo_incidencia']) . "</td>
                <td>" . htmlspecialchars($row['descripcion_incidencia']) . "</td>
                <td>" . htmlspecialchars($row['cantidad']) . "</td>
                <td>" . htmlspecialchars($row['fecha_inicio']) . "</td>
                <td>" . htmlspecialchars($row['fecha_termino']) . "</td>
                <td>$" . number_format($row['descuento'], 2) . "</td> 
                <td>$" . number_format($descuento_total, 2) . "</td>
              </tr>";
    }

    // Mostrar el total de descuentos al final
    echo "<tr>
            <td colspan='12' style='text-align:right; font-weight:bold;'>Total Descuento:</td>
            <td style='font-weight:bold;'>$" . number_format($total_descuento, 2) . "</td>
          </tr>";

    echo "</tbody></table>";

    // Botón de "Siguiente"
    echo '<form method="post">
            <button type="submit" name="click_regresar">Regresar</button>
          </form>';

    echo '<form method="post">
                <button type="submit" name="exportar_excel">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </button>
            </form>';

} else {
    echo "No se encontraron incidencias en el rango de fechas seleccionado.";
}

$conn = null;

if (isset($_POST['click_regresar'])) {
    ob_clean(); // Limpia el buffer de salida
    header('Location: ../CONTROL/PANEL_INCIDENCIAS.php');
    exit();
}

if (isset($_POST['exportar_excel'])) {
    ob_clean(); // Limpia el buffer de salida

    $nombre_archivo = "reporte_incidencias_" . $fecha_inicio . "_a_" . $fecha_fin . ".xls";

    // Configuración de las cabeceras para la descarga del archivo Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$nombre_archivo");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Generar el contenido del archivo Excel
    echo "<table border='1'>
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Apellido 1</th>
                    <th>Apellido 2</th>
                    <th>Sucursal</th>
                    <th>Departamento</th>
                    <th>Codigo Incidencia</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Termino</th>
                    <th>Descuento</th>
                    <th>Descuento Total</th>
                </tr>
            </thead>
            <tbody>";

    $total_descuento = 0;

    // Reejecutar la consulta para obtener los datos
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $descuento_total = $row['descuento'] * $row['cantidad'];
        $total_descuento += $descuento_total;

        echo "<tr>
                <td>" . htmlspecialchars($row['id_usuario']) . "</td>
                <td>" . htmlspecialchars($row['nombre']) . "</td>
                <td>" . htmlspecialchars($row['apellido1']) . "</td>
                <td>" . htmlspecialchars($row['apellido2']) . "</td>
                <td>" . htmlspecialchars($row['sucursal']) . "</td>
                <td>" . htmlspecialchars($row['departamento']) . "</td>
                <td>" . htmlspecialchars($row['codigo_incidencia']) . "</td>
                <td>" . htmlspecialchars($row['descripcion_incidencia']) . "</td>
                <td>" . htmlspecialchars($row['cantidad']) . "</td>
                <td>" . htmlspecialchars($row['fecha_inicio']) . "</td>
                <td>" . htmlspecialchars($row['fecha_termino']) . "</td>
                <td>$" . number_format($row['descuento'], 2) . "</td>
                <td>$" . number_format($descuento_total, 2) . "</td>
              </tr>";
    }

    echo "<tr>
            <td colspan='12' style='text-align:right; font-weight:bold;'>Total Descuento:</td>
            <td style='font-weight:bold;'>$" . number_format($total_descuento, 2) . "</td>
          </tr>";

    echo "</tbody></table>";

    exit(); // Finaliza el script aquí
}

ob_end_flush();