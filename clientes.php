<?php
require_once 'database.php';

// Función para crear un nuevo cliente
function create_cliente($nombre, $zona_id, $cedula) {
    if (!$nombre || !$zona_id || !$cedula) {
        return array('status' => 'error', 'message' => 'Por favor, llena todos los campos.');
    } elseif (strlen($cedula) < 7 || strlen($cedula) > 8) {
        return array('status' => 'error', 'message' => 'La cédula debe tener 7 u 8 caracteres.');
    }
    $dbh = connect();
    // Verificar si ya existe un cliente con la misma cédula
    $stmt = $dbh->prepare("SELECT * FROM clientes WHERE cedula = :cedula");
    $stmt->execute(array(':cedula' => $cedula));
    if ($stmt->fetch()) {
        return array('status' => 'error', 'message' => "Ya existe un cliente con la cédula $cedula.");
    }
    
    // Crear el nuevo cliente
    $stmt = $dbh->prepare("INSERT INTO clientes (nombre, zona_id, cedula) VALUES (:nombre, :zona_id, :cedula)");
    $stmt->execute(array(':nombre' => $nombre, ':zona_id' => $zona_id, ':cedula' => $cedula));
    return array('status' => 'success', 'id' => $dbh->lastInsertId());
}


// Función para obtener todas las zonas
function get_zonas() {
    $dbh = connect();
    return $dbh->query("SELECT * FROM zonas")->fetchAll(PDO::FETCH_ASSOC);
}

// Función para buscar clientes por ID o nombre
function buscar_clientes($busqueda) {
    $dbh = connect();
    $stmt = $dbh->prepare("SELECT * FROM clientes WHERE id = :busqueda OR nombre LIKE :busqueda");
    $stmt->execute(array(':busqueda' => $busqueda, ':busqueda' => "%$busqueda%"));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>