<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir las clases necesarias
include_once('../VISTA/CL_INTERFAZ02.php'); 
include_once('../MODELO/CL_TABLA_SUCURSAL.php');

$tablaSucursal = new CL_TABLA_SUCURSAL();
$sucursales = $tablaSucursal->listar_todo_sucursales(); // Obtener la lista de sucursales

$mensaje = "";
$tipo_mensaje = "";

session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); // O la página de inicio de sesión
    exit();
}

// Opcional: puedes validar el tipo de usuario también
if ($_SESSION['tipo_usuario'] !== 'RH') {
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
    header('Location: VER_USUARIOS.php');
    exit();
}

if (isset($_POST['click_crear_formulario'])) {
    header('Location: CREAR_FORMULARIO.php');
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
    echo "Redirigiendo...";
    header('Location: SISTEMA_RH.php');
    exit();
}

if (isset($_POST['click_regresar'])) {
    header('Location: PANEL_RH.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PANEL DESARROLLO ORGANIZACIONAL | LA GRAN CIUDAD</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../HTML/css/normalize.css">
    <style>
        /* Estilos generales */
        html {
            font-size: 62.5%;
            box-sizing: border-box;
        }
        *, *::before, *::after {
            box-sizing: inherit;
        }
        body {
            font-family: Arial, sans-serif;
            font-style: normal;
            font-size: 2rem;
            margin: 0;
            padding: 0;
            background-image: url('../IMG/fondo2.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* Header */
        .header {
            background-color: #313131;
            padding: 1rem 0;
            width: 100%;
        }

        .header__contenedor {
            max-width: 120rem;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header__logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header__img {
            width: 5rem;
            height: 5rem;
            transition: transform 0.3s ease;
        }

        .header__img:hover {
            transform: scale(1.1);
        }

        .header__titulo {
            color: #ffffff;
            font-size: 2.5rem;
            margin: 0;
            text-align: center;
            flex-grow: 1;
        }

        .boton-regresar {
            background-color: #941C82;
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 100px;
            cursor: pointer;
            font-size: 1.6rem;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .boton-regresar:hover {
            background-color: #641d59;
        }

        .boton-regresar i {
            font-size: 1.8rem;
        }

        @media (min-width: 479px){
        .button-container{
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
    }

    @media (min-width: 1024px) {
        .button-container{
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }
    
    }

        /* Media queries para responsive */
        @media (max-width: 768px) {
            .header__contenedor {
                flex-direction: column;
                padding: 1rem;
            }

            .header__logo {
                margin-bottom: 1rem;
            }

            .header__titulo {
                font-size: 2rem;
                margin: 1rem 0;
            }

            .header__img {
                width: 4rem;
                height: 4rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .header__titulo {
                font-size: 2.2rem;
            }
        }

        @media (min-width: 1025px) {
            .header__titulo {
                font-size: 2.8rem;
            }
        }

        .button-container button {
            background-color: #1f3a54;
            color: #fff;
            border: none;
            padding: 2rem;
            border-radius: 1rem;
            font-size: 2rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            min-width: 14rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            margin-bottom: 1rem;
        }

        .button-container button:hover {
            background-color: #2c577c;
            transform: translateY(-3px);
        }

        .button-container i {
            font-size: 4rem;
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
            background-color: rgba(249, 249, 249, 0.7);
        }

        table.sucursales-table tr:hover {
            background-color: rgba(241, 241, 241, 0.7);
        }

        /* Botones de acción en la tabla */
        .action-buttons {
            display: flex;
            justify-content: center;
        }
        
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

        .tabla-responsive {
            width: 100%;
            overflow-x: auto;
            margin-top: 2rem;
        }
        .contenedor {
            max-width: 120rem;
            margin: 2rem auto;
            padding: 2rem;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 1rem;
        }
        
        /* ... (mantén el resto de tus estilos igual) ... */
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
    </main>
</body>
</html>