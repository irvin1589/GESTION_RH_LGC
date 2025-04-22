<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRO | LA GRAN CIUDAD</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            max-height: 90vh; /* Limitar la altura máxima */
            overflow-y: auto; /* Habilitar desplazamiento si es necesario */
        }

        .form-container h2 {
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

        .form-container input[type="text"],
        .form-container input[type="password"],
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-container input[type="submit"],
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .form-container button {
            background-color: #6c757d;
        }

        .form-container input[type="submit"]:hover,
        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container button:hover {
            background-color: #5a6268;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 15px 20px;
            }

            .form-container h2 {
                font-size: 20px;
            }

            .form-container input[type="submit"],
            .form-container button {
                font-size: 14px;
            }
        }

        @media (max-height: 500px) {
            .form-container {
                max-height: 80vh; /* Ajustar para pantallas muy pequeñas */
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registro de Usuario</h2>
        <form method="POST" action="CONTROL_REGISTRO.php">
        
        <label for="sucursal">Sucursal:</label>
            <select id="sucursal" name="caja_opcion1" onchange="this.form.submit()">
                {{sucursales}}
            </select>

            <label for="departamento">Departamento:</label>
            <select id="departamento" name="caja_opcion2" onchange="this.form.submit()">
                {{departamentos}}
            </select>

            <label for="puesto">Puesto:</label>
            <select id="puesto" name="caja_opcion3">
                {{puestos}}
            </select>

            <label for="tipo_usuario">Tipo de Usuario:</label>
            <select id="tipo_usuario" name="caja_opcion4">
                <option value="Admin">Admin</option>
                <option value="RH">RH</option>
                <option value="Empleado">Empleado</option>
            </select>

        <label for="id">ID Usuario:</label>
            <input type="text" name="caja_texto1" placeholder="Ingrese su ID de usuario">

            <label for="nombre">Nombre:</label>
            <input type="text" name="caja_texto2" placeholder="Ingrese su nombre">

            <label for="apellido1">Apellido 1:</label>
            <input type="text" name="caja_texto3" placeholder="Ingrese su primer apellido">

            <label for="apellido2">Apellido 2:</label>
            <input type="text" name="caja_texto4" placeholder="Ingrese su segundo apellido">

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="caja_texto5" placeholder="Ingrese su contraseña">

            <label for="comprobar_contrasena">Comprobar Contraseña:</label>
            <input type="password" name="caja_texto6" placeholder="Confirme su contraseña">


            <input type="submit" name="click_registrar" value="Registrar">
            <button type="submit" name="click_regresar">REGRESAR</button>
        </form>
    </div>
</body>
</html>
