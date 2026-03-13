<?php
require_once '../config/conexion.php';

echo "<h2>Prueba de Conexión</h2>";

if ($conexion->connect_error) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $conexion->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✅ Conexión a BD exitosa</p>";
    
    // Verificar tabla usuarios
    $result = $conexion->query("SHOW TABLES LIKE 'usuarios'");
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✅ Tabla 'usuarios' existe</p>";
        
        // Contar usuarios
        $count = $conexion->query("SELECT COUNT(*) as total FROM usuarios");
        $row = $count->fetch_assoc();
        echo "<p>Total de usuarios: " . $row['total'] . "</p>";
    } else {
        echo "<p style='color: red;'>Tabla usuarios no existe</p>";
    }
}

$conexion->close();
?>
