<?php

session_start();

$contraseña_correcta = "1nCidencias20254u7H"; 

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); 
    exit();
}

if (isset($_POST['submit_contraseña'])) {
    $contraseña_ingresada = $_POST['contraseña'];

   
    if ($contraseña_ingresada === $contraseña_correcta) {
        
        header('Location: PANEL_AGREGAR_INCIDENCIA.php');
        exit();
    } else {
        
        $error = "Contraseña incorrecta. Por favor, intenta de nuevo.";
    }
}

if (isset($_POST['regresar'])) {
    
    header('Location: PANEL_EMPLEADO.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LOGIN INCIDENCIAS | LA GRAN CIUDAD</title>
  <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Roboto', sans-serif;
    }

    body, html {
      height: 100%;
      background: url('../IMG/fondo_login.png') no-repeat center center/cover;
    }

    .overlay {
      background-color: rgba(0, 0, 0, 0.6);
      height: 100%;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      display: flex;
      width: 90%;
      max-width: 1000px;
      background-color: rgba(255, 255, 255, 0.47);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 16px rgba(0,0,0,0.3);
      flex-direction: row;
    }

    .left, .right {
      flex: 1;
      padding: 40px;
    }

    .right {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .left {
      color: #222;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .left h1 {
      font-size: 42px;
      color: rgb(12, 75, 224);
      margin-bottom: 20px;
    }

    .left p {
      font-size: 18px;
      margin-bottom: 30px;
    }


    .form {
      width: 100%;
    }

    .form h2 {
      font-size: 24px;
      margin-bottom: 20px;
      color: rgb(12, 75, 224);
      text-align: center;
    }

    .form label {
      display: block;
      margin: 10px 0 5px;
    }

    .form input[type="password"] {
      width: 100%;
      padding: 12px;
      background-color:rgba(249, 249, 249, 0.43);
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .form .button-row {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      margin-top: 15px;
    }

    .form button {
      flex: 1;
      padding: 12px;
      background-color:rgb(69, 44, 179);
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .form button[name="regresar"] {
      background-color: #e63946;
    }

    .form button:hover {
      opacity: 0.9;
    }

    .form .error {
      color: red;
      margin-bottom: 10px;
      text-align: center;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        margin: 20px;
      }

      .left, .right {
        padding: 30px;
      }
    }
  </style>
</head>
<body>
  <div class="overlay">
    <div class="container">
      <div class="left">
        <h1>Bienvenido</h1>
        <p>Accede al panel para registrar y gestionar las incidencias del personal de forma rápida y segura.</p>
      </div>
      <div class="right">
        <form class="form" method="post">
          <h2>Ingrese la Contraseña</h2>
          <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
          <label for="contraseña">Contraseña:</label>
          <input type="password" name="contraseña" placeholder="Ingrese la contraseña">
          <div class="button-row">
            <button type="submit" name="submit_contraseña">Ingresar</button>
            <button type="submit" name="regresar">Regresar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
