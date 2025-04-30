<?php

include_once('CL_CONEXION.php');

class CL_TABLA_INCIDENCIA_TIPO extends CL_CONEXION
{
    protected $pdo;

    public function listar_tipos_incidencia($selectedTipo = '')
{
    $pdo = $this->getPDO();
    $sql = "SELECT id_incidencia_tipo, descripcion, calculo_variable FROM incidencia_tipo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $html = '';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['id_incidencia_tipo'];
        $descripcion = $row['descripcion'];
        $calculo_variable = $row['calculo_variable'] ?? '';
        $selected = ($selectedTipo == $id) ? 'selected' : '';
        $html .= "<option value='$id' data-variable='$calculo_variable' $selected>$descripcion</option>";
    }

    return $html;
}

    public function es_variable($id_incidencia_tipo) {
        $pdo = $this->getPDO();
        $sql = "SELECT calculo_variable FROM incidencia_tipo WHERE id_incidencia_tipo = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_incidencia_tipo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($row['calculo_variable']);
    }

    public function obtener_descuento_fijo($id_incidencia_tipo) {
        $pdo = $this->getPDO();
        $sql = "SELECT descuento FROM incidencia_tipo WHERE id_incidencia_tipo = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_incidencia_tipo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['descuento'] ?? 0;
    }

    public function obtener_tipo_incidencia($codigo_incidencia) {
        $query = "SELECT * FROM INCIDENCIA_TIPO WHERE codigo_incidencia = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $codigo_incidencia);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function get_descuento_fijo($id_tipo_incidencia) {
        $pdo = $this->getPDO();
        $sql = "SELECT descuento FROM incidencia_tipo WHERE id_incidencia_tipo = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_tipo_incidencia]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['descuento'] ?? null; // Devuelve el descuento o NULL si no existe
    }
    
}
?>