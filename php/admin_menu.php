<?php
// Esta página es el menú de admin. Solo admins pueden entrar.

// Iniciar sesión.
session_start();

// Conectar a BD.
require_once '../config/conexion.php';

// Si no hay sesión, ir a login.
if (!isset($_SESSION['dni'])) {
    header('Location: index.php');
    exit();
}

// DNI del admin.
$dni_admin = $_SESSION['dni'];

// Buscar rol y nombre.
$sql = "SELECT rol, nombre FROM usuarios WHERE dni = '$dni_admin'";
$res = mysqli_query($conexion, $sql);
if (!$res) {
    die("Error de base de datos");
}

if (mysqli_num_rows($res) !== 1) {
    header("Location: horario.php");
    exit();
}

$usuario = mysqli_fetch_assoc($res);

// Verificar si es admin.
$rol = $usuario['rol'] ?? '';
if ($rol !== 'admin') {
    header("Location: horario.php");
    exit();
}

// Nombre del admin.
$nombre_admin = $_SESSION['nombre'] ?? $usuario['nombre'] ?? $dni_admin;

// Mostrar la vista.
include '../views/admin_menu2.php';

// Cerrar conexión.
mysqli_close($conexion);
?>
