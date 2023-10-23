<?php
session_start();
require_once 'clientes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = create_cliente($_POST['nombre_cliente'], $_POST['zona_id'], $_POST['cedula']);
    if ($result['status'] == 'success') {
        echo "Se ha registrado al cliente {$_POST['nombre_cliente']} con la cédula {$_POST['cedula']} y el ID {$result['id']}.";
    } else {
        echo $result['message'];
    }
}
