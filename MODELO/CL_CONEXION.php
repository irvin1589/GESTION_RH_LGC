<?php
class CL_CONEXION {
    protected $pdo;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'gestion_rh_lgc';
        $username = 'root';
        $password = '1234';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error al conectar: " . $e->getMessage();
        }
    }

    public function conectar() {
        return $this->pdo;
    }

    public function getPDO() {
        return $this->pdo;
    }
}
