<?php
// Script para crear un usuario de prueba

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'reto';

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

echo "✓ Conectado a la BD<br><br>";

// Ver estructura de la tabla usuarios
$result = $conexion->query("DESCRIBE usuarios");
echo "<strong>Estructura de la tabla usuarios:</strong><br>";
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")<br>";
}

echo "<hr><br>";

// Verificar si hay usuarios
$usuarios = $conexion->query("SELECT COUNT(*) as total FROM usuarios");
$count = $usuarios->fetch_assoc();
echo "Total de usuarios: " . $count['total'] . "<br><br>";

// Crear usuario de prueba si no existen
if ($count['total'] == 0) {
    // Determinar qué columna usar para la contraseña
    $result = $conexion->query("DESCRIBE usuarios");
    $hasPassword = false;
    $hasContrasena = false;
    
    while ($row = $result->fetch_assoc()) {
        if ($row['Field'] === 'password') $hasPassword = true;
        if ($row['Field'] === 'contrasena') $hasContrasena = true;
    }
    
    echo "Creando usuario de prueba...<br>";
    
    $dni = '12345678A';
    $nombre = 'Juan';
    $apellido = 'Pérez';
    $ce = 'juan@test.com';
    $pass = 'password123';
    $rol = 'profesor';
    $familia = 'DAW';
    
    if ($hasPassword) {
        // Intentar con hash
        $hashedPass = password_hash($pass, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (dni, nombre, apellido, ce, password, rol, familia) 
                VALUES ('$dni', '$nombre', '$apellido', '$ce', '$hashedPass', '$rol', '$familia')";
    } elseif ($hasContrasena) {
        $sql = "INSERT INTO usuarios (dni, nombre, apellido, ce, contrasena, rol, familia) 
                VALUES ('$dni', '$nombre', '$apellido', '$ce', '$pass', '$rol', '$familia')";
    } else {
        die("No se encontró columna de contraseña");
    }
    
    if ($conexion->query($sql)) {
        echo "✓ Usuario creado exitosamente<br>";
        echo "DNI: $dni<br>";
        echo "Contraseña: $pass<br>";
    } else {
        echo "Error: " . $conexion->error;
    }
} else {
    echo "<strong>Ya hay usuarios en la BD. Listado:</strong><br>";
    $result = $conexion->query("SELECT dni, nombre, apellido, rol FROM usuarios LIMIT 10");
    while ($row = $result->fetch_assoc()) {
        echo $row['dni'] . " - " . $row['nombre'] . " " . $row['apellido'] . " (Rol: " . $row['rol'] . ")<br>";
    }
}

$conexion->close();
?>
