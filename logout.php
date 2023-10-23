<?php
// Comprobar si la sesión ya ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    // Iniciar la sesión
    session_start();
}

// Destruir la sesión
session_destroy();
?>