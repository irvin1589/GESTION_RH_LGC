<?php
session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ENVIADO | LA GRAN CIUDAD RH</title>
  <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico" />
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: white;
      font-family: Arial, sans-serif;
      flex-direction: column;
    }
    .mensaje {
      margin-top: 20px;
      font-size: 20px;
      color: black;
    }
  </style>
</head>
<body>

<div class="mensaje">Formulario Evaluado</div>
<div class="mensaje">Redirigiendo...</div>
  <video autoplay loop muted playsinline width="100">
  <source src="../IMG/VIDEO/evaluar.webm" type="video/webm">
  <source src="../IMG/VIDEO/evaluar.webm" type="video/quicktime">
  Tu navegador no soporta la reproducción de video.
</video>
<script>
    setTimeout(function() {
      window.location.href = "VER_FORMULARIOS.php";
    }, 3000);
  </script>
</body>
</html>
