<?php
include_once('../MODELO/CL_TABLA_SUCURSAL.php');

$tablaSucursal = new CL_TABLA_SUCURSAL();
$id_sucursal = $_GET['id_sucursal'] ?? '';

if (!empty($id_sucursal)) {
    $sucursal = $tablaSucursal->obtener_sucursal($id_sucursal);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';

        $resultado = $tablaSucursal->actualizar_sucursal($id_sucursal, $nombre, $direccion, $telefono);

        if ($resultado) {
            echo "<script>alert('Sucursal actualizada correctamente.');</script>";
            header('Location: PANEL_ADMIN.php');
            exit();
        } else {
            echo "<script>alert('Error al actualizar la sucursal.');</script>";
        }
    }
} else {
    echo "ID de sucursal no válido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sucursal</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('../IMG/editar.jpg'); /* Imagen de fondo */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Overlay azul transparente */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(31, 58, 84, 0.7); /* Fondo azul con transparencia */
            z-index: 1; /* Detrás del formulario */
        }

        /* Contenedor del formulario */
        .form-container {
            position: relative;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            z-index: 2; /* Encima del overlay */
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555555;
        }

        .form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-container input[type="text"]:focus {
            border-color: #007BFF;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button[type="submit"] {
            background-color:  #007bff;
            color: white;
        }

        .form-container button[type="submit"]:hover {
            background-color:rgb(26, 87, 153);;
        }

        .form-container button.cancel {
            background-color: #f44336;
            color: white;
        }

        .form-container button.cancel:hover {
            background-color: #d32f2f;
        }

        /* Estilos responsivos */
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            .form-container h1 {
                font-size: 20px;
            }

            .form-container input[type="text"],
            .form-container button {
                font-size: 14px;
            }
        }
        .logo {
                position: absolute;
                top: 10px;
                left: 10px;
                width: 100px; /* Cambia el tamaño de la imagen */
                height: auto; /* Mantén la proporción */
                z-index: 3; /* Asegura que esté encima del overlay */
            }
    </style>
</head>
<body>
    <img src="../IMG/LOGO_LGC__AZUL.jpg" alt="Logo del Sistema" class="logo">
    <!-- Overlay azul transparente -->
    <div class="overlay"></div>

    <!-- Contenedor del formulario -->
    <div class="form-container">
        <h1>Editar Sucursal</h1>
        <form method="POST" action="">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($sucursal['nombre']); ?>" required>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($sucursal['direccion']); ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($sucursal['telefono']); ?>" required>

            <button type="submit">Guardar Cambios</button>
            <button type="button" class="cancel" onclick="window.location.href='PANEL_ADMIN.php'">Cancelar</button>
        </form>
    </div>
</body>
</html>