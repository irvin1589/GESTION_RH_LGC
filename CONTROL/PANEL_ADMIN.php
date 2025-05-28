<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir las clases necesarias
include_once('../VISTA/CL_INTERFAZ02.php'); 
include_once('../MODELO/CL_TABLA_SUCURSAL.php');

$tablaSucursal = new CL_TABLA_SUCURSAL();
$sucursales = $tablaSucursal->listar_todo_sucursales(); 

$mensaje = "";
$tipo_mensaje = "";

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); 
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin') {
    header('Location: acceso_denegado.php');
    exit();
}

if (isset($_GET['mensaje'])) {
    if ($_GET['mensaje'] === 'success') {
        $mensaje = "Sucursal eliminada correctamente.";
        $tipo_mensaje = "success";
    } elseif ($_GET['mensaje'] === 'error') {
        $mensaje = "Error al eliminar la sucursal.";
        $tipo_mensaje = "error";
    }
}

// Resto de las funciones de redirección...
if (isset($_POST['click_ver_usuarios'])) {
    header('Location: VER_USUARIOS.php');
    exit();
}

if (isset($_POST['click_crear_formulario'])) {
    header('Location: CREAR_FORMULARIO.php');
    exit();
}

if (isset($_POST['click_crear_sucursal'])) {
    header('Location: CREAR_SUCURSAL.php');
    exit();
}

if (isset($_POST['click_crear_departamento'])) {
    header('Location: CREAR_DEPARTAMENTO.php');
    exit();
}

if (isset($_POST['click_crear_puesto'])) {
    header('Location: CREAR_PUESTO.php');
    exit();
}

if (isset($_POST['click_asignar_formulario'])) {
    header('Location: ASIGNAR_FORMULARIO.php');
    exit();
}

if (isset($_POST['click_evaluar'])) {
    header('Location: VER_FORMULARIOS.php');
    exit();
}

if (isset($_POST['click_resultados'])) {
    header('Location: VER_RESULTADOS.php');
    exit();
}

if (isset($_POST['click_cerrar_sesion'])) {
    session_start();
    session_unset();
    session_destroy();
    header('Location: SISTEMA_RH.php');
    exit();
}

if (isset($_POST['click_regresar'])) {
    header('Location: PANEL_ADMIN1.php');
    exit();
}

if (isset($_POST['click_eliminar_sucursal'])) {
    $id_sucursal = $_POST['id_sucursal'] ?? '';

    if (!empty($id_sucursal)) {
        $resultado = $tablaSucursal->eliminar_sucursal($id_sucursal);
        if ($resultado) {
            header("Location: PANEL_ADMIN.php?mensaje=success");
            exit();
        } else {
            header("Location: PANEL_ADMIN.php?mensaje=error");
            exit();
        }
    }
}

