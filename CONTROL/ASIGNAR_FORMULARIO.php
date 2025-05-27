<?php
session_start();
ob_start();
include_once('../MODELO/CL_TABLA_DEPARTAMENTO.php');
include_once('../MODELO/CL_TABLA_PUESTO.php');
include_once('../MODELO/CL_TABLA_SUCURSAL.php');
include_once('../VISTA/CL_INTERFAZ08.php');
include_once('../MODELO/CL_TABLA_FORMULARIO.php');
include_once('../MODELO/CL_TABLA_USUARIO.php');
include_once('../MODELO/CL_TABLA_ASIGNACION_FORMULARIO.php');
include_once('../MODELO/CL_CONEXION.php');

$form_08 = new CL_INTERFAZ08();
$form_08->mostrar();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); 
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

// Inicializar variables
$notification = null;
$showAssignmentForm = false;
$formulario = null;
$usuarios = [];

if (isset($_POST['seleccionar_formulario'])) {
    $id_formulario = $_POST['id_formulario'];
    $tablaFormulario = new CL_TABLA_FORMULARIO();
    $formulario = $tablaFormulario->obtener_formulario_por_id($id_formulario);

    if (!$formulario) {
        $notification = [
            'type' => 'error',
            'message' => 'Error: No se pudo obtener los detalles del formulario.'
        ];
    } else {
        $showAssignmentForm = true;
        $id_departamento = $formulario['id_departamento'];
        $id_sucursal = $formulario['id_sucursal'];
        $id_puesto = $formulario['id_puesto'];
        
        $tablaUsuario = new CL_TABLA_USUARIO();
        $usuarios = $tablaUsuario->listar_usuarios_por_filtros($id_departamento, $id_sucursal, $id_puesto);
    }
}

if (isset($_POST['click_regresar_1'])) {
    $redirect = ($_SESSION['tipo_usuario'] === 'Admin' || $_SESSION['tipo_usuario'] === 'RH') 
        ? '../CONTROL/ASIGNAR_FORMULARIO.php' 
        : 'acceso_denegado.php';
    header("Location: $redirect");
    exit();
}

if (isset($_POST['asignar_formulario'])) {
    $id_formulario = $_POST['id_formulario'];
    $id_usuario = $_POST['id_usuario'];

    $tablaAsignacion = new CL_TABLA_ASIGNACION_FORMULARIO();
    $resultado = $tablaAsignacion->crear_asignacion($id_formulario, $id_usuario);

    if ($resultado) {
        $notification = [
            'type' => 'success',
            'message' => 'Formulario asignado exitosamente al usuario.'
        ];
        $showAssignmentForm = false;
    } else {
        $notification = [
            'type' => 'error',
            'message' => 'Error al asignar el formulario. Por favor, inténtelo de nuevo.'
        ];
    }
}

if (isset($_POST['click_regresar'])) {
    $redirect = $_SESSION['tipo_usuario'] === 'Admin' 
        ? '../CONTROL/PANEL_ADMIN.php' 
        : ($_SESSION['tipo_usuario'] === 'RH' 
            ? '../CONTROL/PANEL_DESARROLLO.php' 
            : 'acceso_denegado.php');
    header("Location: $redirect");
    exit();
}

if (isset($_POST['ver_formulario'])) {
    $id_formulario = $_POST['id_formulario'];
    $tablaAsignacion = new CL_TABLA_ASIGNACION_FORMULARIO();
    $asignaciones = $tablaAsignacion->listar_asignaciones_por_formulario($id_formulario);

    if (!empty($asignaciones)) {
        $id_asignacion = $asignaciones[0]['id_asignacion'];
        header("Location: RESPONDER_FORM.php?id_asignacion=$id_asignacion");
        exit();
    } else {
        $notification = [
            'type' => 'error',
            'message' => 'No hay una vista previa disponible.'
        ];
    }
}

