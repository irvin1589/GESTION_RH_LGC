<?php
include_once('../MODELO/CL_CONEXION.php');

class CL_TABLA_REVISTA extends CL_CONEXION
{
    protected $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=gestion_rh_lgc', 'root', '');
    }

    public function guardar($revista)
    {
        try {
            $query = "INSERT INTO revista (titulo, contenido, archivo_pdf, fecha_publicacion, autor) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($query);

            $stmt->execute([
                $revista->get_titulo(),
                $revista->get_contenido(),
                $revista->get_archivo_pdf(),
                $revista->get_fecha_publicacion(),
                $revista->get_autor()
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Error al guardar revista: " . $e->getMessage());
            return false;
        }
    }
    public function listar_todas()
    {
        $query = "SELECT * FROM revista ORDER BY fecha_publicacion DESC";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>