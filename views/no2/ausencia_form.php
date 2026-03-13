<?php //require_once __DIR__ . '/añadirhorariologica.php'; 
    ?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Comunicar ausencia</title>

<link rel="stylesheet" href="../css/basic.css">
<link rel="stylesheet" href="../css/ausencia_form.css">
</head>

<body>

<header class="nav-header">
<nav class="navbar">

<div class="nav-left">
<a href="horario.php">
<img src="../cpifp.png" alt="Logo" class="nav-logo">
</a>
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

<a class="volver" href="horario.php">← Volver</a>

<h1>Comunicar ausencia</h1>

<?php if ($mensaje) echo "<p class='mensaje'>" . htmlspecialchars($mensaje) . "</p>"; ?>

<form class="formulario" method="POST" enctype="multipart/form-data">

<select name="id_h" required>
<option value="">Selecciona una franja</option>

<?php
$stmt = $conexion->prepare(
"SELECT h.id_h, hr.dia, hr.hora, h.modulo
FROM horario h
JOIN hora hr ON h.id_hora = hr.id_hora
WHERE h.dni_u = ?
ORDER BY hr.dia, hr.hora"
);

$stmt->bind_param('s', $dni);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
echo "<option value='" . htmlspecialchars($row['id_h']) . "'>
" . htmlspecialchars($row['dia']) . " - " . htmlspecialchars($row['hora']) . " (" . htmlspecialchars($row['modulo']) . ")
</option>";
}
?>

</select>

<input type="text" name="tipo" placeholder="Tipo (Ej: Guardia)" required>
<input type="text" name="aula" placeholder="Aula (Ej: A-203)" required>
<input type="date" name="fecha" required>
<textarea name="texto" placeholder="Descripción de la ausencia" required></textarea>
<input type="file" name="justificante" accept="application/pdf">

<button class="btn" type="submit">Enviar ausencia</button>

</form>

</div>

</body>
</html>
