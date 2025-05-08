<?php
include_once('../MODELO/CL_TABLA_USUARIO.php');
include_once('../MODELO/CL_TABLA_DEPARTAMENTO.php');
include_once('../MODELO/CL_TABLA_PUESTO.php');
include_once('../MODELO/CL_TABLA_SUCURSAL.php');

$tablaUsuario = new CL_TABLA_USUARIO();
$tablaDepartamento = new CL_TABLA_DEPARTAMENTO();
$tablaPuesto = new CL_TABLA_PUESTO();
$tablaSucursal = new CL_TABLA_SUCURSAL();

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

// Procesar eliminación de usuario
if (isset($_POST['eliminar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $resultado = $tablaUsuario->eliminar_usuario($id_usuario);
    if ($resultado) {
        header("Location: VER_USUARIOS.php?msg=success");
        exit();
    } else {
        header("Location: VER_USUARIOS.php?msg=error");
        exit();
    }
}

// Redirecciones
if (isset($_POST['click_registrar'])) {
    header('Location: CONTROL_REGISTRO.php');
    exit();
}

if (isset($_POST['regresar'])) {
    $redirect = ($_SESSION['tipo_usuario'] === 'Admin') ? '../CONTROL/PANEL_ADMIN.php' : '../CONTROL/PANEL_DESARROLLO.php';
    header("Location: $redirect");
    exit();
}

$usuarios = $tablaUsuario->listar_todos_los_usuarios_con_detalles();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTIÓN DE USUARIOS | LA GRAN CIUDAD</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1f3a54;
            --secondary-color: #941C82;
            --accent-color: #2c577c;
            --danger-color: #e74c3c;
            --success-color: #28a745;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
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
            background-image: url('../IMG/DEPARTAMENTAL.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--dark-color);
            min-height: 100vh;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(31, 58, 84, 0.7);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .header h2 {
            font-size: 2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
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
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background:  #1f3a54;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
        }

        tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-action {
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
        }

        .btn-edit {
            color: var(--primary-color);
        }

        .btn-edit:hover {
            background-color: rgba(31, 58, 84, 0.1);
            transform: scale(1.1);
        }

        .btn-delete {
            color: var(--danger-color);
        }

        .btn-delete:hover {
            background-color: rgba(231, 76, 60, 0.1);
            transform: scale(1.1);
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-secondary:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .header h2 {
                font-size: 1.5rem;
            }
            
            th, td {
                padding: 8px 10px;
                font-size: 0.8rem;
            }
            
            .col-departamento,
            .col-puesto,
            .col-apellido2,
            .col-contra,
            .col-tipo {
                display: none;
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
    <div class="overlay"></div>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-users"></i> GESTIÓN DE USUARIOS</h2>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'success'): ?>
                <div class="notification success">Usuario eliminado correctamente.</div>
            <?php elseif ($_GET['msg'] == 'error'): ?>
                <div class="notification error">Error al eliminar el usuario. Inténtalo de nuevo.</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="col-sucursal">Sucursal</th>
                        <th class="col-departamento">Departamento</th>
                        <th class="col-puesto">Puesto</th>
                        <th class="col-id">ID</th>
                        <th class="col-nombre">Nombre</th>
                        <th class="col-apellido1">Apellido Paterno</th>
                        <th class="col-apellido2">Apellido Materno</th>
                        <th class="col-tipo">Tipo</th>
                        <th class="col-acciones">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td class="col-sucursal"><?= htmlspecialchars($usuario['nombre_sucursal']) ?></td>
                            <td class="col-departamento"><?= htmlspecialchars($usuario['nombre_departamento']) ?></td>
                            <td class="col-puesto"><?= htmlspecialchars($usuario['nombre_puesto']) ?></td>
                            <td class="col-id"><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                            <td class="col-nombre"><?= htmlspecialchars($usuario['nombre']) ?></td>
                            <td class="col-apellido1"><?= htmlspecialchars($usuario['apellido1']) ?></td>
                            <td class="col-apellido2"><?= htmlspecialchars($usuario['apellido2']) ?></td>
                            <td class="col-tipo"><?= htmlspecialchars($usuario['tipo_usuario']) ?></td>
                            <td class="col-acciones">
                                <div class="action-buttons">
                                    <form method="GET" action="EDITAR_USUARIO.php">
                                        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
                                        <button type="submit" class="btn-action btn-edit" title="Editar usuario">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="VER_USUARIOS.php">
                                        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
                                        <button type="submit" name="eliminar_usuario" class="btn-action btn-delete" title="Eliminar usuario">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="button-group">
            <form method="POST" action="VER_USUARIOS.php">
            <button type="submit" name="click_registrar" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> REGISTRAR NUEVO
                </button>
                <button type="submit" name="regresar" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> REGRESAR
                </button>
            </form>
        </div>
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