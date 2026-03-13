<?php //require_once __DIR__ . '/añadirhorariologica.php'; 
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

        <?php $error = $error ?? ''; if ($error !== ''): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form method="POST" action="../php/index67.php">
            <label for="dni">DNI:</label><br>
            <input type="text" id="dni" name="dni" required><br><br>

            <label for="password">Contrasena:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit" name="login">Iniciar sesion</button>
        </form>
    </div>
</body>
</html>
