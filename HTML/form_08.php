<?php
include_once('../MODELO/CL_TABLA_FORMULARIO.php');

$tablaFormulario = new CL_TABLA_FORMULARIO();
$formularios = $tablaFormulario->listar_todos_los_formularios(); // Método que devuelve los formularios
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
            --primary-color: #1f3a54;       /* Azul oscuro principal */
            --secondary-color: #941C82;     /* Morado corporativo */
            --accent-color: #2c577c;       /* Azul más claro para hovers */
            --danger-color: #e74c3c;       /* Rojo para acciones peligrosas */
            --success-color: #28a745;      /* Verde para acciones positivas */
            --light-color: #f8f9fa;        /* Fondo claro */
            --dark-color: #343a40;         /* Texto oscuro */
            --border-radius: 8px;          /* Bordes redondeados */
            --box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Sombra suave */
            --transition: all 0.3s ease;   /* Transiciones suaves */
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
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .header::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: var(--secondary-color);
            margin: 0 auto;
            border-radius: 3px;
        }

        table {
            width: 90%;
            margin: 0 auto 30px;
            border-collapse: collapse;
            background-color: white;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }

        tr:nth-child(even) {
            background-color: rgba(248, 249, 250, 0.5);
        }

        tr:hover {
            background-color: rgba(31, 58, 84, 0.05);
            transition: var(--transition);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .btn {
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            background: transparent;
        }

        .btn-select {
            color: var(--success-color);
        }

        .btn-view {
            color: var(--primary-color);
        }

        .btn-delete {
            color: var(--danger-color);
        }

        .btn:hover {
            transform: scale(1.1);
            opacity: 0.9;
        }

        .btn i {
            font-size: 1.1rem;
        }

        .footer-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .footer-btn {
            padding: 12px 24px;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-assignments {
            background-color: var(--primary-color);
        }

        .btn-assignments:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
        }

        .btn-back {
            background-color: var(--danger-color);
        }

        .btn-back:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            table {
                width: 100%;
            }
            
            th, td {
                padding: 10px 8px;
                font-size: 0.85rem;
            }
            
            .action-buttons {
                gap: 5px;
            }
            
            .btn {
                width: 30px;
                height: 30px;
            }
            
            .footer-buttons {
                flex-direction: column;
                align-items: center;
                gap: 12px;
            }
            
            .footer-btn {
                width: 100%;
                max-width: 250px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2><i class="fas fa-file-signature"></i> LISTA DE FORMULARIOS</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha Límite</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($formularios as $formulario): ?>
                <tr>
                    <td><?= htmlspecialchars($formulario['nombre']) ?></td>
                    <td><?= htmlspecialchars($formulario['fecha_limite']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <form method="POST" action="../CONTROL/ASIGNAR_FORMULARIO.php" style="display: inline;">
                                <input type="hidden" name="id_formulario" value="<?= htmlspecialchars($formulario['id_formulario']) ?>">
                                <button type="submit" name="seleccionar_formulario" class="btn btn-select" title="Seleccionar">
                                    <i class="fas fa-circle-check"></i>
                                </button>
                            </form>
                            <form method="POST" action="../CONTROL/ASIGNAR_FORMULARIO.php" style="display: inline;">
                                <input type="hidden" name="id_formulario" value="<?= htmlspecialchars($formulario['id_formulario']) ?>">
                                <button type="submit" name="ver_formulario" class="btn btn-view" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </form>
                            <form method="POST" action="../CONTROL/ASIGNAR_FORMULARIO.php" style="display: inline;">
                                <input type="hidden" name="id_formulario" value="<?= htmlspecialchars($formulario['id_formulario']) ?>">
                                <button type="submit" name="eliminar_formulario" class="btn btn-delete" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer-buttons">
        <form method="POST" action="../CONTROL/VER_ASIGNACIONES.php">
            <button type="submit" name="ver_asignaciones" class="footer-btn btn-assignments">
                <i class="fas fa-list-check"></i> VER ASIGNACIONES
            </button>
        </form>
        
        <form method="POST" action="../CONTROL/ASIGNAR_FORMULARIO.php">
            <button type="submit" name="click_regresar" class="footer-btn btn-back">
                <i class="fas fa-arrow-left"></i> REGRESAR
            </button>
        </form>
    </div>
</body>
</html>