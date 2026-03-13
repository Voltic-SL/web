<?php //require_once __DIR__ . '/añadirhorariologica.php'; 
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
<title>Crear usuario</title>
<link rel="icon" href="../favicon.ico">
<link rel="stylesheet" href="../css/basic.css">
<link rel="stylesheet" href="../css/usuario2.css">
</head>

<body>

<header class="nav-header">
<nav class="navbar">

<div class="nav-left">
<img src="../views/cpifp.png" class="nav-logo">
</div>

<div class="nav-center">
<a href="/php/ausencia_form.php">Comunicar ausencia</a>
<a href="/php/horario_general.php">Horario general</a>
<a href="/php/admin_menu.php">Admin</a>
</div>

<div class="nav-right">
<span class="nav-user"><?php echo htmlspecialchars($nombre_profesor); ?></span>
<a class="logout" href="/php/logout.php">Salir</a>
</div>

</nav>
</header>

<div class="container-form">

<h2>Crear usuario</h2>



<?php
$dni_value = '';
if (isset($_POST['dni'])) {
    $dni_value = $_POST['dni'];
}

$nombre_value = '';
if (isset($_POST['nombre'])) {
    $nombre_value = $_POST['nombre'];
}

$apellido_value = '';
if (isset($_POST['apellido'])) {
    $apellido_value = $_POST['apellido'];
}

$ce_value = '';
if (isset($_POST['ce'])) {
    $ce_value = $_POST['ce'];
}

$familia_value = '';
if (isset($_POST['familia'])) {
    $familia_value = $_POST['familia'];
}
?>

<?php if ($mensaje !== ''): ?>
<div class="mensaje <?php echo $ok ? 'ok' : 'error'; ?>">
<?php echo htmlspecialchars($mensaje); ?>
</div>
<?php endif; ?>

<form method="POST" class="form-usuario">

<div class="campo">
<label>DNI</label>
<input type="text" name="dni" required value="<?php echo htmlspecialchars($dni_value); ?>">
</div>

<div class="campo">
<label>Nombre</label>
<input type="text" name="nombre" required value="<?php echo htmlspecialchars($nombre_value); ?>">
</div>

<div class="campo">
<label>Apellido</label>
<input type="text" name="apellido" required value="<?php echo htmlspecialchars($apellido_value); ?>">
</div>

<div class="campo">
<label>Correo electrónico</label>
<input type="email" name="ce" required value="<?php echo htmlspecialchars($ce_value); ?>">
</div>

<div class="campo">
<label>Contraseña</label>
<input type="password" name="password" required>
</div>

<div class="campo">
<label>Rol</label>
<select name="rol" required>
<option value="">Selecciona rol</option>
<option value="profesor">Profesor</option>
<option value="admin">Admin</option>
</select>
</div>

<div class="campo">
<label>Familia</label>
<input type="text" name="familia" value="<?php echo htmlspecialchars($familia_value); ?>">
</div>

<button type="submit" class="btn-crear">Crear usuario</button>

</form>

</div>

<footer>
    <p>© 2026 Voltic. Todos los derechos reservados.</p>
</footer>

</body>
</html>
