<?php
include_once('CL_CONEXION.php'); // Asegúrate de incluir la clase de conexión

class CL_TABLA_FORMULARIO extends CL_CONEXION {
    public function listar_todos_los_formularios() {
        try {
            $sql = "SELECT id_formulario, nombre, fecha_limite FROM formulario"; // Ajusta el nombre de la tabla si es necesario
            $stmt = $this->getPDO()->prepare($sql); // Usa el método getPDO() de la clase base
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar los formularios: " . $e->getMessage());
            return [];
        }
    }

    public function obtener_formulario_por_id($id_formulario) {
        try {
            $sql = "SELECT id_formulario, nombre, descripcion, fecha_creacion, fecha_limite, id_sucursal, id_departamento, id_puesto 
                    FROM formulario 
                    WHERE id_formulario = :id_formulario";
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->bindParam(':id_formulario', $id_formulario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve un arreglo asociativo con los datos del formulario
        } catch (PDOException $e) {
            error_log("Error al obtener el formulario: " . $e->getMessage());
            return null;
        }
    }

    public function guardar_formulario(CL_FORMULARIO $x){
        try {
            $pdo = $this->getPDO();
    
            $sql = "INSERT INTO formulario (nombre, descripcion, fecha_limite, id_sucursal, id_departamento, id_puesto)
                    VALUES (:nombre, :descripcion, :fecha_limite, :id_sucursal, :id_departamento, :id_puesto)";
    
            $stmt = $pdo->prepare($sql);
    
            $stmt->bindValue(':nombre', $x->get_nombre(), PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $x->get_descripcion(), PDO::PARAM_STR);
            $stmt->bindValue(':fecha_limite', $x->get_fecha_limite(), PDO::PARAM_STR);
            $stmt->bindValue(':id_sucursal', $x->get_sucursal_id(), PDO::PARAM_INT);
            $stmt->bindValue(':id_departamento', $x->get_departamentoId(), PDO::PARAM_INT);
            $stmt->bindValue(':id_puesto', $x->get_puestoId(), PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $pdo->lastInsertId();
    
        } catch (PDOException $e) {
            error_log("Error al guardar el formulario: " . $e->getMessage());
            return false;
        }

    }
}