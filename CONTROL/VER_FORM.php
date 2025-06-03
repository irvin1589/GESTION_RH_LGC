<?php
require_once '../MODELO/CL_CONEXION.php';
require_once '../MODELO/CL_TABLA_FORMULARIO.php';

if (!isset($_GET['id_asignacion'])) {
    echo "Formulario no especificado.";
    exit;
}

session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

$idAsignacion = $_GET['id_asignacion'];
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

$stmt = $pdo->prepare("SELECT id_formulario FROM asignacion_formulario WHERE id_asignacion = ?");
$stmt->execute([$idAsignacion]);
$formulario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$formulario) {
    echo "Asignación no encontrada.";
    exit;
}

$idFormulario = $formulario['id_formulario'];

$t_formulario = new CL_TABLA_FORMULARIO();
$datosFormulario = $t_formulario->obtener_formulario_por_id($idFormulario);

$stmt = $pdo->prepare("SELECT * FROM pregunta WHERE id_formulario = ? ORDER BY orden");
$stmt->execute([$idFormulario]);
$preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT id_pregunta, respuesta FROM respuesta WHERE id_asignacion = ?");
$stmt->execute([$idAsignacion]);
$respuestas = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO CONTESTADO | LA GRAN CIUDAD RH</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        :root {
            --primary-color: #00b4d8;
            --primary-dark: #0077b6;
            --background-light: #f8f9fa;
            --text-dark: #343a40;
            --text-light: #6c757d;
            --white: #ffffff;
            --success: #28a745;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Montserrat', sans-serif;
            background-color: var(--background-light);
            color: var(--text-dark);
            line-height: 1.6;
            padding: 20px;
        }

        .contenedor-principal {
            max-width: 800px;
            margin: 30px auto;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }

        .encabezado-formulario {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            color: var(--white);
            padding: 25px 30px;
            text-align: center;
        }

        .encabezado-formulario h2 {
            font-size: 1.8rem;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .encabezado-formulario h3 {
            font-size: 1.1rem;
            font-weight: 400;
            opacity: 0.9;
        }

        .contenido-formulario {
            padding: 30px;
        }

        .pregunta {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0, 180, 216, 0.1);
        }

        .pregunta:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .pregunta label {
            display: block;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--primary-dark);
            margin-bottom: 12px;
            position: relative;
            padding-left: 25px;
        }

        .pregunta label:before {
            content: '\f059';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 2px;
            color: var(--primary-color);
            font-size: 0.9em;
        }

        .respuesta {
            background-color: rgba(0, 180, 216, 0.05);
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            margin-left: 25px;
            transition: var(--transition);
        }

        .respuesta:hover {
            background-color: rgba(0, 180, 216, 0.1);
        }

        .no-respondida {
            color: var(--text-light);
            font-style: italic;
        }

        .boton-volver {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: var(--primary-color);
            color: var(--white);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 500;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .boton-volver:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 180, 216, 0.3);
        }

        .boton-volver i {
            margin-right: 8px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .contenedor-principal {
                margin: 15px;
            }
            
            .encabezado-formulario {
                padding: 20px;
            }
            
            .encabezado-formulario h2 {
                font-size: 1.5rem;
            }
            
            .contenido-formulario {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="contenedor-principal">
    <div class="encabezado-formulario">
        <h2><?= htmlspecialchars($datosFormulario['nombre']) ?></h2>
        <h3><?= htmlspecialchars($datosFormulario['descripcion']) ?></h3>
    </div>
    
    <div class="contenido-formulario">
        <?php foreach ($preguntas as $pregunta): ?>
            <div class="pregunta">
                <label><?= htmlspecialchars($pregunta['texto']) ?></label>
                <div class="respuesta">
                    <?= isset($respuestas[$pregunta['id_pregunta']]) 
                            ? htmlspecialchars($respuestas[$pregunta['id_pregunta']]) 
                            : '<span class="no-respondida">No respondida</span>' ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <button class="boton-volver" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i> Volver
        </button>
    </div>
</div>
</body>
</html>
