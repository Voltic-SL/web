<?php
// Este archivo conecta a la base de datos MySQL.

// Datos de conexión (cambiar si es necesario).
$host = '192.168.14.24'; // Dirección del servidor MySQL.
$user = 'root'; // Usuario de MySQL.
$password = ''; // Contraseña de MySQL.
$database = 'reto'; // Nombre de la base de datos.

// Crear conexión.
$conexion = new mysqli($host, $user, $password, $database);

// Si hay error, parar y mostrar mensaje.
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Función para controlar si la sesión está inactiva.
function timeSesion() {
    // Si han pasado 30 minutos (1800 segundos) desde la última actividad.
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        // Redirigir a logout.
        header("Location: logout.php");
        exit();
    }
    // Actualizar tiempo de actividad.
    $_SESSION['LAST_ACTIVITY'] = time();
}

// Llamar a la función para verificar sesión.
timeSesion();
?>
