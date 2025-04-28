<?php
include_once('CL_CONEXION.php');
class CL_TABLA_INCIDENCIA extends CL_CONEXION();
{
    protected $pdo;

    public function listar_incidencias() {
        $sql = "SELECT * FROM incidencia";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar_incidencia($incidencia) {
        $codigo = $incidencia->get_codigo();
        $descripcion = $incidencia->get_descripcion();
        $tipo = $incidencia->get_tipo();

        $sql = "INSERT INTO incidencia (codigo, descripcion, tipo) VALUES (:codigo, :descripcion, :tipo)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':tipo', $tipo);
        return $stmt->execute();
    }

    public function obtener_incidencia_por_codigo($codigo) {
        $sql = "SELECT * FROM incidencia WHERE codigo = :codigo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminar_incidencia($codigo) {
        $sql = "DELETE FROM incidencia WHERE codigo = :codigo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        return $stmt->execute();
    }

    public function actualizar_incidencia($incidencia) {
        $codigo = $incidencia->get_codigo();
        $descripcion = $incidencia->get_descripcion();
        $tipo = $incidencia->get_tipo();

        $sql = "UPDATE incidencia SET descripcion = :descripcion, tipo = :tipo WHERE codigo = :codigo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':tipo', $tipo);
        return $stmt->execute();
    }


}

