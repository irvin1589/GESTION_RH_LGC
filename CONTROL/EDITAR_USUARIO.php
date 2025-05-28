<?php
include_once('../MODELO/CL_TABLA_USUARIO.php');

$tablaUsuario = new CL_TABLA_USUARIO();
session_start();

// Verificar autenticación
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

// Verificar privilegios de administrador
if ($_SESSION['tipo_usuario'] !== 'Admin') {
    header('Location: acceso_denegado.php');
    exit();
}

// Mensaje de éxito/error
$mensaje = '';
$tipoMensaje = '';

// Obtener usuario por ID
if (isset($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];
    $usuario = $tablaUsuario->buscar_usuario_por_id($id_usuario);

    if (!$usuario) {
        header('Location: VER_USUARIOS.php?msg=error&action=update&text=Usuario+no+encontrado');
        exit();
    }
} else {
    header('Location: VER_USUARIOS.php');
    exit();
}

// Procesar el formulario cuando se envía
if (isset($_POST['editar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $contraseña = $_POST['contraseña'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $sueldo_diario = $_POST['sueldo_diario'];

    // Validación básica
    if (empty($nombre) || empty($apellido1) || empty($contraseña) || empty($tipo_usuario)) {
        $mensaje = 'Todos los campos obligatorios deben ser completados.';
        $tipoMensaje = 'error';
    } else {
        try {
            $resultado = $tablaUsuario->editar_usuario($id_usuario, $nombre, $apellido1, $apellido2, $contraseña, $tipo_usuario, $sueldo_diario );
            
            if ($resultado) {
                header("Location: VER_USUARIOS.php?msg=success&action=update&text=Usuario+actualizado+correctamente");
                exit();
            } else {
                $mensaje = 'Error al editar el usuario. Inténtalo de nuevo.';
                $tipoMensaje = 'error';
            }
        } catch (Exception $e) {
            $mensaje = 'Error: ' . $e->getMessage();
            $tipoMensaje = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario | LA GRAN CIUDAD </title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../IMG/puesto.jpg'); 
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
             background: rgba(31, 58, 84, 0.73);
            z-index: -1;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            color: black;
        }

        form {
            width: 50%;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 30px;
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="text"]:focus, input[type="password"]:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px; 
        }

        button[type="submit"], button.cancel {
            background-color: #007bff;
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            width: 30%; 
        }

        button[type="submit"]:hover, button.cancel:hover {
            background-color: #0056b3;
        }

        button.cancel {
            background-color: #dc3545;
        }

        button.cancel:hover {
            background-color:rgb(151, 29, 41);
        }

        .notification {
            width: 80%;
            margin: 20px auto;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .contraseña-container {
            position: relative;
            width: 100%;
        }

        .ojo {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="overlay"></div> 

    <?php if (!empty($mensaje)): ?>
        <div class="notification <?php echo $tipoMensaje; ?>"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST">
        <h2>Editar Usuario</h2>
        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required><br>

        <label for="apellido1">Apellido Paterno:</label>
        <input type="text" name="apellido1" id="apellido1" value="<?= htmlspecialchars($usuario['apellido1']) ?>" required><br>

        <label for="apellido2">Apellido Materno:</label>
        <input type="text" name="apellido2" id="apellido2" value="<?= htmlspecialchars($usuario['apellido2']) ?>"><br>

        <label for="contraseña">Contraseña:</label>
        <div class="contraseña-container">
            <input type="password" name="contraseña" id="contraseña" value="<?= htmlspecialchars($usuario['contraseña']) ?>" required><br>
            <i class="fas fa-eye ojo" onclick="togglePassword('contraseña')"></i>
        </div>

        <label for="sueldo_diario">Sueldo Diario</label>
        <input type="text" name="sueldo_diario" id="sueldo_diario"
        value="<?= '$' . number_format((float)$usuario['sueldo_diario'], 2, '.', ',') ?>"
        required>



        <label for="tipo_usuario">Tipo de Usuario:</label>
        <select name="tipo_usuario" id="tipo_usuario" required>
            <option value="Admin" <?= $usuario['tipo_usuario'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
            <option value="Colaborador" <?= $usuario['tipo_usuario'] == 'Colaborador' ? 'selected' : '' ?>>Colaborador</option>
            <option value="RH" <?= $usuario['tipo_usuario'] == 'RH' ? 'selected' : '' ?>>RH</option>
        </select><br><br>

        <div class="button-container">
            <button type="submit" name="editar_usuario">Actualizar Usuario</button>
            <button type="button" class="cancel" onclick="window.location.href='VER_USUARIOS.php'">Cancelar</button>
        </div>
    </form>

    <script>
        function togglePassword(id) {
        const passwordField = document.getElementById(id);
        passwordField.type = passwordField.type === "password" ? "text" : "password";
    }

      const sueldoInput = document.getElementById('sueldo_diario');

    // Al cargar, formatear si hay valor
    window.addEventListener('DOMContentLoaded', () => {
        formatearCampo();
    });

    // Permitir escribir sin formateo automático
    sueldoInput.addEventListener('input', () => {
        // Permitir solo números y un punto decimal
        let valor = sueldoInput.value.replace(/[^0-9.]/g, '');
        const partes = valor.split('.');
        if (partes.length > 2) { // Más de un punto decimal no es válido
            valor = partes[0] + '.' + partes[1];
        }
        sueldoInput.value = valor;
    });

    // Al salir del campo, aplicar formato bonito
    sueldoInput.addEventListener('blur', () => {
        formatearCampo();
    });

    // Antes de enviar, dejar solo el número plano
    document.querySelector('form').addEventListener('submit', () => {
        sueldoInput.value = sueldoInput.value.replace(/[^0-9.]/g, '');
    });

    function formatearCampo() {
        let valor = sueldoInput.value.replace(/[^0-9.]/g, '');
        if (valor) {
            const numero = parseFloat(valor);
            if (!isNaN(numero)) {
                sueldoInput.value = '$' + numero.toLocaleString('es-MX', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        }
    }
    </script>

</body>
</html>