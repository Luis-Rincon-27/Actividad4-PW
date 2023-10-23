<?php
require_once 'config.php';

// Conexión a la base de datos
function connect() {
    try {
        return new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    } catch (PDOException $e) {
        exit("Error: " . $e->getMessage());
    }
}
?>