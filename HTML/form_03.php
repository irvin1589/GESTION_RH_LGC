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
      background-image: url('../IMG/fondo_registro.avif');
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
            background-color:  rgba(0, 0, 0, 0.6);
            animation: moveBackground 15s linear infinite; 
            z-index: 1; 
            }

            @keyframes moveBackground {
            0% {
              background-position: 0 0;
            }
            100% {
              background-position: 100% 100%; 
            }
          }

    .form-container {
      background-color:rgba(165, 39, 147, 0.57);
      width: 100%;
      max-width: 800px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(255, 255, 255, 0.15);
      padding: 30px 40px;
      z-index: 2;
    }

    h2 {
      text-align: center;
      color:rgb(255, 255, 255);
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
    color: #f1f1f1; /* Blanco suave */
    display: block;
  }

    input[type="text"],
    input[type="password"],
    input[type="number"],
    select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid  rgb(200, 0, 255);
    border-radius: 8px;
    font-size: 14px;
    background-color:  rgba(165, 39, 147, 0.57);
    color: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(4px);
    }

    select {
  background-color: rgba(165, 39, 147, 0.57); 
  color: rgba(255, 255, 255, 0.9); 
  border: 1px solid rgb(200, 0, 255); 
  padding: 10px 12px;
  border-radius: 8px;
  font-size: 14px;
}

select option {
  background-color:rgba(138, 4, 118, 0.97); 
  color: white; 
}

    input[type="text"]::placeholder,
    input[type="password"]::placeholder,
    input[type="number"]::placeholder,
    select::placeholder {
      color: rgba(255, 255, 255, 0.79);
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
      background-color: #009FE3;
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
          <option value="Colaborador">Colaborador</option>
          <option value="RH">RH</option>
          <option value="Admin">Admin</option>
          
        </select>
      </div>

      <div>
        <label for="id">ID Usuario:</label>
        <input type="text" id="usuario" name="caja_texto1" placeholder="Ingrese su ID de usuario" >
      </div>

      <div>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="caja_texto2" placeholder="Ingrese su nombre" oninput="generarUsuario()">
      </div>

      <div>
        <label for="apellido1">Apellido Paterno:</label>
        <input type="text" id="apellidoP" name="caja_texto3" placeholder="Ingrese su primer apellido" oninput="generarUsuario()">
      </div>

      <div>
        <label for="apellido2">Apellido Materno:</label>
        <input type="text" id="apellidoM" name="caja_texto4" placeholder="Ingrese su segundo apellido" oninput="generarUsuario()">
      </div>

      <div>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="caja_texto5" placeholder="Ingrese su contraseña">
      </div>

      <div>
        <label for="comprobar_contrasena">Comprobar Contraseña:</label>
        <input type="password" name="caja_texto6" placeholder="Confirme su contraseña">
      </div>

      <div>
        <label>Sueldo diario</label>
        <input type="number" name="caja_texto7" step="0.01" min="0" placeholder="Ingrese el sueldo diario">
      </div>

      <div class="form-actions">
      <button type="submit" name="click_registrar">
        <i class="fas fa-user-plus"></i> Registrar
        </button>

        <button type="submit" name="click_regresar"><i class="fas fa-arrow-left"></i> Regresar</button>
      </div>
    </form>
  </div>

  <script>
    function generarUsuario() {
      const nombre = document.getElementById("nombre").value.trim().toLowerCase();
      const apellidoP = document.getElementById("apellidoP").value.trim().toLowerCase();
      const apellidoM = document.getElementById("apellidoM").value.trim().toLowerCase();

      if (nombre && apellidoP && apellidoM) {
        const usuario = apellidoP.substring(0, 10) +
                        apellidoM.substring(0, 2) +
                        nombre.substring(0, 2) +
                        Math.floor(Math.random() * 100); 
        document.getElementById("usuario").value = usuario;
      } else {
        document.getElementById("usuario").value = "";
      }
    }
  </script>
</body>
</html>
