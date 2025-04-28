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
if (isset($_POST['click_ver_usuarios'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/VER_USUARIOS.php');
    exit();
}

if (isset($_POST['click_crear_formulario'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/CREAR_FORMULARIO.php');
    exit();
}

if (isset($_POST['click_crear_sucursal'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/CREAR_SUCURSAL.php');
    exit();
}

if (isset($_POST['click_crear_departamento'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/CREAR_DEPARTAMENTO.php');
    exit();
}

if (isset($_POST['click_crear_puesto'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/CREAR_PUESTO.php');
    exit();
}

if (isset($_POST['click_asignar_formulario'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/ASIGNAR_FORMULARIO.php');
    exit();
}

if (isset($_POST['click_evaluar'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/EVALUAR_FORMULARIO.php');
    exit();
}

if (isset($_POST['click_resultados'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/RESULTADOS.php');
    exit();
}

if (isset($_POST['click_cerrar_sesion'])) {
    session_start();
    session_unset();
    session_destroy();
    echo "Redirigiendo...";
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/SISTEMA_RH.php');
    exit();
}

if (isset($_POST['click_regresar'])) {
    header('Location: http://localhost/GESTION_RH_LGC/CONTROL/PANEL_ADMIN1.php');
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
        header("Location: http://localhost/GESTION_RH_LGC/CONTROL/EDITAR_SUCURSAL.php?id_sucursal=$id_sucursal");
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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-image: url('../IMG/DEPARTAMENTAL.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: rgba(31, 58, 84, 0.9); /* Fondo azul con transparencia */
            color: #ffffff;
            padding: 20px 30px;
            text-align: center;
        }

        .container {
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.5); /* Fondo blanco semitransparente */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 1200px;
        }

        .button-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* Cuatro columnas */
            gap: 20px; /* Espacio entre los botones */
            margin-top: 20px;
        }

        button {
            background-color: rgba(31, 58, 84, 0.6);
            color: #ffffff;
            border: 1px solid #003f7f;
            border-radius: 250px;
            padding: 15px 20px; /* Aumenta el tamaño de los botones */
            font-size: 18px; /* Aumenta el tamaño del texto de los botones */
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:active {
            background-color: #003f7f;
        }
        .logout-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .logout-button {
            background-color: #dc3545;
            border: 1px solid #dc3545;
            font-size: 16px; 
            padding: 10px 20px; 
            margin: 0px;
            width: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .logout-button:hover {
            background-color: #a71d2a;
        }

        .logout-button:active {
            background-color: #7f1420;
        }

        .sucursales-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .sucursales-table th,
        .sucursales-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .sucursales-table th {
            background-color: rgba(31, 58, 84, 0.9);
            color: white;
        }

        .sucursales-table tr:hover {
            background-color: rgba(31, 58, 84, 0.1);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            transition: transform 0.2s ease;
        }

        .action-buttons button:hover {
            transform: scale(1.2);
        }

        .action-buttons .edit i {
            color:  #007bff; 
            font-size: 18px; 
        }

        .action-buttons .delete i {
            color: #f44336; 
            font-size: 18px; 
        }

        .action-buttons .edit i:hover {
            color:  #007bff; 
        }

        .action-buttons .delete i:hover {
            color: #e53935;
        } 

        @media (max-width: 768px) {
       
        .sucursales-table th:nth-child(3),
        .sucursales-table th:nth-child(4),
        .sucursales-table td:nth-child(3),
        .sucursales-table td:nth-child(4) {
            display: none;
        }
    }
    @media (max-width: 768px) {
    .button-container {
        grid-template-columns: repeat(2, 1fr); /* Dos columnas en móvil */
    }
}
    .notification {
        position: fixed;
        top: 20%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(31, 58, 84, 0.9); 
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-align: center;
        z-index: 1000;
        color: white;
        display: none; 
    }

    .notification.success {
        background-color: rgba(31, 58, 84, 0.9); 
    }

    .notification.error {
        background-color: rgba(31, 58, 84, 0.9); 
    }

    .button-container button i {
    font-size: 60px; 
    margin-bottom: 8px; 
}

    .notification button {
        margin-top: 10px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #ffffff;
        color: #333333;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .notification button:hover {
        background-color: #dddddd;
    }
    </style>
</head>
<body>
    <div class="header">
        <h1>Panel de Desarrollo Organizacional</h1>
    </div>

    <div class="container">
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
            <div style="display: flex; justify-content: center; gap: 20px; margin-top: 30px;">
            <div class="logout-container">
                <button type="submit" name="click_cerrar_sesion" class="logout-button">
                    <i class="fas fa-sign-out-alt"></i>  Cerrar Sesión</button>
                <button type="submit" name="click_regresar" class="logout-button">
                <i class="fas fa-arrow-left"></i>  Regresar</button>
            </div>
            </div>
        </form>

        <div class="notification <?php echo $tipo_mensaje ?? ''; ?>" id="notification" style="display: <?php echo !empty($mensaje) ? 'block' : 'none'; ?>;">
            <p><?php echo $mensaje ?? ''; ?></p>
            <button onclick="closeNotification()">Cerrar</button>
        </div>

        <h2>Lista de Sucursales</h2>
        <table class="sucursales-table">
            <thead>
                <tr>
                    <th>ID Sucursal</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($sucursales)) {
                    foreach ($sucursales as $sucursal) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($sucursal['id_sucursal']) . "</td>";
                        echo "<td>" . htmlspecialchars($sucursal['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($sucursal['direccion']) . "</td>";
                        echo "<td>" . htmlspecialchars($sucursal['telefono']) . "</td>";
                        echo "<td class='action-buttons'>";
                        echo "<form method='POST' action='' style='display:inline;'>";
                        echo "<input type='hidden' name='id_sucursal' value='" . htmlspecialchars($sucursal['id_sucursal']) . "'>";
                        echo "<button type='submit' name='click_editar_sucursal' class='edit'><i class='fas fa-edit'></i></button>";
                        echo "</form>";
                        echo "<form method='POST' action='' style='display:inline;'>";
                        echo "<input type='hidden' name='id_sucursal' value='" . htmlspecialchars($sucursal['id_sucursal']) . "'>";
                        echo "<button type='submit' name='click_eliminar_sucursal' class='delete'><i class='fas fa-trash'></i></button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay sucursales registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        // Función para cerrar la notificación
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }
    </script>
</body>
</html>