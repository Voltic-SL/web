<?php
// Esta página muestra el formulario para que los usuarios pongan su DNI y contraseña.
// Cuando pulsan "Iniciar sesión", los datos van a ../php/index.php para verificar.
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
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" href="../css/loginc.css">
    <link rel="stylesheet" href="../css/basic.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Si hay error, mostrarlo -->
        <?php if (!empty($error)) echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>'; ?>

        <!-- Formulario que envía a index.php -->
        <form method="POST" action="../php/index.php">
            <label for="dni">DNI:</label><br>
            <input type="text" id="dni" name="dni" required placeholder="Ej: 12345678A"><br><br>

            <label for="password">Contraseña:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit" name="login">Iniciar sesión</button>
        </form>
    </div>

    <footer>
        <p>© 2026 Voltic. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
