<?php
// Incluir el archivo de conexión
include('conexion.php');

// Verificar si se ha enviado el formulario
if (isset($_POST['click_registrar'])) {
    // Obtener los valores del formulario
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $contraseña = $_POST['contraseña'];
    $comprobar_contraseña = $_POST['comprobar_contraseña'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $id_sucursal = $_POST['id_sucursal'];
    $id_puesto = $_POST['id_puesto'];
    $id_departamento = $_POST['id_departamento'];

    // Validar que las contraseñas coincidan
    if ($contraseña !== $comprobar_contraseña) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Encriptar la contraseña
    $contraseña_encriptada = password_hash($contraseña, PASSWORD_BCRYPT);

    // Insertar el usuario en la base de datos
    $query = "INSERT INTO USUARIO (id_usuario, nombre, apellido1, apellido2, contraseña, tipo_usuario, id_sucursal, id_puesto, id_departamento) 
              VALUES ('$id_usuario', '$nombre', '$apellido1', '$apellido2', '$contraseña_encriptada', '$tipo_usuario', '$id_sucursal', '$id_puesto', '$id_departamento')";

    if ($conn->query($query) === TRUE) {
        echo "Usuario registrado exitosamente.";
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>
