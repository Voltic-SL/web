<?php //require_once __DIR__ . '/añadirhorariologica.php'; 
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
<img src="../cpifp.png" class="nav-logo">
</div>

<div class="nav-center">
<a href="ausencia_form.php">Comunicar ausencia</a>
<a href="horario_general.php">Horario general</a>
<a href="admin_menu.php">Admin</a>
</div>

<div class="nav-right">
<span class="nav-user"><?php echo htmlspecialchars($nombre_profesor); ?></span>
<a class="logout" href="logout.php">Salir</a>
</div>

</nav>
</header>

<div class="container-form">

<h2>Crear usuario</h2>

<p class="volver">
<a href="admin_menu.php">← Volver</a>
</p>

<?php if ($mensaje !== ''): ?>
<div class="mensaje <?php echo $ok ? 'ok' : 'error'; ?>">
<?php echo htmlspecialchars($mensaje); ?>
</div>
<?php endif; ?>

<form method="POST" class="form-usuario">

<div class="campo">
<label>DNI</label>
<input type="text" name="dni" required value="<?php echo htmlspecialchars($_POST['dni'] ?? ''); ?>">
</div>

<div class="campo">
<label>Nombre</label>
<input type="text" name="nombre" required value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
</div>

<div class="campo">
<label>Apellido</label>
<input type="text" name="apellido" required value="<?php echo htmlspecialchars($_POST['apellido'] ?? ''); ?>">
</div>

<div class="campo">
<label>Correo electrónico</label>
<input type="email" name="ce" required value="<?php echo htmlspecialchars($_POST['ce'] ?? ''); ?>">
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
<input type="text" name="familia" value="<?php echo htmlspecialchars($_POST['familia'] ?? ''); ?>">
</div>

<button type="submit" class="btn-crear">Crear usuario</button>

</form>

</div>

</body>
</html>
