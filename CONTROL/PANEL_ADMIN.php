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
    <link rel="stylesheet" href="../HTML/css/normalize.css">
    <style>

        html{
            font-size: 62.5%;
            box-sizing: border-box;
        }
        *, *::before, *::after{
            box-sizing: inherit;
        }
        body {
            font-family: Arial, sans-serif;
            font-style: normal;
            font-size: 2rem;
        }

        .header{
            background-color: #313131;
        }

        .header__contenedor{
            display: flex;
        }

        .header__titulo{
            color: #ffffff;
            font-size: 3.8rem;
            padding: 1rem 0;
            margin: auto;
            align-items: center;
            text-align: center;
            justify-content: center;
        }

        .header__img{
            width: 5rem;
            height: 5rem;
            margin: 1rem 0;
        }
        
        .contenedor{
            max-width: 120rem;
            text-align: center;
            margin: 0 auto;
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

    .button-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2rem;
        margin: 3rem 0;
    }

    .button-container button {
        background-color: #1f3a54;
        color: #fff;
        border: none;
        padding: 1.5rem 2rem;
        border-radius: 1rem;
        font-size: 1.6rem;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        min-width: 14rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .button-container button:hover {
        background-color: #2c577c;
        transform: translateY(-3px);
    }

    .button-container i {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    /* Botones de cerrar sesión y regresar */
    .logout-button {
        background-color: #444;
        color: white;
        padding: 1rem 2rem;
        font-size: 1.6rem;
        border: none;
        border-radius: 0.8rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .logout-button:hover {
        background-color: #666;
    }

    /* Tabla de sucursales */
    table.sucursales-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 4rem;
        font-size: 1.6rem;
    }

    table.sucursales-table th, table.sucursales-table td {
        border: 1px solid #ddd;
        padding: 1.2rem;
    }

    table.sucursales-table th {
        background-color: #1f3a54;
        color: white;
        text-align: center;
    }

    table.sucursales-table td {
        text-align: center;
        background-color: #f9f9f9;
    }

    table.sucursales-table tr:hover {
        background-color: #f1f1f1;
    }

    /* Botones de acción en la tabla */
    .action-buttons button {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 2rem;
        margin: 0 0.5rem;
        transition: transform 0.2s;
    }

    .action-buttons button.edit {
        color: #2c7;
    }

    .action-buttons button.delete {
        color: #d33;
    }

    .action-buttons button:hover {
        transform: scale(1.2);
    }

    h2 {
        font-size: 3rem;
        color: #1f3a54;
        margin-top: 3rem;
        text-align: center;
    }

    .footer {
        text-align: center;
    }
    </style>
</head>
<body>
<header class="header">
        <div class="contenedor header__contenedor">
            <img class="header__img" src="../IMG/logo-blanco-1.ico" alt="Nuestro logo">
            <h1 class="header__titulo">PANEL DESARROLLO ORGANIZACIONAL</h1>


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
    </main>
    <script>
        // Función para cerrar la notificación
        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }
    </script>


<!-- <footer>Todos los derechos reservados &copy; 2025</footer> -->
</body>
</html>