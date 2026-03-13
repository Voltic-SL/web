<?php
ob_start();
session_start();
require_once '../config/conexion.php';

// Si ya está logueado, ir a horario
if (isset($_SESSION['dni'])) {
    header('Location: horario.php');
    exit();
}

$error = '';
$debug = '';

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = trim($_POST['dni'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($dni) || empty($password)) {
        $error = 'DNI y contraseña requeridos.';
    } else {
        // Buscar usuario
        $sql = "SELECT * FROM usuarios WHERE dni = '$dni' LIMIT 1";
        $result = mysqli_query($conexion, $sql);
        if (!$result) {
            $error = 'Error de BD: ' . mysqli_error($conexion);
        } else {
            
            if (mysqli_num_rows($result) == 1) {
                $usuario = mysqli_fetch_assoc($result);
                
                // Obtener la contraseña (columna: contraseña)
                $passwordValue = $usuario['contraseña'] ?? '';
                
                if (!empty($passwordValue)) {
                    // Verificar contraseña
                    $esCorrecta = password_verify($password, $passwordValue) || ($passwordValue === $password);
                    
                    if ($esCorrecta) {
                        // Login exitoso
                        session_regenerate_id();
                        $_SESSION['dni'] = $usuario['dni'];
                        $_SESSION['nombre'] = $usuario['nombre'] ?? '';
                        $_SESSION['rol'] = $usuario['rol'] ?? 'profesor';
                        
                        header('Location: horario.php');
                        exit();
                    } else {
                        $error = 'Contraseña incorrecta.';
                    }
                } else {
                    $error = 'Usuario sin contraseña configurada.';
                }
            } else {
                $error = 'DNI no encontrado.';
            }
        }
    }
}

include '../views/login.php';
?>
