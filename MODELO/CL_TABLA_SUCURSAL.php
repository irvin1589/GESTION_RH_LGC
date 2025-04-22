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

        return $html; // Devuelve una cadena HTML
    }

    // Método para guardar sucursal
    public function existe_sucursal($id_sucursal) {
        $pdo = $this->getPDO(); // Obtiene la conexión PDO
        $sql = "SELECT COUNT(*) FROM sucursal WHERE id_sucursal = :id_sucursal"; // Consulta para verificar existencia
        $stmt = $pdo->prepare($sql); // Prepara la consulta
        $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT); // Asigna el valor del parámetro
        $stmt->execute(); // Ejecuta la consulta
        $count = $stmt->fetchColumn(); // Obtiene el número de filas encontradas
        return $count > 0; // Devuelve true si existe, false si no
    }

    public function guardar_sucursal($sucursal) {
        $pdo = $this->getPDO();
        $id_sucursal = $sucursal->get_id_sucursal();
        $nombre = $sucursal->get_nombre();
        $direccion = $sucursal->get_direccion();
        $telefono = $sucursal->get_telefono();

        // Sentencia SQL para insertar la sucursal
        $sql = "INSERT INTO sucursal (id_sucursal, nombre, direccion, telefono) 
                VALUES (:id_sucursal, :nombre, :direccion, :telefono)";
        
        try {
            // Preparar la consulta
            $stmt = $pdo->prepare($sql);

            // Asignar los valores a los parámetros
            $stmt->bindParam(':id_sucursal', $id_sucursal);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':telefono', $telefono);

            // Ejecutar la consulta
            $stmt->execute();
            return true; // Registro exitoso
        } catch (PDOException $e) {
            // Manejar error de clave duplicada
            if ($e->getCode() === '23000') { // Código de error para clave duplicada
                return "Error: El ID de la sucursal ya existe.";
            } else {
                // Otros errores
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
            $sql = "DELETE FROM sucursal WHERE id_sucursal = :id_sucursal";
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar la sucursal: " . $e->getMessage());
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