<?php
session_start();
require_once '../MODELO/CL_CONEXION.php';

session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id_asignacion'])) {
        echo "ID de asignación no proporcionado.";
        exit;
    }

    $idAsignacion = $_POST['id_asignacion'];
    $conn = new CL_CONEXION();
    $pdo = $conn->getPDO();

    try {
        $pdo->beginTransaction();

        foreach ($_POST as $key => $value) {
            
            if (strpos($key, 'respuesta_') === 0) {
                $idPregunta = str_replace('respuesta_', '', $key);
                $respuesta = trim($value);

                $stmt = $pdo->prepare("INSERT INTO respuesta (id_asignacion, id_pregunta, respuesta) VALUES (?, ?, ?)");
                $stmt->execute([$idAsignacion, $idPregunta, $respuesta]);
            }
        }

        $stmt = $pdo->prepare("UPDATE asignacion_formulario SET completado = 1 WHERE id_asignacion = ?");
        $stmt->execute([$idAsignacion]);

        $pdo->commit();
        exit(header('Location: ../CONTROL/formulario_exito.php'));

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error al guardar las respuestas: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}
