<?php
include_once('CL_CONEXION.php');

class CL_TABLA_DEPARTAMENTO extends CL_CONEXION
{
    public function listar_departamentos($id_sucursal, $selectedDepartamento = '')
    {
        $pdo = $this->getPDO();
        $sql = "SELECT id_departamento, nombre FROM departamento WHERE id_sucursal = :id_sucursal";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
        $stmt->execute();

        $html = '';
        $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Si solo hay un departamento, seleccionarlo automáticamente
        if (count($departamentos) === 1) {
            $selectedDepartamento = $departamentos[0]['id_departamento'];
        }

        foreach ($departamentos as $row) {
            $id = $row['id_departamento'];
            $nombre = $row['nombre'];
            $selected = ($selectedDepartamento == $id) ? 'selected' : '';
            $html .= "<option value='$id' $selected>$nombre</option>";
        }

        // Si no hay departamentos, agregar una opción predeterminada
        if (empty($html)) {
            $html = "<option value=''>No hay departamentos disponibles</option>";
        }

        return $html;
    }

    public function guardar($departamento, $sucursal){
        $pdo = $this->getPDO();
        $id_sucursal = $sucursal->get_id_sucursal();
        $nombre = $departamento->get_nombre();


        $sql = "INSERT INTO departamento (id_sucursal, nombre) 
        VALUES (:id_sucursal, :nombre)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_sucursal', $id_sucursal);
        $stmt->bindParam(':nombre', $nombre);

        if ($stmt->execute()) {
            return true; // Registro exitoso
        } else {
            return false; // Error al registrar
        }
    }
}
?>
