<?php
// Esta página permite a los admins crear nuevos usuarios.

// Iniciar sesión.
session_start();

// Conectar a BD.
require_once '../config/conexion.php';

// Verificar conexión.
if (!isset($conexion) || !($conexion instanceof mysqli)) {
    die('Error de conexión a la base de datos.');
}

// Función para mantener sesión activa (si existe).
if (function_exists('timeSesion')) {
    timeSesion();
}

// Si no hay sesión, ir a login.
if (!isset($_SESSION['dni'])) {
    header('Location: index.php');
    exit();
}

// Verificar que sea admin.
$dniAdmin = $_SESSION['dni'];
$sql = "SELECT rol FROM usuarios WHERE dni = '$dniAdmin'";
$resAdmin = mysqli_query($conexion, $sql);
$adminRow = mysqli_num_rows($resAdmin) ? mysqli_fetch_assoc($resAdmin) : null;

if (($adminRow['rol'] ?? '') !== 'admin') {
    header('Location: horario.php');
    exit();
}

// Mensaje para errores o éxito.
$mensaje = '';
$ok = false;

// Asumir columna de contraseña es 'contraseña'.
$passwordColumn = 'contraseña';

// Si se envió formulario.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Tomar datos.
    $dni = trim($_POST['dni'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $ce = trim($_POST['ce'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = trim($_POST['rol'] ?? '');
    $familia = trim($_POST['familia'] ?? '');

    // Validar campos obligatorios.
    if ($dni === '' || $nombre === '' || $apellido === '' || $ce === '' || $password === '' || $rol === '') {
        $mensaje = 'Faltan campos obligatorios.';
    }
    // Validar DNI.
    elseif (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
        $mensaje = 'DNI debe ser 8 números y 1 letra mayúscula.';
    }
    // Validar nombre.
    elseif (!preg_match('/^[a-zA-ZáéíóúAÉÍÓÚñÑ\s]+$/', $nombre)) {
        $mensaje = 'Nombre solo letras.';
    }
    // Validar apellido.
    elseif (!preg_match('/^[a-zA-ZáéíóúAÉÍÓÚñÑ\s]+$/', $apellido)) {
        $mensaje = 'Apellido solo letras.';
    }
    // Validar contraseña.
    elseif (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{6,}$/', $password)) {
        $mensaje = 'Contraseña: mayúscula, número, símbolo, mínimo 6.';
    }
    // Validar rol.
    elseif (!in_array($rol, ['profesor', 'admin'], true)) {
        $mensaje = 'Rol debe ser profesor o admin.';
    }
    else {

        // Ver si DNI ya existe.
        $sql_check = "SELECT dni FROM usuarios WHERE dni = '$dni'";
        $exists = mysqli_query($conexion, $sql_check);

        if (mysqli_num_rows($exists) > 0) {
            $mensaje = 'Ese DNI ya existe.';
        }
        else {

            // Hashear contraseña.
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insertar usuario.
            $sql = "INSERT INTO usuarios (dni, nombre, apellido, ce, `$passwordColumn`, rol, familia)
                    VALUES ('$dni', '$nombre', '$apellido', '$ce', '$passwordHash', '$rol', '$familia')";

            if (mysqli_query($conexion, $sql)) {
                $ok = true;
                $mensaje = 'Usuario creado correctamente.';
                $_POST = []; // Limpiar formulario.
            } else {
                $mensaje = 'Error al crear usuario.';
            }
        }
    }
}

// Obtener nombre del admin.
$dni_profesor = $_SESSION['dni'];
$sql = "SELECT nombre FROM usuarios WHERE dni = '$dni_profesor'";
$res_nombre = mysqli_query($conexion, $sql);
$nombre_profesor = mysqli_num_rows($res_nombre) === 1 ? mysqli_fetch_assoc($res_nombre)['nombre'] : $dni_profesor;

// Mostrar la vista.
include '../views/usuario22.php';

// Cerrar conexión.
if (isset($conexion) && $conexion instanceof mysqli) {
    $conexion->close();
}
?>
