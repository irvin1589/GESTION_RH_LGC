<?php
require_once '../MODELO/CL_CONEXION.php';
session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if (!isset($_POST['id_asignacion'])) {
    echo "Asignación no especificada.";
    exit;
}

$conn = new CL_CONEXION();
$pdo = $conn->getPDO();

$idAsignacion = $_POST['id_asignacion'];

// Obtener todos los id_respuesta relacionados a esa asignación
$stmt = $pdo->prepare("SELECT id_respuesta FROM RESPUESTA WHERE id_asignacion = ?");
$stmt->execute([$idAsignacion]);
$respuestas = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($respuestas as $idRespuesta) {
    $calificacion = $_POST["calificacion_$idRespuesta"] ?? null;
    $observacion = $_POST["observacion_$idRespuesta"] ?? null;

    $stmtUpdate = $pdo->prepare("
        UPDATE RESPUESTA 
        SET calificacion = ?, observacion = ?
        WHERE id_respuesta = ?
    ");
    $stmtUpdate->execute([$calificacion, $observacion, $idRespuesta]);
}

$stmt = $pdo->prepare("UPDATE ASIGNACION_FORMULARIO SET evaluado = 1 WHERE id_asignacion = ?");
$stmt->execute([$idAsignacion]);

header("Location: ver_respuestas.php?id_asignacion=$idAsignacion");
exit;
