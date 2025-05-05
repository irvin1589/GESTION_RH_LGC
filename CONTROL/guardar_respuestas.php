<?php
session_start();
require_once '../MODELO/CL_CONEXION.php';

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

                $stmt = $pdo->prepare("INSERT INTO RESPUESTA (id_asignacion, id_pregunta, respuesta) VALUES (?, ?, ?)");
                $stmt->execute([$idAsignacion, $idPregunta, $respuesta]);
            }
        }

        $stmt = $pdo->prepare("UPDATE ASIGNACION_FORMULARIO SET completado = 1 WHERE id_asignacion = ?");
        $stmt->execute([$idAsignacion]);

        $pdo->commit();

        echo "<script>alert('Respuestas guardadas y asignación completada.'); window.location.href='formulario_exito.php';</script>";
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error al guardar las respuestas: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}
