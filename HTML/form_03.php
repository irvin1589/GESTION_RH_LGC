<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>REGISTRO | LA GRAN CIUDAD</title>
  <link rel="icon" href="../IMG/logo-blanco-1.ico" type="image/x-icon"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 20px;
      background-image: url('../IMG/fondo_1.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100dvh;
      overflow-y: auto;
    }
            .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color:  rgba(31, 58, 84, 0.7);
            background-image: url('../IMG/logo-blanco-1.ico');
            background-repeat: repeat;
            background-size: 70px 70px; 
            background-position: center;
            animation: moveBackground 15s linear infinite; 
            z-index: 1; 
            }

            @keyframes moveBackground {
  0% {
    background-position: 0 0;
  }
  100% {
    background-position: 100% 100%; /* Mueve el fondo a través de la pantalla */
  }
}

    .form-container {
      background-color: rgba(255, 255, 255, 0.7);
      width: 100%;
      max-width: 800px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      padding: 30px 40px;
      z-index: 2;
    }

    h2 {
      text-align: center;
      color:rgb(26, 120, 219);
      margin-bottom: 30px;
      font-size: 28px;
    }

    form {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    label {
      font-weight: 600;
      margin-bottom: 6px;
      color: #333;
      display: block;
    }

    input[type="text"],
    input[type="password"],
    select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid rgb(0, 89, 255);
    border-radius: 8px;
    font-size: 14px;
    background-color: rgba(255, 255, 255, 0.1);
    color: black;
    backdrop-filter: blur(4px);
    }

    input[type="text"]::placeholder,
    input[type="password"]::placeholder,
    select::placeholder {
    color:rgb(0, 0, 0);  /* Aquí puedes cambiar el color que desees */
    }

    .form-actions {
  grid-column: 1 / -1;
  display: flex;
  justify-content: center; /* Centra horizontalmente */
  gap: 20px; /* Espacio entre los botones */
  margin-top: 10px;
}

    input[type="submit"],
    button {
      padding: 12px;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
      width: 30%;
    }

    input[type="submit"] {
      background-color: #007bff;
      color: white;
    }

    input[type="submit"]:hover {
      background-color: #0056b3;
    }

    button {
      background-color: #007bff;
      color: white;
    }
    button[name="click_regresar"] {
  background-color: #dc3545;
}

button[name="click_regresar"]:hover {
  background-color: #c82333;
}
    button:hover {
      background-color:rgb(29, 105, 187);
    }

    @media (max-width: 480px) {
      .form-container {
        padding: 20px 15px;
        border-radius: 10px;
      }

      h2 {
        font-size: 22px;
      }

      input[type="submit"],
      button {
        font-size: 14px;
        padding: 10px;
      }

      form {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
<div class="overlay"></div>
  <div class="form-container">
    <h2><i class="fas fa-user-plus"></i> Registro de Usuario</h2>
    <form method="POST" action="CONTROL_REGISTRO.php">
      <div>
        <label for="sucursal">Sucursal:</label>
        <select id="sucursal" name="caja_opcion1" onchange="this.form.submit()">
          {{sucursales}}
        </select>
      </div>

      <div>
        <label for="departamento">Departamento:</label>
        <select id="departamento" name="caja_opcion2" onchange="this.form.submit()">
          {{departamentos}}
        </select>
      </div>

      <div>
        <label for="puesto">Puesto:</label>
        <select id="puesto" name="caja_opcion3">
          {{puestos}}
        </select>
      </div>

      <div>
        <label for="tipo_usuario">Tipo de Usuario:</label>
        <select id="tipo_usuario" name="caja_opcion4">
          <option value="Admin">Admin</option>
          <option value="RH">RH</option>
          <option value="Empleado">Empleado</option>
        </select>
      </div>

      <div>
        <label for="id">ID Usuario:</label>
        <input type="text" name="caja_texto1" placeholder="Ingrese su ID de usuario">
      </div>

      <div>
        <label for="nombre">Nombre:</label>
        <input type="text" name="caja_texto2" placeholder="Ingrese su nombre">
      </div>

      <div>
        <label for="apellido1">Apellido Paterno:</label>
        <input type="text" name="caja_texto3" placeholder="Ingrese su primer apellido">
      </div>

      <div>
        <label for="apellido2">Apellido Materno:</label>
        <input type="text" name="caja_texto4" placeholder="Ingrese su segundo apellido">
      </div>

      <div>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="caja_texto5" placeholder="Ingrese su contraseña">
      </div>

      <div>
        <label for="comprobar_contrasena">Comprobar Contraseña:</label>
        <input type="password" name="caja_texto6" placeholder="Confirme su contraseña">
      </div>

      <div class="form-actions">
      <button type="submit" name="click_registrar">
        <i class="fas fa-user-plus"></i> Registrar
        </button>

        <button type="submit" name="click_regresar"><i class="fas fa-arrow-left"></i> Regresar</button>
      </div>
    </form>
  </div>
</body>
</html>
