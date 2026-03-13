<?php
// Esta vista muestra el menú para administradores.
setcookie('nombre_cookie', 'valor', [
    'expires' => time() + 3600, // Expira en una hora
    'httponly' => true,
])
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Administrador</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" href="../css/basic.css">
    <link rel="stylesheet" href="../css/admin_menu.css">
</head>
<body>
    <a href="/php/horario.php" class="btn-volver">← Volver</a>

    <div class="container">
        <div class="admin-container">
            <div class="admin-header">
                <h1>Panel Administrador</h1>
                <p>Bienvenido, <?php echo htmlspecialchars($nombre_admin); ?></p>
            </div>

            <div class="menu-buttons">
                <a href="/php/usuario2.php" class="btn-menu">
                    <span class="icon">👤</span>
                    Crear Nuevo Usuario
                </a>

                <a href="/php/ver_usuarios.php" class="btn-menu">
                    <span class="icon">📋</span>
                    Ver Información de Usuarios
                </a>

                <a href="/php/admin_ausencias.php" class="btn-menu">
                    <span class="icon">📄</span>
                    Gestionar Ausencias
                </a>
            </div>
        </div>
    </div>
    <footer>
        <p>© 2026 Voltic. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
