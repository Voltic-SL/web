<?php //require_once __DIR__ . '/añadirhorariologica.php'; 
    ?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mi horario</title>

<link rel="stylesheet" href="../css/basic.css">
<link rel="stylesheet" href="../css/horario.css">
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

<?php
$stmt_rol = $conexion->prepare("SELECT rol FROM usuarios WHERE dni = ?");
$stmt_rol->bind_param('s', $dni_profesor);
$stmt_rol->execute();
$res_rol = $stmt_rol->get_result();

if ($res_rol && $res_rol->num_rows === 1 && $res_rol->fetch_assoc()['rol'] === 'admin') {
echo '<a href="admin_menu.php">Admin</a>';
}
?>

</div>

<div class="nav-right">
<span class="nav-user"><?php echo htmlspecialchars($nombre_profesor); ?></span>
<a class="logout" href="logout.php">Salir</a>
</div>

</nav>
</header>

<div class="panel">

<h1>Mi horario</h1>

<?php if ($mensaje) echo "<p class='mensaje'>" . htmlspecialchars($mensaje) . "</p>"; ?>

<form class="formulario" method="POST">

<select name="id_hora" required>
<option value="">Selecciona franja</option>

<?php while ($hora = $result_horas->fetch_assoc()): 
$dia_nombre = $dias_nombre[$hora['dia']] ?? $hora['dia']; ?>

<option value="<?php echo $hora['id_hora']; ?>">
<?php echo $dia_nombre . " - " . $hora['hora']; ?>
</option>

<?php endwhile; ?>
</select>

<select name="modulo_sel">
<option value="">--Seleccionar--</option>
<option value="Guardia">Guardia</option>
</select>

<input type="text" name="modulo_manual" placeholder="Escribe otro módulo">

<button class="btn" type="submit" name="asignar">Guardar</button>

</form>

<h2>Mi horario</h2>

<?php if ($result_horario->num_rows > 0): ?>

<table class="tabla">

<tr>
<th>Día</th>
<th>Hora</th>
<th>Módulo</th>
<th>Acción</th>
</tr>

<?php while ($horario = $result_horario->fetch_assoc()):
$dia_nombre = $dias_nombre[$horario['dia']] ?? $horario['dia']; ?>

<tr>
<td><?php echo $dia_nombre; ?></td>
<td><?php echo $horario['hora']; ?></td>
<td><?php echo htmlspecialchars($horario['modulo']); ?></td>

<td>

<form method="POST">
<input type="hidden" name="id_h" value="<?php echo $horario['id_h']; ?>">
<button class="btn-borrar" name="eliminar">Eliminar</button>
</form>

</td>
</tr>

<?php endwhile; ?>

</table>

<?php else: ?>

<p>No hay horarios. Usa el formulario para añadir uno.</p>

<?php endif; ?>

</div>

</body>
</html>
