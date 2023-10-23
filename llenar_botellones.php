<?php
session_start();
require_once 'clientes.php';
require_once 'llenados.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cliente_cedula']) && isset($_POST['cantidad'])) {
        llenar_botellones($_POST['cliente_cedula'], $_POST['cantidad']);
        echo "Se ha registrado el llenado de {$_POST['cantidad']} botellones para el usuario con la cédula {$_POST['cliente_cedula']}.";
    }
}

