<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Lista de Sucursales</title>
    <style>
        html, body {
            height: 100%; /* Asegura que el fondo cubra toda la pantalla */
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-image: url('../IMG/DEPARTAMENTAL.jpg'); /* Ruta de la imagen de fondo */
            background-size: cover; /* Ajusta la imagen para cubrir todo el fondo */
            background-position: center; /* Centra la imagen */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            background-attachment: fixed; /* Fija la imagen de fondo */
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: rgba(31, 58, 84, 0.9); /* Fondo azul con transparencia */
            color: #ffffff;
            padding: 20px 30px; /* Ajusta el espacio interno */
            position: relative; /* Necesario para posicionar el logo dentro del header */
        }

        .header h1 {
            margin: 0;
            font-size: 28px; /* Tamaño del título por defecto */
            text-align: center; /* Centra el texto del título */
        }

        .logo {
            position: absolute; /* Posiciona el logo dentro del header */
            top: 10px; /* Ajusta la distancia desde la parte superior */
            left: 10px; /* Ajusta la distancia desde la parte izquierda */
            width: 100px; /* Tamaño del logo por defecto */
            height: auto; /* Mantén la proporción del logo */
        }

        .container {
            margin: 20px auto;
            max-width: 1200px; /* Aumenta el ancho del contenedor */
            background-color: rgba(255, 255, 255, 0.4); /* Fondo blanco con transparencia */
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px; /* Aumenta el espacio interno */
            min-height: 350px; /* Aumenta la altura mínima del contenedor */
        }

        .description {
            color: #080808;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 20px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 18px; /* Aumenta el tamaño del texto */
        }

        .button-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* Tres columnas por defecto */
            gap: 20px; /* Espacio entre los botones */
            margin-top: 20px;
        }

        button {
            background-color: rgba(31, 58, 84, 0.6);
            color: #ffffff;
            border: 1px solid #003f7f;
            border-radius: 25px;
            padding: 15px 20px; /* Aumenta el tamaño de los botones */
            font-size: 18px; /* Aumenta el tamaño del texto de los botones */
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:active {
            background-color: #003f7f;
        }

        .logout-button {
            background-color: #dc3545;
            border: 1px solid #dc3545;
            font-size: 16px; /* Aumenta el tamaño del texto del botón de cerrar sesión */
            padding: 10px 20px; /* Aumenta el tamaño del botón de cerrar sesión */
            margin-top: 30px;
            width: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .logout-button:hover {
            background-color: #a71d2a;
        }

        .logout-button:active {
            background-color: #7f1420;
        }

        .sucursales-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .sucursales-table th,
        .sucursales-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .sucursales-table th {
            background-color: rgba(31, 58, 84, 0.9);
            color: white;
        }

        .sucursales-table tr:hover {
            background-color: rgba(31, 58, 84, 0.1);
        }

        /* Media query para tablets (dos columnas) */
        @media (max-width: 1024px) {
            .button-container {
                grid-template-columns: repeat(2, 1fr); /* Dos columnas */
            }
        }

        /* Media query para celulares (una columna y ajustes de tamaño) */
        @media (max-width: 768px) {
            .button-container {
                grid-template-columns: repeat(1, 1fr); /* Una columna */
            }

            .container {
                max-width: 90%; /* Reduce el ancho del contenedor en pantallas pequeñas */
                padding: 20px; /* Reduce el espacio interno */
            }

            .header h1 {
                font-size: 20px; /* Reduce el tamaño del título */
            }

            .logo {
                width: 60px; /* Reduce el tamaño del logo */
                top: 5px; /* Ajusta la distancia desde la parte superior */
                left: 5px; /* Ajusta la distancia desde la parte izquierda */
            }

            .description {
                font-size: 16px; /* Reduce el tamaño del texto */
            }

            button {
                font-size: 14px; /* Reduce el tamaño del texto de los botones */
                padding: 6px 6px; /* Reduce el tamaño de los botones */
                max-width: 250px; /* Limita el ancho máximo de los botones */
                margin: 0 auto; /* Centra los botones */
            }

            .logout-button {
                font-size: 14px; /* Reduce el tamaño del texto del botón de cerrar sesión */
                padding: 8px 12px; /* Reduce el tamaño del botón de cerrar sesión */
                max-width: 250px; /* Limita el ancho máximo del botón */
            }
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <img src="../IMG/LOGO_LGC__AZUL.jpg" alt="Logo del Sistema" class="logo">
        <h1>Panel de Administración</h1>
    </div>

    <!-- Contenedor principal -->
    <div class="container">
        <!-- Descripción -->
        <section class="description-section">
            <p class="description">Bienvenido al panel de administración. Seleccione una acción:</p>
        </section>

        <!-- Botones de acciones -->
        <section class="actions-section">
            <form action="PANEL_ADMIN.php" method="POST">
                <div class="button-container">
                    <button type="submit" name="click_crear_formulario">Crear Formulario</button>
                    <button type="submit" name="click_asignar_formulario">Asignar Formulario</button>
                    <button type="submit" name="click_evaluar_formulario">Evaluar Formulario</button>
                    <button type="submit" name="click_respuestas_formulario">Ver Respuestas</button>
                    <button type="submit" name="click_crear_sucursal">Crear Sucursal</button>
                    <button type="submit" name="click_crear_departamento">Crear Departamento</button>
                    <button type="submit" name="click_crear_puesto">Crear Puesto</button>
                </div>
            </form>
        </section>
        <form action="PANEL_ADMIN.php" method="POST">
            <button type="submit" name="click_cerrar_sesion" class="logout-button">Cerrar Sesión</button>
        </form>

        <section class="sucursales-section">
            <h2>Lista de Sucursales</h2>
            <table class="sucursales-table">
                <thead>
                    <tr>
                        <th>ID Sucursal</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($sucursales)) {
                        foreach ($sucursales as $sucursal) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($sucursal['id_sucursal']) . "</td>";
                            echo "<td>" . htmlspecialchars($sucursal['nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($sucursal['direccion']) . "</td>";
                            echo "<td>" . htmlspecialchars($sucursal['telefono']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No hay sucursales registradas.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>