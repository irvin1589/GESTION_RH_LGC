<?php
require_once '../MODELO/CL_CONEXION.php';
require_once '../MODELO/CL_TABLA_FORMULARIO.php';

session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if (!isset($_GET['id_asignacion'])) {
    echo "Formulario no especificado.";
    exit;
}


$idAsignacion = $_GET['id_asignacion'];
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

// Obtener el id_formulario desde la tabla ASIGNACION_FORMULARIO
$stmt = $pdo->prepare("SELECT id_formulario FROM asignacion_formulario WHERE id_asignacion = ?");
$stmt->execute([$idAsignacion]);
$formulario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$formulario) {
    echo "Asignación no encontrada.";
    exit;
}

$idFormulario = $formulario['id_formulario'];

$t_formulario = new CL_TABLA_FORMULARIO();
$formulario = $t_formulario->obtener_formulario_por_id($idFormulario);

// Obtener preguntas del formulario
$stmt = $pdo->prepare("SELECT * FROM pregunta WHERE id_formulario = ? ORDER BY orden");
$stmt->execute([$idFormulario]);
$preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO | LA GRAN CIUDAD RH</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        :root {
            --primary-color: #00b4d8;
            --primary-dark: #0077b6;
            --primary-light: #90e0ef;
            --danger: #dc3545;
            --danger-dark: #c82333;
            --background-light: #f8f9fa;
            --text-dark: #343a40;
            --text-light: #6c757d;
            --white: #ffffff;
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
            font-family: 'Montserrat', 'Segoe UI', sans-serif;
            background-color: var(--background-light);
            color: var(--text-dark);
            line-height: 1.6;
            padding: 20px;
        }

        .contenedor-principal {
            max-width: 800px;
            margin: 30px auto;
            animation: fadeIn 0.5s ease-out;
        }

        .encabezado-formulario {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            color: var(--white);
            padding: 25px 30px;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            text-align: center;
            box-shadow: var(--box-shadow);
        }

        .encabezado-formulario h2 {
            font-size: 1.8rem;
            margin-bottom: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .encabezado-formulario h3 {
            font-size: 1.1rem;
            font-weight: 400;
            opacity: 0.9;
        }

        .formulario-evaluacion {
            background: var(--white);
            padding: 30px;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .grupo-pregunta {
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(0, 180, 216, 0.1);
        }

        .grupo-pregunta:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .grupo-pregunta label.titulo-pregunta {
            display: block;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
            position: relative;
            padding-left: 30px;
        }

        .grupo-pregunta label.titulo-pregunta:before {
            content: '\f059';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 2px;
            color: var(--primary-color);
            font-size: 1.1em;
        }

        .opcion-respuesta {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding: 10px;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .opcion-respuesta:hover {
            background-color: rgba(0, 180, 216, 0.05);
        }

        .opcion-respuesta input[type="radio"] {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid var(--primary-light);
            border-radius: 50%;
            margin-right: 15px;
            cursor: pointer;
            position: relative;
            transition: var(--transition);
        }

        .opcion-respuesta input[type="radio"]:checked {
            border-color: var(--primary-color);
        }

        .opcion-respuesta input[type="radio"]:checked::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 10px;
            height: 10px;
            background-color: var(--primary-color);
            border-radius: 50%;
        }

        .opcion-respuesta label {
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
            flex-grow: 1;
        }

        .campo-texto {
            width: calc(100% - 20px);
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            margin-left: 30px;
        }

        .campo-texto:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.2);
            width: calc(100% - 60px);
        }

        .boton-accion {
            display: block;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 30px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .boton-enviar {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .boton-enviar:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 180, 216, 0.3);
        }

        .boton-regresar {
            background-color: var(--danger);
            color: var(--white);
        }

        .boton-regresar:hover {
            background-color: var(--danger-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
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
            
            .formulario-evaluacion {
                padding: 20px;
            }
            
            .grupo-pregunta label.titulo-pregunta {
                padding-left: 25px;
                font-size: 1rem;
            }
            
            .campo-texto {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="contenedor-principal">
    <div class="encabezado-formulario">
        <h2><?= htmlspecialchars($formulario['nombre']) ?></h2>
        <h3><?= htmlspecialchars($formulario['descripcion']) ?></h3>
    </div>
    
    <form class="formulario-evaluacion" action="guardar_respuestas.php" method="post">
        <input type="hidden" name="id_asignacion" value="<?= $idAsignacion ?>">
        
        <?php foreach ($preguntas as $pregunta): ?>
            <div class="grupo-pregunta">
                <label class="titulo-pregunta"><?= htmlspecialchars($pregunta['texto']) ?></label>

                <?php
                $stmtOp = $pdo->prepare("SELECT * FROM opcion_pregunta WHERE id_pregunta = ?");
                $stmtOp->execute([$pregunta['id_pregunta']]);
                $opciones = $stmtOp->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php if ($opciones): ?>
                    <?php foreach ($opciones as $opcion): ?>
                        <div class="opcion-respuesta">
                            <input type="radio" 
                                   name="respuesta_<?= $pregunta['id_pregunta'] ?>" 
                                   value="<?= htmlspecialchars($opcion['valor_opcion']) ?>" 
                                   id="opcion_<?= $opcion['id_opcion'] ?>">
                            <label for="opcion_<?= $opcion['id_opcion'] ?>">
                                <?= htmlspecialchars($opcion['texto_opcion']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <input type="text" 
                           class="campo-texto" 
                           name="respuesta_<?= $pregunta['id_pregunta'] ?>" 
                           placeholder="Escribe tu respuesta aquí...">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        
        <?php if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH'): ?>
            <button type="submit" class="boton-accion boton-enviar">
                <i class="fas fa-paper-plane"></i> Enviar respuestas
            </button>
        <?php else: ?>
            <button type="button" 
                    class="boton-accion boton-regresar" 
                    onclick="window.location.href='ASIGNAR_FORMULARIO.php'">
                <i class="fas fa-arrow-left"></i> Regresar
            </button>
        <?php endif; ?>
    </form>
</div>
</body>
</html>