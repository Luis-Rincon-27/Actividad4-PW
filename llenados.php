<?php
require_once 'clientes.php';
require_once 'llenar_botellones.php';

// Comprobar si la sesión ya ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    // Iniciar la sesión
    session_start();
}

// Función para llenar botellones para un cliente específico
function llenar_botellones($cliente_cedula, $cantidad) {
    if (!$cliente_cedula || !$cantidad) {
        return array('status' => 'error', 'message' => 'Por favor, llena todos los campos.');
    }
    $dbh = connect();
    // Verificar si existe un cliente con la cédula proporcionada
    $stmt = $dbh->prepare("SELECT * FROM clientes WHERE cedula = :cliente_cedula");
    $stmt->execute(array(':cliente_cedula' => $cliente_cedula));
    if (!$stmt->fetch()) {
        return array('status' => 'error', 'message' => "No existe un cliente con la cédula $cliente_cedula.");
    }
    // Inserta el nuevo llenado
    $stmt = $dbh->prepare("INSERT INTO llenados (fecha_hora, cantidad, cliente_cedula) VALUES (NOW(), :cantidad, :cliente_cedula)");
    $stmt->execute(array(':cantidad' => $cantidad, ':cliente_cedula' => $cliente_cedula));
    return array('status' => 'success', 'message' => "Se ha registrado el llenado de $cantidad botellones para el cliente con la cédula $cliente_cedula.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear un nuevo cliente
    if (isset($_POST['crear_cliente'])) {
        $cliente_id = create_cliente($_POST['nombre_cliente'], $_POST['zona_id'], $_POST['cedula']);
        $_SESSION['mensaje'] = "Se ha registrado al cliente {$_POST['nombre_cliente']} con el ID $cliente_id.";
    }

    // Llenar botellones para un cliente específico
    if (isset($_POST['llenar_botellones'])) {
        llenar_botellones($_POST['botellon_id'], $_POST['cantidad']);
        $_SESSION['mensaje'] = "Se ha registrado el llenado de {$_POST['cantidad']} botellones para el usuario con el ID {$_POST['botellon_id']}.";
    }
}

// Obtén todas las zonas para la combobox
$zonas = get_zonas();
?>