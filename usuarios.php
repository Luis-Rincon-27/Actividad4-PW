<?php
require_once 'database.php';

// Función para autenticar al usuario
function authenticate($nombre_usuario, $clave) {
    $dbh = connect();
    $stmt = $dbh->prepare("SELECT id FROM usuarios WHERE nombre_usuario = :nombre_usuario AND clave = :clave");
    $stmt->execute(array(':nombre_usuario' => $nombre_usuario, ':clave' => $clave));
    return $stmt->fetchColumn();
}