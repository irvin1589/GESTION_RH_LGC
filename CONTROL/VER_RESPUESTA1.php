<?php
include('../MODELO/CL_CONEXION.php');

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

// Verificar si el usuario tiene permisos
if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

// Verificar si se recibió el ID de la asignación
$id_asignacion = $_GET['id_asignacion'] ?? null;
if (!$id_asignacion) {
    echo "ID de asignación no especificado o inválido.";
    exit();
}

// Conexión a la base de datos
$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

// Consultar las respuestas asociadas al ID de asignación
$stmt = $pdo->prepare("SELECT p.texto AS pregunta, r.respuesta, r.calificacion, r.observacion 
                      FROM RESPUESTA r
                      JOIN PREGUNTA p ON r.id_pregunta = p.id_pregunta
                      WHERE r.id_asignacion = ?");
$stmt->execute([$id_asignacion]);
$respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Depuración: Verifica si se obtuvieron resultados
if (empty($respuestas)) {
    echo "No se encontraron respuestas para el ID de asignación: " . htmlspecialchars($id_asignacion);
    exit();
}
if (isset($_POST['regresar'])) {
    header('Location: ver_usuarios_formulario.php');
    exit();
}


$stmt_form = $pdo->prepare("SELECT id_formulario FROM ASIGNACION_FORMULARIO WHERE id_asignacion = ?");
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
        max-width: 1000px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #34495e;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
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

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        font-size: 15px;
        font-weight: 600;
        text-transform: uppercase;
        border: none;
        border-radius: 25px;
        background-color: transparent;
        color: #3498db;
        border: 2px solid #3498db;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn:hover {
        background-color: #3498db;
        color: #fff;
    }

    .btn i {
        color: #3498db;
        transition: color 0.3s ease;
    }

    .btn:hover i {
        color: #fff;
    }
    .graficar{
        margin-bottom: 20px;
    }

    .graficas {
        text-align: center;
        margin-top: 20px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    #graficaCalificaciones {
    width: 400px;
    height: 400px;
    margin: 0 auto;
    display: none;
}
</style>
</head>
<body>
    <div class="container">
        <h2>Respuestas del Formulario</h2>
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
                        <td><?= htmlspecialchars($respuesta['pregunta']) ?></td>
                        <td><?= htmlspecialchars($respuesta['respuesta']) ?></td>
                        <td><?= $respuesta['calificacion'] == 1 ? 'Bien' : 'Mal' ?></td>
                        <td><?= htmlspecialchars($respuesta['observacion']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form action="ver_usuarios_formulario1.php" method="get">
            <input type="hidden" name="id_formulario" value="<?= htmlspecialchars($id_formulario) ?>">
            <button type="submit" class="btn"><i class="fas fa-arrow-left"></i> Regresar</button>
        </form>
    </div>

    <div class="graficas">
        <button type="button" id="btnGraficar" class="btn graficar"><i class="fas fa-chart-pie"></i> GRAFICAR</button>
        <canvas id="graficaCalificaciones" style="display:none; width:400px; height:400px;"></canvas>
    </div>
    <script>
    const btnGraficar = document.getElementById('btnGraficar');
    const canvas = document.getElementById('graficaCalificaciones');
    let graficaInicializada = false;
    let grafica;

    btnGraficar.addEventListener('click', () => {
        if (!graficaInicializada) {
            const ctx = canvas.getContext('2d');
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
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Resumen de Calificaciones Individuales',
                        }
                    }
                }
            });
            graficaInicializada = true;
        }
        canvas.style.display = canvas.style.display === 'none' ? 'block' : 'none';
    });
</script>
</body>
</html>