<?php

session_start();

$contraseña_correcta = "1nCidencias20254u7H"; 

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); // Redirigir al inicio de sesión si no está autenticado
    exit();
}

if (isset($_POST['submit_contraseña'])) {
    $contraseña_ingresada = $_POST['contraseña'];

    // Verificar si la contraseña es correcta
    if ($contraseña_ingresada === $contraseña_correcta) {
        // Redirigir al panel de incidencias si la contraseña es correcta
        header('Location: PANEL_AGREGAR_INCIDENCIA.php');
        exit();
    } else {
        // Mostrar un mensaje de error si la contraseña es incorrecta
        $error = "Contraseña incorrecta. Por favor, intenta de nuevo.";
    }
}

if (isset($_POST['regresar'])) {
    // Redirigir a la página de inicio o a donde desees
    header('Location: PANEL_EMPLEADO.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Panel de Incidencias</title>
</head>
<body>

<h2>Ingrese la Contraseña para Acceder al Panel de Incidencias</h2>

<!-- Mostrar error si la contraseña es incorrecta -->
<?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

<form method="post">
    <label for="contraseña">Contraseña:</label>
    <input type="password" name="contraseña">
    <button type="submit" name="submit_contraseña">Ingresar</button>
    <button type= "submit" name="regresar"> Regresar</button>
</form>

</body>
</html>
