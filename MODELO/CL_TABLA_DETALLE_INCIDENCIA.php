<?php

include_once('CL_CONEXION.php');

class CL_TABLA_DETALLE_INCIDENCIA extends CL_CONEXION
{
    protected $pdo;

    public function registrar($id_usuario, $id_incidencia_tipo, $cantidad, $descuento, $fecha_inicio, $fecha_termino, $id_reporte) {
        $pdo = $this->getPDO();
        $sql = "INSERT INTO detalle_incidencia 
                (id_usuario, id_incidencia_tipo, cantidad, descuento, fecha_inicio, fecha_termino, id_reporte) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id_usuario, $id_incidencia_tipo, $cantidad, $descuento, $fecha_inicio, $fecha_termino, $id_reporte]);
    }

    public function registrar_detalle_incidencia($datos) {
        $pdo = $this->getPDO();
        $sql = "INSERT INTO detalle_incidencia (id_usuario, id_incidencia_tipo, cantidad, descuento, fecha_inicio, fecha_termino) 
                VALUES (:id_usuario, :id_incidencia_tipo, :cantidad, :descuento, :fecha_inicio, :fecha_termino)";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id_usuario' => $datos['id_usuario'],
            ':id_incidencia_tipo' => $datos['id_incidencia_tipo'],
            ':cantidad' => $datos['cantidad'],
            ':descuento' => $datos['descuento'],
            ':fecha_inicio' => $datos['fecha_inicio'],
            ':fecha_termino' => $datos['fecha_termino'],
        ]);
    }
}