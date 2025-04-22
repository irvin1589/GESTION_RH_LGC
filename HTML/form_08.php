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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #444;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        button {
            padding: 8px 15px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button[name="seleccionar_formulario"] {
            background-color: #28a745;
            color: #fff;
        }

        button[name="seleccionar_formulario"]:hover {
            background-color: #218838;
        }

        button[name="ver_asignaciones"] {
            background-color: #007bff;
            color: #fff;
        }

        button[name="ver_asignaciones"]:hover {
            background-color: #0056b3;
        }

        button[name="click_regresar"] {
            background-color: #dc3545;
            color: #fff;
        }

        button[name="click_regresar"]:hover {
            background-color: #c82333;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Lista de Formularios</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha Límite</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($formularios as $formulario): ?>
                <tr>
                    <td><?= htmlspecialchars($formulario['nombre']) ?></td>
                    <td><?= htmlspecialchars($formulario['fecha_limite']) ?></td>
                    <td>
                        <form method="POST" action="../CONTROL/ASIGNAR_FORMULARIO.php">
                            <input type="hidden" name="id_formulario" value="<?= htmlspecialchars($formulario['id_formulario']) ?>">
                            <button type="submit" name="seleccionar_formulario">Seleccionar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Botón para ver asignaciones -->
    <form method="POST" action="../CONTROL/VER_ASIGNACIONES.php">
        <button type="submit" name="ver_asignaciones">VER ASIGNACIONES</button>
    </form>

    <!-- Botón para regresar -->
    <form method="POST" action="../CONTROL/ASIGNAR_FORMULARIO.php">
        <button type="submit" name="click_regresar">REGRESAR</button>
    </form>
</body>
</html>