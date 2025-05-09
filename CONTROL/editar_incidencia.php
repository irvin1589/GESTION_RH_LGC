<?php
ob_start();
include('../MODELO/CL_CONEXION.php');
include('../MODELO/CL_TABLA_DETALLE_INCIDENCIA.php');

session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

// Obtener el ID de la incidencia a editar
$id_incidencia = $_GET['id'] ?? 0;

$conexion = new CL_CONEXION();
$incidenciaModel = new CL_TABLA_DETALLE_INCIDENCIA();

// Obtener datos de la incidencia
$incidencia = $incidenciaModel->obtenerIncidenciaPorId($id_incidencia);

if (!$incidencia) {
    header('Location: PANEL_INCIDENCIAS.php?error=incidencia_no_encontrada');
    exit();
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_descuento = (float)$_POST['descuento'];
    
    if ($incidenciaModel->actualizarDescuento($id_incidencia, $nuevo_descuento)) {
        header('Location: PANEL_INCIDENCIAS.php?success=descuento_actualizado');
        exit();
    } else {
        $error = "Error al actualizar el descuento";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Descuento | LA GRAN CIUDAD RH</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 500px;
            margin: 30px auto;
            padding: 25px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .info-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            min-width: 120px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #1f3a54;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
        }
        .btn-primary {
            background-color: #1f3a54;
            color: white;
        }
        .btn-primary:hover {
            background-color: #142b41;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .error {
            color: #dc3545;
            padding: 10px;
            background-color: #f8d7da;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2><i class="fas fa-edit"></i> Editar Descuento</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="info-header">
            <div class="info-row">
                <span class="info-label">ID Incidencia:</span>
                <span><?php echo htmlspecialchars($incidencia['id_detalle_incidencia']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tipo:</span>
                <span><?php echo htmlspecialchars($incidencia['id_incidencia_tipo']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Cantidad:</span>
                <span><?php echo htmlspecialchars($incidencia['cantidad']); ?> días</span>
            </div>
            <div class="info-row">
                <span class="info-label">Fechas:</span>
                <span>
                    <?php echo htmlspecialchars($incidencia['fecha_inicio']); ?> 
                    a <?php echo htmlspecialchars($incidencia['fecha_termino']); ?>
                </span>
            </div>
        </div>
        
        <form method="post">
            <div class="form-group">
                <label for="id_usuario">Para percepciones anteponer un "-"</label>
                <label for="descuento">Descuento/Beneficio:</label>
                <input type="number" id="descuento" name="descuento" 
                       value="<?php echo htmlspecialchars($incidencia['descuento']); ?>" 
                       step="0.01" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Descuento
                </button>
                <a href="PANEL_INCIDENCIAS.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>