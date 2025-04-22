<?php
$host = 'localhost';
$dbname = 'gestion_rh_lgc';
$username = 'root';
$password = ''; // sin contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos";
} catch (PDOException $e) {
    echo "Error al conectar: " . $e->getMessage();
}
?>