if (isset($_POST['click_editar_sucursal'])) {
    $id_sucursal = $_POST['id_sucursal'] ?? '';

    if (!empty($id_sucursal)) {
        header("Location: EDITAR_SUCURSAL.php?id_sucursal=$id_sucursal");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PANEL ADMINISTRADOR | LA GRAN CIUDAD</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1f3a54;
            --secondary-color: #941C82;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --danger-color: #dc3545;
            --success-color: #28a745;
        }

        html {
            font-size: 62.5%;
            box-sizing: border-box;
        }
        
        * {
            box-sizing: inherit;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 1.6rem;
            background-image: url('../IMG/fondo2.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #333;
        }

        /* Header */
        .header {
            background-color: #313131;
            padding: 1rem;
        }

        .header__contenedor {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header__titulo {
            color: #ffffff;
            font-size: 2.5rem;
            text-align: center;
            flex-grow: 1;
        }

        .header__img {
            width: 4rem;
            height: 4rem;
            transition: transform 0.3s ease;
        }

        .header__img:hover {
            transform: scale(1.1);
        }

        .boton-regresar {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 100px;
            cursor: pointer;
            font-size: 1.6rem;
            transition: background-color 0.3s;
        }

        .boton-regresar:hover {
            background-color: #641d59;
        }

        /* Contenedor principal */
        .contenedor {
            max-width: 120rem;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Botones de acción */
        .button-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin: 3rem 0;
        }

        @media (min-width: 768px) {
            .button-container {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .button-container button {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            padding: 2rem;
            border-radius: 1rem;
            font-size: 1.6rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 8rem;
        }

        .button-container button:hover {
            background-color: #2c577c;
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .button-container i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        /* Notificaciones */
        .notification {
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(31, 58, 84, 0.9);
            padding: 2rem 3rem;
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            text-align: center;
            z-index: 1000;
            color: white;
            display: none;
        }

        .notification button {
            margin-top: 1rem;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            background-color: #ffffff;
            color: #333333;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .notification button:hover {
            background-color: #dddddd;
        }

        /* Tabla responsiva */
        .tabla-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 3rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 0.8rem;
            background-color: white;
        }

        .sucursales-table {
            width: 100%;
            min-width: 600px;
            border-collapse: collapse;
            font-size: 1.4rem;
        }

        .sucursales-table th,
        .sucursales-table td {
            padding: 1.2rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .sucursales-table th {
            background:  #1f3a54;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 1.9rem;
            position: sticky;
            top: 0;
        }

        .sucursales-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .sucursales-table tr:hover {
            background-color: #f1f1f1;
        }

        /* Acciones */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .action-buttons button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.6rem;
            padding: 0.5rem;
            border-radius: 0.4rem;
            transition: all 0.2s;
        }

        .action-buttons button.edit {
            color: var(--success-color);
            background-color: rgba(40, 167, 69, 0.1);
        }

        .action-buttons button.delete {
            color: var(--danger-color);
            background-color: rgba(220, 53, 69, 0.1);
        }

        .action-buttons button:hover {
            transform: scale(1.1);
        }

        /* Títulos */
        h2 {
            font-size: 2.4rem;
            color: var(--primary-color);
            margin: 2rem 0;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header__titulo {
                font-size: 2rem;
            }
            
            .sucursales-table th,
            .sucursales-table td {
                padding: 1rem;
                font-size: 1.3rem;
            }
            
            .sucursales-table .col-telefono {
                display: none;
            }
            
            .action-buttons button {
                font-size: 1.8rem;
                padding: 0.6rem;
            }
        }
        
        @media (max-width: 576px) {
            .header__contenedor {
                flex-direction: column;
                gap: 1rem;
            }
            
            .header__titulo {
                font-size: 1.8rem;
                order: -1;
            }
            
            .button-container {
                grid-template-columns: 1fr;
            }
            
            .sucursales-table .col-direccion {
                display: none;
            }
            
            .sucursales-table th,
            .sucursales-table td {
                padding: 0.8rem;
                font-size: 1.2rem;
            }
            
            .tabla-responsive::after {
                content: "Desliza para ver más columnas →";
                display: block;
                text-align: center;
                padding: 0.8rem;
                font-size: 1.2rem;
                color: #666;
                background-color: #f5f5f5;
                border-top: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header__contenedor">
            <form method="post" style="display: inline;">
                <button type="submit" name="click_regresar" style="background: none; border: none; padding: 0; cursor: pointer;">
                    <img class="header__img" src="../IMG/logo-blanco-1.ico" alt="Regresar">
                </button>
            </form>
            
            <h1 class="header__titulo">PANEL DESARROLLO ORGANIZACIONAL</h1>

            <form method="post">
                <button type="submit" name="click_cerrar_sesion" class="boton-regresar">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </header>

    <main class="contenedor">
        <!-- Botones de acciones -->
        <form method="POST" action="">
            <div class="button-container">
                <button type="submit" name="click_crear_formulario">
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <i class="fas fa-file-alt"></i>
                        <span>Crear Formulario</span>
                    </div>
                </button>
                <button type="submit" name="click_crear_sucursal">
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <i class="fas fa-building"></i>
                        <span>Crear Sucursal</span>
                    </div>
                </button>
                <button type="submit" name="click_crear_departamento">
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <i class="fas fa-sitemap"></i>
                        <span>Crear Departamento</span>
                    </div>
                </button>
                <button type="submit" name="click_crear_puesto">
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <i class="fas fa-briefcase"></i>
                        <span>Crear Puesto</span>
                    </div>
                </button> 
                <button type="submit" name="click_asignar_formulario">
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <i class="fas fa-share-square"></i>
                        <span>Asignar Formulario</span>  
                    </div>
                </button>
                <button type="submit" name="click_evaluar">
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <i class="fas fa-check"></i>
                        <span>Evaluar Formulario</span>
                    </div>
                </button>
                <button type="submit" name="click_resultados">
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <i class="fas fa-chart-pie"></i>
                        <span>Resultados</span>
                    </div>
                </button>
                <button type="submit" name="click_ver_usuarios">
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <i class="fas fa-users"></i>
                        <span>Ver Usuarios</span>
                    </div>
                </button>
            </div>
        </form>

        <div class="notification <?php echo $tipo_mensaje ?? ''; ?>" id="notification" style="display: <?php echo !empty($mensaje) ? 'block' : 'none'; ?>;">
            <p><?php echo $mensaje ?? ''; ?></p>
            <button onclick="closeNotification()">Cerrar</button>
        </div>

        <h2>Lista de Sucursales</h2>
        <div class="tabla-responsive">
            <table class="sucursales-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th class="col-direccion">Dirección</th>
                        <th class="col-telefono">Teléfono</th>
                        <th>Acciones</th>
                    </tr>   
                </thead>
                <tbody>
                    <?php if (!empty($sucursales)): ?>
                        <?php foreach ($sucursales as $sucursal): ?>
                            <tr>
                                <td><?= htmlspecialchars($sucursal['id_sucursal']) ?></td>
                                <td><?= htmlspecialchars($sucursal['nombre']) ?></td>
                                <td class="col-direccion"><?= htmlspecialchars($sucursal['direccion']) ?></td>
                                <td class="col-telefono"><?= htmlspecialchars($sucursal['telefono']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="id_sucursal" value="<?= htmlspecialchars($sucursal['id_sucursal']) ?>">
                                            <button type="submit" name="click_editar_sucursal" class="edit" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </form>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="id_sucursal" value="<?= htmlspecialchars($sucursal['id_sucursal']) ?>">
                                            <button type="submit" name="click_eliminar_sucursal" class="delete" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No hay sucursales registradas</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
        
    <script>
        // Función para cerrar la notificación
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }
        
        // Cerrar notificación automáticamente después de 5 segundos
        setTimeout(() => {
            closeNotification();
        }, 5000);
    </script>
</body>
</html>