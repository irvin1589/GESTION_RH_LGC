<?php
include('../MODELO/CL_FORMULARIO.php');
include('../MODELO/CL_SUCURSAL.php');
include('../MODELO/CL_DEPARTAMENTO.php');
include('../MODELO/CL_CONEXION.php');
include_once('../MODELO/CL_TABLA_FORMULARIO.php');
include_once('../VISTA/CL_INTERFAZ18.php');

session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: SISTEMA_RH.php');
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'Admin' && $_SESSION['tipo_usuario'] !== 'RH') {
    header('Location: acceso_denegado.php');
    exit();
}

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
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

            // Asegúrate de que no haya salida adicional
            header('Content-Type: text/plain');
            echo "ok";
            exit();
        } else {
            header('Content-Type: text/plain');
            echo "No se recibieron preguntas válidas.";
            exit();
        }
    }
    exit();
}

// Si no es una solicitud AJAX, muestra la interfaz normalmente
$interfaz = new CL_INTERFAZ18();
$interfaz->mostrar();