<?php //require_once __DIR__ . '/añadirhorariologica.php'; 
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
    <a href="horario.php" class="btn-volver">← Volver</a>
    
    <div class="container">
        <div class="admin-container">
            <div id="mensaje" class="message"></div>
            
            <div class="admin-header">
                <h1>Panel Administrador</h1>
                <p>Bienvenido, <?php echo htmlspecialchars($nombre_admin); ?></p>
            </div>

            <div class="menu-buttons">
                <a href="usuario2.php" class="btn-menu" onclick="mostrarMensaje('Abriendo formulario de nuevo usuario...', event)">
                    <span class="icon">👤</span>
                    Crear Nuevo Usuario
                </a>

                <a href="ver_usuarios.php" class="btn-menu" onclick="mostrarMensaje('Abriendo lista de usuarios...', event)">
                    <span class="icon">📋</span>
                    Ver Información de Usuarios
                </a>

                <a href="admin_ausencias.php" class="btn-menu" onclick="mostrarMensaje('Abrir tabla de ausencias...', event)">
                    <span class="icon">📄</span>
                   Gestionar Ausencias
                </a>
            </div>
        </div>
    </div>

    <script>
        function mostrarMensaje(texto, evento) {
            const mensaje = document.getElementById('mensaje');
            mensaje.textContent = texto;
            mensaje.classList.add('show');
            
            setTimeout(() => {
                mensaje.classList.remove('show');
            }, 1500);
        }

        function confirmarSalida(evento) {
            const confirmacion = confirm('¿Estás seguro de que deseas cerrar sesión?');
            if (!confirmacion) {
                evento.preventDefault();
            }
        }

        window.addEventListener('load', function() {
            console.log('Panel de administrador cargado');
            mostrarMensaje('✅ Bienvenido al panel', null);
        });
    </script>
</body>
</html>
