<?php
include_once('CL_CONEXION.php');

class CL_TABLA_ASIGNACION_FORMULARIO extends CL_CONEXION
{
    public function crear_asignacion($id_formulario, $id_usuario) {
        try {
            $sql = "INSERT INTO asignacion_formulario (fecha_asignacion, completado, id_usuario, id_formulario) 
                    VALUES (CURDATE(), 0, :id_usuario, :id_formulario)";
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':id_formulario', $id_formulario, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al crear la asignación: " . $e->getMessage());
            return false;
        }
    }

    public function listar_asignaciones() {
        try {
            $sql = "SELECT af.id_asignacion, f.nombre AS nombre_formulario, u.nombre AS nombre_usuario, 
                           af.fecha_asignacion, af.completado
                    FROM asignacion_formulario af
                    INNER JOIN formulario f ON af.id_formulario = f.id_formulario
                    INNER JOIN usuario u ON af.id_usuario = u.id_usuario";
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar asignaciones: " . $e->getMessage());
            return [];
        }
    }

    public function eliminar_asignacion($id_asignacion) {
        try {
            $sql = "DELETE FROM asignacion_formulario WHERE id_asignacion = :id_asignacion";
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->bindParam(':id_asignacion', $id_asignacion, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar la asignación: " . $e->getMessage());
            return false;
        }
    }

    public function get_asignaciones_user($id_usuario){
        try {
            $instruccion_sql = "SELECT 
                                    af.id_asignacion,
                                    af.fecha_asignacion,
                                    af.completado,
                                    af.id_formulario,
                                    f.nombre AS nombre_formulario,
                                    f.descripcion,
                                    f.fecha_creacion,
                                    f.fecha_limite,
                                    CASE 
                                        WHEN EXISTS (
                                            SELECT 1 FROM respuesta r 
                                            WHERE r.id_asignacion = af.id_asignacion
                                        )
                                        THEN 1
                                        ELSE 0
                                    END AS respondido
                                FROM 
                                    asignacion_formulario af
                                JOIN 
                                    formulario f ON af.id_formulario = f.id_formulario
                                WHERE 
                                    af.id_usuario = :id_usuario";
                                    
            $stmt = $this->getPDO()->prepare($instruccion_sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener las asignaciones del usuario: " . $e->getMessage());
            return false;
        }
    }

    public function listar_asignaciones_por_formulario($id_formulario) {
        try {
            $instruccion_sql = "SELECT * FROM asignacion_formulario WHERE id_formulario = :id_formulario";
            $stmt = $this->getPDO()->prepare($instruccion_sql);
            $stmt->bindParam(':id_formulario', $id_formulario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar asignaciones por formulario: " . $e->getMessage());
            return false;
        }
    }
    
}
?>