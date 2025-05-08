<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CREAR FORMULARIO | LA GRAN CIUDAD</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1f3a54;
            --secondary-color: #941C82;
            --danger-color: #e74c3c;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 8px;
            --box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--dark-color);
            background-image: url('../IMG/evaluaciones.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .form-container {
            background-color: white;
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 500px;
            margin: 2rem;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .form-header h2 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .form-header::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: var(--secondary-color);
            margin: 0.5rem auto;
            border-radius: 3px;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            transition: var(--transition);
            background-color: var(--light-color);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(31, 58, 84, 0.2);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            background-size: 1rem;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 120px;
            height: auto;
            z-index: 3;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            width: 100%;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #2c577c;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            align-items: stretch;
        }

        .btn-group .btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.8rem 1.5rem; /* Ajuste de padding para consistencia */
            min-height: 44px; 
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .form-header h2 {
                font-size: 1.5rem;
            }
            
            .btn-group {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .btn-group .btn {
            width: 100%;
        }

            .logo {
                opacity: 0;
            }
    }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-container {
            animation: fadeIn 0.5s ease-out;
        }

        /* Efecto hover para los selects */
        select:hover {
            cursor: pointer;
        }
    </style>
</head>
<body>
<img src="../IMG/LOGO_LGC__AZUL-1.png" alt="Logo La Gran Ciudad" class="logo">
    <div class="form-container">
        <div class="form-header">
            <h2><i class="fas fa-file-alt"></i> CREACIÓN DE FORMULARIO</h2>
        </div>
        
        <form method="POST" action="CREAR_FORMULARIO.php">
            <div class="form-group">
                <label for="sucursal"><i class="fas fa-building"></i> Sucursal:</label>
                <select id="sucursal" name="caja_opcion1" onchange="this.form.submit()" class="form-control">
                    {{sucursales}}
                </select>
            </div>

            <div class="form-group">
                <label for="departamento"><i class="fas fa-sitemap"></i> Departamento:</label>
                <select id="departamento" name="caja_opcion2" onchange="this.form.submit()" class="form-control">
                    {{departamentos}}
                </select>
            </div>

            <div class="form-group">
                <label for="puesto"><i class="fas fa-user-tie"></i> Puesto:</label>
                <select id="puesto" name="caja_opcion3" class="form-control">
                    {{puestos}}
                </select>
            </div>

            <div class="form-group">
                <label for="nombre"><i class="fas fa-heading"></i> Nombre Formulario:</label>
                <input type="text" id="nombre" name="caja_texto1" placeholder="Nombre del formulario" class="form-control">
            </div>

            <div class="form-group">
                <label for="descripcion"><i class="fas fa-align-left"></i> Descripción:</label>
                <input type="text" id="descripcion" name="caja_texto2" placeholder="Descripción del formulario" class="form-control">
            </div>

            <div class="form-group">
                <label for="fecha"><i class="fas fa-calendar-alt"></i> Fecha límite:</label>
                <input type="date" id="fecha" name="caja_texto3" class="form-control">
            </div>

            <div class="btn-group">
                <button type="submit" name="click_siguiente" class="btn btn-primary">
                    SIGUIENTE
                </button>
                <button type="submit" name="click_regresar" class="btn btn-danger">
                    REGRESAR
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Establecer fecha mínima como hoy
            const dateInput = document.getElementById('fecha');
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
            
            // Mejorar la experiencia de usuario
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>