if (isset($_POST['eliminar_formulario'])) {
    $id_formulario = $_POST['id_formulario'];

    $db = new CL_CONEXION();
    $pdo = $db->getPDO();

    try {
        $pdo->beginTransaction();
        $exito = true;

        // 1. Obtener asignaciones
        $stmt = $pdo->prepare("SELECT id_asignacion FROM asignacion_formulario WHERE id_formulario = ?");
        $stmt->execute([$id_formulario]);
        $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Eliminar respuestas y evaluaciones por asignación
        foreach ($asignaciones as $row) {
            $id_asignacion = $row['id_asignacion'];

            $stmt = $pdo->prepare("DELETE FROM respuesta WHERE id_asignacion = ?");
            $exito = $exito && $stmt->execute([$id_asignacion]);

            $stmt = $pdo->prepare("DELETE FROM evaluacion WHERE id_asignacion = ?");
            $exito = $exito && $stmt->execute([$id_asignacion]);
        }

        // 3. Eliminar asignaciones
        $stmt = $pdo->prepare("DELETE FROM asignacion_formulario WHERE id_formulario = ?");
        $exito = $exito && $stmt->execute([$id_formulario]);

        // 4. Eliminar opciones de preguntas y luego preguntas
        $stmt = $pdo->prepare("SELECT id_pregunta FROM pregunta WHERE id_formulario = ?");
        $stmt->execute([$id_formulario]);
        $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($preguntas as $row) {
            $id_pregunta = $row['id_pregunta'];

            $stmt = $pdo->prepare("DELETE FROM opcion_pregunta WHERE id_pregunta = ?");
            $exito = $exito && $stmt->execute([$id_pregunta]);
        }

        // 5. Eliminar preguntas
        $stmt = $pdo->prepare("DELETE FROM pregunta WHERE id_formulario = ?");
        $exito = $exito && $stmt->execute([$id_formulario]);

        // 6. Finalmente, eliminar el formulario
        $stmt = $pdo->prepare("DELETE FROM formulario WHERE id_formulario = ?");
        $exito = $exito && $stmt->execute([$id_formulario]);

        if ($exito) {
            $pdo->commit();
            $notification = [
                'type' => 'success',
                'message' => 'Formulario eliminado correctamente.'
            ];
        } else {
            $pdo->rollBack();
            $notification = [
                'type' => 'error',
                'message' => 'Hubo un error al eliminar el formulario.'
            ];
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $notification = [
            'type' => 'error',
            'message' => 'Error en la base de datos: ' . $e->getMessage()
        ];
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASIGNAR FORMULARIO | LA GRAN CIUDAD</title>
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
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
            color: var(--dark-color);
            min-height: 100vh;
        }

        .assignment-container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .assignment-header {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            text-align: center;
        }

        .assignment-header h1 {
            margin: 0;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .form-details {
            padding: 25px;
            border-bottom: 1px solid #eee;
        }

        .form-detail-item {
            margin-bottom: 15px;
        }

        .form-detail-item strong {
            color: var(--primary-color);
            font-size: 1.1rem;
            display: block;
            margin-bottom: 5px;
        }

        .form-detail-item span {
            font-size: 1rem;
            color: var(--dark-color);
            display: block;
            padding-left: 15px;
        }

        .user-selection {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background-color: var(--light-color);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(31, 58, 84, 0.2);
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-assign {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-assign:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
        }

        .btn-back {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-back:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
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

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .assignment-container {
                max-width: 100%;
            }
            
            .assignment-header h1 {
                font-size: 1.5rem;
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
    <?php if ($showAssignmentForm && $formulario): ?>
    <div class="assignment-container">
        <div class="assignment-header">
            <h1><i class="fas fa-file-signature"></i> ASIGNAR FORMULARIO</h1>
        </div>
        
        <div class="form-details">
            <div class="form-detail-item">
                <strong>Nombre:</strong>
                <span><?= htmlspecialchars($formulario['nombre']) ?></span>
            </div>
            <div class="form-detail-item">
                <strong>Descripción:</strong>
                <span><?= htmlspecialchars($formulario['descripcion']) ?></span>
            </div>
            <div class="form-detail-item">
                <strong>Fecha Límite:</strong>
                <span><?= htmlspecialchars($formulario['fecha_limite']) ?></span>
            </div>
        </div>
        
        <div class="user-selection">
            <form method="POST" action="ASIGNAR_FORMULARIO.php">
                <input type="hidden" name="id_formulario" value="<?= htmlspecialchars($formulario['id_formulario']) ?>">
                
                <div class="form-group">
                    <label for="usuario"><i class="fas fa-user"></i> SELECCIONAR USUARIO</label>
                    <select id="usuario" name="id_usuario" class="form-control">
                        <option value="">-- Seleccione un usuario --</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
                                <?= htmlspecialchars($usuario['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="button-group">
                    <button type="submit" name="asignar_formulario" class="btn btn-assign">
                        <i class="fas fa-check-circle"></i> ASIGNAR
                    </button>
                    <button type="submit" name="click_regresar_1" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i> REGRESAR
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (isset($notification)): ?>
        <div class="notification <?= $notification['type'] ?>">
            <?= $notification['message'] ?>
        </div>
        
        <script>
            setTimeout(() => {
                const notif = document.querySelector('.notification');
                if (notif) notif.style.display = 'none';
            }, 3000);
        </script>
    <?php endif; ?>
</body>
</html>