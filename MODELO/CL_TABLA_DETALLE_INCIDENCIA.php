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

    public function obtener_incidencia() {
        $pdo = $this->getPDO();
        $sql = "SELECT * FROM detalle_incidencia ORDER BY id_detalle_incidencia DESC LIMIT 1";
        $stmt = $pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve solo una fila
    }

    public function registrar_detalle_incidencia($datos) {
        $pdo = $this->getPDO();
        try {
            $sql = "INSERT INTO detalle_incidencia 
                     (id_usuario, id_incidencia_tipo, cantidad, descuento, fecha_inicio, fecha_termino, id_reporte) 
                     VALUES (:id_usuario, :id_incidencia_tipo, :cantidad, :descuento, :fecha_inicio, :fecha_termino, :id_reporte)";
            
            $stmt = $pdo->prepare($sql); // Usar prepare() en lugar de query()
            
            $stmt->execute([
                ':id_usuario' => $datos['id_usuario'],
                ':id_incidencia_tipo' => $datos['id_incidencia_tipo'],
                ':cantidad' => $datos['cantidad'],
                ':descuento' => $datos['descuento'],
                ':fecha_inicio' => $datos['fecha_inicio'],
                ':fecha_termino' => $datos['fecha_termino'],
                ':id_reporte' => $datos['id_reporte']
            ]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al registrar incidencia: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerIncidenciaPorId($id) {
        $pdo = $this->getPDO();
        try {
            $sql = "SELECT * FROM detalle_incidencia WHERE id_detalle_incidencia = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener incidencia: " . $e->getMessage());
            return false;
        }
    }
    
    public function actualizarIncidencia($datos) {
        $pdo = $this->getPDO();
        try {
            $sql = "UPDATE detalle_incidencia SET 
                    cantidad = :cantidad,
                    descuento = :descuento,
                    fecha_inicio = :fecha_inicio,
                    fecha_termino = :fecha_termino
                    WHERE id_detalle_incidencia = :id_detalle_incidencia";
            
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                ':cantidad' => $datos['cantidad'],
                ':descuento' => $datos['descuento'],
                ':fecha_inicio' => $datos['fecha_inicio'],
                ':fecha_termino' => $datos['fecha_termino'],
                ':id_detalle_incidencia' => $datos['id_detalle_incidencia']
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar incidencia: " . $e->getMessage());
            return false;
        }
    }

    public function btenerIncidenciaPorId($id) {
        $pdo = $this->getPDO();
        try {
            $sql = "SELECT * FROM detalle_incidencia WHERE id_detalle_incidencia = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener incidencia: " . $e->getMessage());
            return false;
        }
    }
    
    public function actualizarDescuento($id, $descuento) {
        $pdo = $this->getPDO();
        try {
            $sql = "UPDATE detalle_incidencia SET descuento = ? WHERE id_detalle_incidencia = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$descuento, $id]);
        } catch (PDOException $e) {
            error_log("Error al actualizar descuento: " . $e->getMessage());
            return false;
        }
    }
}