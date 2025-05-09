<?php
include_once "../MODELO/CL_CONEXION.php";
$mensaje = "";
$tipo_mensaje = "";

// Crear conexión a la base de datos
$conexion = new CL_CONEXION();
$conn = $conexion->getPDO();

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_departamento = trim($_POST['nombre_departamento']);
    $id_sucursal = intval($_POST['id_sucursal']);

    if (!empty($nombre_departamento) && $id_sucursal > 0) {
        try {
            $sql = "INSERT INTO departamento (nombre, id_sucursal) VALUES (:nombre, :id_sucursal)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre_departamento, PDO::PARAM_STR);
            $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $mensaje = "✅ Departamento agregado correctamente.";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "❌ Error al agregar el departamento.";
                $tipo_mensaje = "error";
            }
        } catch (PDOException $e) {
            $mensaje = "❌ Error: " . $e->getMessage();
            $tipo_mensaje = "error";
        }
    } else {
        $mensaje = "⚠️ Por favor, llena todos los campos correctamente.";
        $tipo_mensaje = "error";
    }
}

// Obtener las sucursales
$sucursales = [];
try {
    $sql_suc = "SELECT id_sucursal, nombre FROM sucursal";
    $stmt = $conn->query($sql_suc);
    $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "⚠️ Error al obtener sucursales: " . $e->getMessage();
    $tipo_mensaje = "error";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Departamento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('../IMG/puesto.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            position: relative;
        }

        .overlay {
            background-color: rgba(31, 58, 84, 0.9);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }

        .logo {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 3;
            width: 50px;
            height: auto;
        }

        .container {
            position: relative;
            z-index: 2;
            max-width: 400px;
            margin: 100px auto;
            background: #fff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .notification-container {
            position: fixed;
            top: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            z-index: 1000;
        }

        .notification {
            display: <?php echo !empty($mensaje) ? 'flex' : 'none'; ?>;
            flex-direction: column;
            background-color: rgba(0,0,0,0.9);
            color: white;
            padding: 15px;
            width: 90%;
            max-width: 400px;
            border-radius: 8px;
            text-align: center;
            animation: fadeIn 0.3s ease-in-out;
        }

        .notification.success {
            border-left: 5px solid #28a745;
        }

        .notification.error {
            border-left: 5px solid #dc3545;
        }

        .notification button {
            margin-top: 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            align-self: flex-end;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Resto de tus estilos... */
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        input[type="submit"], .btn-secondary {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            cursor: pointer;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #FF4B4B;
            color: white;
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background-color: #c0392b;
        }

        @media (max-width: 500px) {
            .container {
                margin: 80px 15px;
                padding: 20px;
            }

            .logo {
                width: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <img src="../IMG/LOGO_LGC__AZUL.jpg" alt="Logo" class="logo">

    <?php if (!empty($mensaje)): ?>
        <div class="notification-container">
            <div class="notification <?php echo $tipo_mensaje; ?>" id="notification">
                <p><?php echo $mensaje; ?></p>
                <button onclick="document.getElementById('notification').style.display='none'">Cerrar</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>CREACIÓN DE DEPARTAMENTO</h2>
        <form method="POST">
            <label for="id_sucursal">Sucursal:</label>
            <select id="id_sucursal" name="id_sucursal" required>
                <option value="">-- Selecciona una sucursal --</option>
                <?php foreach ($sucursales as $sucursal): ?>
                    <option value="<?php echo $sucursal['id_sucursal']; ?>">
                        <?php echo htmlspecialchars($sucursal['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="nombre_departamento">Departamento:</label>
            <input type="text" id="nombre_departamento" name="nombre_departamento" required placeholder="Ej. Recursos Humanos">

            <input type="submit" value="Registrar">
            <button type="button" class="btn-secondary" onclick="location.href='../index.php';">Regresar</button>
        </form>
    </div>

    <script>
        // Ocultar notificación después de 5 segundos
        <?php if (!empty($mensaje)): ?>
            setTimeout(function() {
                var notification = document.getElementById('notification');
                if (notification) {
                    notification.style.display = 'none';
                }
            }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>