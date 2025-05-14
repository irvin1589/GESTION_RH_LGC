<?php
include('../MODELO/CL_CONEXION.php');

session_start();

// Verificación de autenticación y permisos (sin cambios)
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

$id_asignacion = $_GET['id_asignacion'] ?? null;
if (!$id_asignacion) {
    echo "ID de asignación no especificado o inválido.";
    exit();
}

// Conexión y consultas (sin cambios)
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

$stmt = $pdo->prepare("SELECT p.texto AS pregunta, r.respuesta, r.calificacion, r.observacion 
                      FROM respuesta r
                      JOIN pregunta p ON r.id_pregunta = p.id_pregunta
                      WHERE r.id_asignacion = ?");
$stmt->execute([$id_asignacion]);
$respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($respuestas)) {
    echo "No se encontraron respuestas para el ID de asignación: " . htmlspecialchars($id_asignacion);
    exit();
}

if (isset($_POST['regresar'])) {
    header('Location: ver_usuarios_formulario.php');
    exit();
}

$stmt_form = $pdo->prepare("SELECT id_formulario FROM asignacion_formulario WHERE id_asignacion = ?");
$stmt_form->execute([$id_asignacion]);
$id_formulario = $stmt_form->fetchColumn();

if (!$id_formulario) {
    echo "No se pudo encontrar el formulario relacionado.";
    exit();
}

$Bien = 0;
$Mal = 0;

foreach ($respuestas as $respuesta) {
    if ($respuesta['calificacion'] == 1) {
        $Bien++;
    } else {
        $Mal++;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Respuestas</title>
    <link rel="icon" type="image/x-icon" href="../IMG/logo-blanco-1.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7fb;
        margin: 0;
        padding: 20px;
        color: #2c3e50;
    }

    .container {
        max-width: 100%;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #34495e;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
    }

    .table-responsive {
        overflow-x: auto;
        margin-bottom: 20px;
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        min-width: 600px;
    }

    table thead {
        background-color: #3498db;
        color: #fff;
    }

    table th, table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    table tbody tr:hover {
        background-color: #e9ecef;
    }

    .btn-container {
        text-align: center;
        margin-top: 20px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 4px;
        background-color: transparent;
        color: #3498db;
        border: 2px solid #3498db;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
    }

    .btn:hover {
        background-color: #3498db;
        color: #fff;
    }

    .btn i {
        transition: color 0.3s ease;
    }

    .btn:hover i {
        color: #fff;
    }

    .graficas {
        text-align: center;
        margin: 20px auto;
        max-width: 1000px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    #graficaCalificaciones {
        width: 100%;
        max-width: 400px;
        height: auto;
        margin: 20px auto;
        display: none;
    }

    /* Estilos responsivos */
    @media (max-width: 768px) {
        .container, .graficas {
            padding: 15px;
        }
        
        h2 {
            font-size: 1.5rem;
        }
        
        table th, table td {
            padding: 8px 10px;
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        body {
            padding: 10px;
        }
        
        .container, .graficas {
            padding: 10px;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
            padding: 12px;
        }
    }

    /* Estilo para móviles muy pequeños */
    @media (max-width: 480px) {
        table {
            display: block;
            width: 100%;
        }
        
        table thead {
            display: none;
        }
        
        table tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            text-align: right;
            border-bottom: 1px solid #eee;
        }
    
        table tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 10px;
            text-align: left;
        }
        
        #graficaCalificaciones {
            max-width: 300px;
        }
    }
    @media (min-width: 780px) {
        #graficaCalificaciones {
            max-width: 500px;
            height: 400px !important;
            display: none; /* Mantenemos oculta inicialmente */
        }
        
        .graficas {
            max-width: 600px;
        }
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Respuestas del Formulario</h2>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pregunta</th>
                        <th>Respuesta</th>
                        <th>Calificación</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($respuestas as $respuesta): ?>
                        <tr>
                            <td data-label="Pregunta"><?= htmlspecialchars($respuesta['pregunta']) ?></td>
                            <td data-label="Respuesta"><?= htmlspecialchars($respuesta['respuesta']) ?></td>
                            <td data-label="Calificación"><?= $respuesta['calificacion'] == 1 ? 'Bien' : 'Mal' ?></td>
                            <td data-label="Observación"><?= htmlspecialchars($respuesta['observacion']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="btn-container">
            <form action="ver_usuarios_formulario1.php" method="get">
                <input type="hidden" name="id_formulario" value="<?= htmlspecialchars($id_formulario) ?>">
                <button type="submit" class="btn"><i class="fas fa-arrow-left"></i> Regresar</button>
            </form>
        </div>
    </div>

    <div class="graficas">
        <button type="button" id="btnGraficar" class="btn"><i class="fas fa-chart-pie"></i> Graficar</button>
        <canvas id="graficaCalificaciones"></canvas>
    </div>

    <script>
    const btnGraficar = document.getElementById('btnGraficar');
    const canvas = document.getElementById('graficaCalificaciones');
    let grafica = null;

    btnGraficar.addEventListener('click', function() {
        // Alternar visibilidad
        if (canvas.style.display === 'none') {
            // Mostrar gráfica
            canvas.style.display = 'block';
            
            // Si la gráfica no existe, crearla
            if (!grafica) {
                crearGrafica();
            } else {
                // Si ya existe, redibujarla
                grafica.update();
            }
        } else {
            // Ocultar gráfica
            canvas.style.display = 'none';
        }
    });

    function crearGrafica() {
        const ctx = canvas.getContext('2d');
        
        // Configuración específica para pantallas grandes
        const isLargeScreen = window.matchMedia("(min-width: 780px)").matches;
        const aspectRatio = isLargeScreen ? 1.5 : 1;
        
        grafica = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Bien', 'Mal'],
                datasets: [{
                    label: 'Respuestas',
                    data: [<?= $Bien ?>, <?= $Mal ?>],
                    backgroundColor: ['#2b95f2', '#c55bf6'],
                    borderColor: ['#0088ff', '#af04ff'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: aspectRatio,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Resumen de Calificaciones Individuales',
                        font: {
                            size: isLargeScreen ? 16 : 14
                        }
                    }
                }
            }
        });
    }

    // Redimensionar gráfica cuando cambia el tamaño de la ventana
    window.addEventListener('resize', function() {
        if (grafica) {
            grafica.resize();
        }
    });
    </script>
</body>
</html>