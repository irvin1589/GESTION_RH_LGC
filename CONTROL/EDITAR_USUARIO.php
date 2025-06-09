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
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
             background: rgba(31, 58, 84, 0.73);
            z-index: -1;
        }
        :root {
            --primary-color: #4361ee;
            --primary-hover: #3a56d4;
            --secondary-color: #3f37c9;
            --danger-color: #f72585;
            --danger-hover: #e01a72;
            --success-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
            --border-radius: 10px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
           
            background-image: url('../IMG/puesto.jpg'); 
            background-size: cover;
            background-position: center;            
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: var(--dark-color);
        }

        .container {
            width: 100%;
            max-width: 800px;
        }

        .card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(to right, #4361ee, #3f37c9);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .card-header h2 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .card-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background-color: #f8f9fa;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            background-color: white;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
            cursor: pointer;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 25px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .notification {
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .notification.success {
            background-color: rgba(76, 201, 240, 0.15);
            color: #0d7a94;
            border-left: 4px solid var(--success-color);
        }

        .notification.error {
            background-color: rgba(247, 37, 133, 0.15);
            color: #b51a5e;
            border-left: 4px solid var(--danger-color);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 20px;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div> 
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-user-edit"></i> Editar Usuario</h2>
            </div>
            
            <div class="card-body">
                <?php if (!empty($mensaje)): ?>
                    <div class="notification <?= $tipoMensaje === 'error' ? 'error' : 'success' ?>">
                        <i class="fas fa-<?= $tipoMensaje === 'error' ? 'exclamation-circle' : 'check-circle' ?>"></i>
                        <?= htmlspecialchars($mensaje) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">

                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido1" class="form-label">Apellido Paterno:</label>
                        <input type="text" name="apellido1" id="apellido1" class="form-control" value="<?= htmlspecialchars($usuario['apellido1']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido2" class="form-label">Apellido Materno:</label>
                        <input type="text" name="apellido2" id="apellido2" class="form-control" value="<?= htmlspecialchars($usuario['apellido2']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="contraseña" class="form-label">Contraseña:</label>
                        <div style="position: relative;">
                            <input type="password" name="contraseña" id="contraseña" class="form-control" value="<?= htmlspecialchars($usuario['contraseña']) ?>" required>
                            <i class="fas fa-eye input-icon" onclick="togglePassword('contraseña')"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sueldo_diario" class="form-label">Sueldo Diario:</label>
                        <input type="text" name="sueldo_diario" id="sueldo_diario" class="form-control" value="<?= '$' . number_format((float)$usuario['sueldo_diario'], 2, '.', ',') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="tipo_usuario" class="form-label">Tipo de Usuario:</label>
                        <select name="tipo_usuario" id="tipo_usuario" class="form-control" required>
                            <option value="Admin" <?= $usuario['tipo_usuario'] == 'Admin' ? 'selected' : '' ?>>Administrador</option>
                            <option value="Colaborador" <?= $usuario['tipo_usuario'] == 'Colaborador' ? 'selected' : '' ?>>Colaborador</option>
                            <option value="RH" <?= $usuario['tipo_usuario'] == 'RH' ? 'selected' : '' ?>>Recursos Humanos</option>
                        </select>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-danger" onclick="window.location.href='VER_USUARIOS.php'">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" name="editar_usuario" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const passwordField = document.getElementById(id);
            const icon = passwordField.nextElementSibling;
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
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