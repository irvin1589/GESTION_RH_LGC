<?php
include_once('../MODELO/CL_TABLA_ASIGNACION_FORMULARIO.php');
include_once('../MODELO/CL_TABLA_FORMULARIO.php');
include_once('../MODELO/CL_TABLA_USUARIO.php');

$tablaAsignacion = new CL_TABLA_ASIGNACION_FORMULARIO();

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); // O la página de inicio de sesión
    exit();
}

// Permitir acceso a Admin y RH
if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

// Manejar la eliminación de una asignación
if (isset($_POST['eliminar_asignacion'])) {
    $id_asignacion = $_POST['id_asignacion'];
    $resultado = $tablaAsignacion->eliminar_asignacion($id_asignacion);

    if ($resultado) {
        echo '<div class="notification success">Asignación eliminada exitosamente.</div>';
    } else {
        echo '<div class="notification error">Error al eliminar la asignación. Por favor, inténtelo de nuevo.</div>';
    }
}

// Obtener la lista de asignaciones
$asignaciones = $tablaAsignacion->listar_asignaciones();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VER ASIGNACIONES | LA GRAN CIUDAD</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1f3a54;
            --secondary-color: #941C82;
            --accent-color: #2c577c;
            --danger-color: #e74c3c;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --gray-color: #6c757d;
            --border-radius: 8px;
            --box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: var(--dark-color);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .header h2 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 10px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .header h2 i {
            color: var(--secondary-color);
        }

        .table-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow-x: auto;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #1f3a54;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-pending {
            background-color: rgba(var(--warning-color), 0.1);
            color: var(--warning-color);
        }

        .status-completed {
            background-color: rgba(var(--success-color), 0.1);
            color: var(--success-color);
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: transparent;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            color: var(--gray-color);
        }

        .action-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: scale(1.1);
        }

        .delete-btn {
            color: var(--danger-color);
        }

        .delete-btn:hover {
            background-color: rgba(var(--danger-color), 0.1);
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            margin-top: 20px;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            max-width: 90%;
            width: 400px;
            padding: 15px 20px;
            border-radius: var(--border-radius);
            font-family: 'Segoe UI', sans-serif;
            text-align: center;
            box-shadow: var(--box-shadow);
            display: block;
            opacity: 0.95;
            animation: fadeIn 0.5s, fadeOut 0.5s 2.5s forwards;
        }

        .notification.success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 6px solid #2e7d32;
        }

        .notification.error {
            background-color: #ffebee;
            color: #c62828;
            border-left: 6px solid #c62828;
        }

        @keyframes fadeIn {
            from { opacity: 0; top: 0; }
            to { opacity: 0.95; top: 20px; }
        }

        @keyframes fadeOut {
            from { opacity: 0.95; top: 20px; }
            to { opacity: 0; top: 0; }
        }

        @media (max-width: 768px) {
            th, td {
                padding: 10px 8px;
                font-size: 0.85rem;
            }
            
            .header h2 {
                font-size: 1.5rem;
            }
        }

        /* Estilos para el botón de eliminar en formulario */
        form {
            display: inline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-tasks"></i> LISTA DE ASIGNACIONES</h2>
        </div>

        <?php if (isset($resultado) && $resultado): ?>
            <div class="notification success">Asignación eliminada exitosamente.</div>
        <?php elseif (isset($resultado) && !$resultado): ?>
            <div class="notification error">Error al eliminar la asignación. Por favor, inténtelo de nuevo.</div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Formulario</th>
                        <th>Usuario</th>
                        <th>Fecha Asignación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($asignaciones as $asignacion): ?>
                        <tr>
                            <td><?= htmlspecialchars($asignacion['id_asignacion']) ?></td>
                            <td><?= htmlspecialchars($asignacion['nombre_formulario']) ?></td>
                            <td><?= htmlspecialchars($asignacion['nombre_usuario']) ?></td>
                            <td><?= htmlspecialchars($asignacion['fecha_asignacion']) ?></td>
                            <td>
                                <span class="status <?= $asignacion['completado'] ? 'status-completed' : 'status-pending' ?>">
                                    <?= $asignacion['completado'] ? 'Completado' : 'Pendiente' ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="VER_ASIGNACIONES.php">
                                    <input type="hidden" name="id_asignacion" value="<?= htmlspecialchars($asignacion['id_asignacion']) ?>">
                                    <button type="submit" name="eliminar_asignacion" class="action-btn delete-btn" title="Eliminar asignación">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <form method="POST" action="../HTML/form_08.php">
            <button type="submit" name="regresar" class="back-btn">
                <i class="fas fa-arrow-left"></i> REGRESAR
            </button>
        </form>
    </div>

    <script>
        // Cerrar notificaciones automáticamente
        setTimeout(() => {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                notification.style.display = 'none';
            });
        }, 3000);
    </script>
</body>
</html>