<?php
include_once('../MODELO/CL_CONEXION.php'); // Incluir configuración de la base de datos

class CL_TABLA_PUESTO extends CL_CONEXION
{
    protected $pdo;

    // public function __construct() {
    //     $this->pdo = new PDO('mysql:host=localhost;dbname=gestion_rh_lgc', 'root', '1234');
    //     $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // }

    public function listar_puestos($id_sucursal, $id_departamento, $selectedPuesto = '')
    {
        $pdo = $this->getPDO(); // Usar el método getPDO() de CL_CONEXION
        $sql = "SELECT id_puesto, nombre FROM puesto WHERE id_sucursal = :id_sucursal AND id_departamento = :id_departamento";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
        $stmt->bindParam(':id_departamento', $id_departamento, PDO::PARAM_INT);
        $stmt->execute();

        $html = '';
        $puestos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($puestos) === 1) {
            $selectedPuesto = $puestos[0]['id_puesto'];
        }

        foreach ($puestos as $row) {
            $id = $row['id_puesto'];
            $nombre = $row['nombre'];
            $selected = ($selectedPuesto == $id) ? 'selected' : '';
            $html .= "<option value='$id' $selected>$nombre</option>";
        }

        if (empty($html)) {
            $html = "<option value=''>No hay puestos disponibles</option>";
        }

        return $html;
    }

    // Método para guardar puesto en la base de datos
    public function guardar($puesto) {
        $pdo = $this->getPDO(); // Obtener la conexión usando getPDO()

        // Sentencia SQL para insertar el puesto
        $sql = "INSERT INTO puestos (id_puesto, nombre) 
                VALUES (:id_puesto, :nombre)";
        
        // Preparar la consulta
        $stmt = $pdo->prepare($sql);

        // Asignar los valores a los parámetros
        $stmt->bindParam(':id_puesto', $puesto->get_id_puesto());
        $stmt->bindParam(':nombre', $puesto->get_nombre());

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Puesto registrado con éxito.";
        } else {
            echo "Error al registrar el puesto.";
        }
    }

    // Método para crear un puesto en la base de datos
    public function crear_puesto($id_puesto, $nombre, $id_departamento, $id_sucursal) {
        // Obtener la conexión a través del método getPDO()
        $pdo = $this->getPDO();

        // Sentencia SQL para insertar el puesto
        $sql = "INSERT INTO puesto (id_puesto, nombre, id_departamento, id_sucursal) 
                VALUES (:id_puesto, :nombre, :id_departamento, :id_sucursal)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_puesto', $id_puesto);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id_departamento', $id_departamento);
        $stmt->bindParam(':id_sucursal', $id_sucursal);
        return $stmt->execute();
    }

    public function existe_puesto($id_puesto) {
        $sql = "SELECT COUNT(*) FROM puesto WHERE id_puesto = :id_puesto";
        $stmt = $this->getPDO()->prepare($sql);
        $stmt->bindParam(':id_puesto', $id_puesto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function listar_todos_los_puestos() {
        $sql = "SELECT id_puesto, nombre FROM puesto";
        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>