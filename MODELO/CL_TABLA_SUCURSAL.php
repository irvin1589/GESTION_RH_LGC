CL_TABLA_SUCURSAL.php
<?php
include_once('../MODELO/CL_CONEXION.php'); // Incluir configuración de la base de datos

class CL_TABLA_SUCURSAL extends CL_CONEXION {
    public function listar_sucursales($selectedSucursal = '') {
        $pdo = $this->getPDO();
        $sql = "SELECT id_sucursal, nombre FROM sucursal";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $html = '';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id_sucursal'];
            $nombre = $row['nombre'];
            $selected = ($selectedSucursal == $id) ? 'selected' : '';
            $html .= "<option value='$id' $selected>$nombre</option>";
        }

        return $html;
    }


    public function existe_sucursal($id_sucursal) {
        $pdo = $this->getPDO(); 
        $sql = "SELECT COUNT(*) FROM sucursal WHERE id_sucursal = :id_sucursal"; 
        $stmt = $pdo->prepare($sql); 
        $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT); 
        $stmt->execute(); 
        $count = $stmt->fetchColumn(); 
        return $count > 0;
    }

    public function guardar_sucursal($sucursal) {
        $pdo = $this->getPDO();
        $id_sucursal = $sucursal->get_id_sucursal();
        $nombre = $sucursal->get_nombre();
        $direccion = $sucursal->get_direccion();
        $telefono = $sucursal->get_telefono();

        
        $sql = "INSERT INTO sucursal (id_sucursal, nombre, direccion, telefono) 
                VALUES (:id_sucursal, :nombre, :direccion, :telefono)";
        
        try {
            
            $stmt = $pdo->prepare($sql);

           
            $stmt->bindParam(':id_sucursal', $id_sucursal);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':telefono', $telefono);

            
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            
            if ($e->getCode() === '23000') { 
                return "Error: El ID de la sucursal ya existe.";
            } else {
               
                return "Error al registrar la sucursal: " . $e->getMessage();
            }
        }
    }

    public function listar_todo_sucursales() {
        $pdo = $this->getPDO();
        $sql = "SELECT * FROM sucursal";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar_sucursal($id_sucursal) {
        try {
            $pdo = $this->getPDO();

            
            $pdo->beginTransaction();

            
            $sql1 = "DELETE FROM puesto WHERE id_sucursal = :id_sucursal";
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
            $stmt1->execute();

            
            $sql2 = "DELETE FROM departamento WHERE id_sucursal = :id_sucursal";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
            $stmt2->execute();

            $sql3 = "DELETE FROM sucursal WHERE id_sucursal = :id_sucursal";
            $stmt3 = $pdo->prepare($sql3);
            $stmt3->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
            $stmt3->execute();

            $pdo->commit();

            return true;
        } catch (PDOException $e) {
            error_log("Error al eliminar la sucursal: " . $e->getMessage());
            $pdo->rollBack();
            return false;
        }
    }


    public function obtener_sucursal($id_sucursal) {
        try {
            $sql = "SELECT * FROM sucursal WHERE id_sucursal = :id_sucursal";
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener la sucursal: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar_sucursal($id_sucursal, $nombre, $direccion, $telefono) {
        try {
            $sql = "UPDATE sucursal SET nombre = :nombre, direccion = :direccion, telefono = :telefono WHERE id_sucursal = :id_sucursal";
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar la sucursal: " . $e->getMessage());
            return false;
        }
    }

    public function listar_todas_las_sucursales() {
        $pdo = $this->getPDO();
        $sql = "SELECT * FROM sucursal";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>