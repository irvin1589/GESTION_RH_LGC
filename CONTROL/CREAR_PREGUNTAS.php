<?php
include('../MODELO/CL_FORMULARIO.php');
include('../MODELO/CL_SUCURSAL.php');
include('../MODELO/CL_DEPARTAMENTO.php');
include('../MODELO/CL_CONEXION.php');
include('../VISTA/CL_INTERFAZ02.php');
include_once('../VISTA/CL_INTERFAZ18.php');
include_once('../MODELO/CL_TABLA_FORMULARIO.php');

$interfaz = new CL_INTERFAZ18();
$interfaz->mostrar();

session_start();

echo 'EL ID DEL FORMULARIO ES: '. $_SESSION['formularioId'];
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php'); // O la página de inicio de sesión
    exit();
}

// Permitir acceso a Admin y RH
if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

$rawInput = file_get_contents('php://input');

if (!empty($rawInput)) {
    $datos = json_decode($rawInput, true);

    if (isset($datos['preguntas']) && is_array($datos['preguntas'])) {
        $preguntas = $datos['preguntas'];
        $idFormulario = $_SESSION['formularioId'];
        $orden = 1;

        $conn = new CL_CONEXION();
        foreach ($preguntas as $pregunta) {
            $texto = $pregunta['texto'];
            $tipo = $pregunta['tipo'];

            $pdo = $conn->getPDO();
            $stmt = $pdo->prepare("SELECT id_tipo_pregunta FROM TIPO_PREGUNTA WHERE nombre_tipo = ?");
            $stmt->execute([$tipo]);
            $idTipoPregunta = $stmt->fetchColumn();

            $stmt = $pdo->prepare("INSERT INTO PREGUNTA (texto, orden, id_tipo_pregunta, id_formulario)
                                   VALUES (?, ?, ?, ?)");
            $stmt->execute([$texto, $orden, $idTipoPregunta, $idFormulario]);
            $idPregunta = $pdo->lastInsertId();
            $orden++;

            if (isset($pregunta['opciones']) && is_array($pregunta['opciones'])) {
                foreach ($pregunta['opciones'] as $opcion) {
                    $stmt = $pdo->prepare("INSERT INTO OPCION_PREGUNTA (texto_opcion, valor_opcion, id_pregunta)
                                           VALUES (?, ?, ?)");
                    $stmt->execute([$opcion, $opcion, $idPregunta]);
                }
            }
        }

        echo "Formulario guardado correctamente.";
    } else {
        echo "No se recibieron preguntas válidas.";
    }
}
