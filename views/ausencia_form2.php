<?php
// Esta vista muestra el formulario para comunicar una ausencia.
// El formulario envía datos a ../php/ausencia_form.php
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
<title>Comunicar ausencia</title>
<link rel="stylesheet" href="../css/basic.css">
<link rel="stylesheet" href="../css/ausencia_form.css">
</head>

<body>

<header class="nav-header">
<nav class="navbar">

<div class="nav-left">
<a href="/php/horario.php">
<img src="../views/cpifp.png" alt="Logo" class="nav-logo">
</a>
</div>

<div class="nav-center">
<a href="/php/ausencia_form.php">Comunicar ausencia</a>
<a href="/php/horario_general.php">Horario general</a>

<?php
$dni_profesor = $_SESSION['dni'];
$res_rol = mysqli_query($conexion, "SELECT rol FROM usuarios WHERE dni = '$dni_profesor'");
$rol_row = mysqli_fetch_assoc($res_rol);
if ($rol_row && $rol_row['rol'] === 'admin') {
    echo '<a href="/php/admin_menu.php">Admin</a>';
}
?>
</div>

<div class="nav-right">
<span class="nav-user"><?php echo htmlspecialchars($nombre_profesor); ?></span>
<a class="logout" href="/php/logout.php">Salir</a>
</div>

</nav>
</header>

<div class="panel">

<a class="volver" href="/php/horario.php">← Volver</a>

<h1>Comunicar ausencia</h1>

<?php if ($mensaje) echo "<p class='mensaje'>" . htmlspecialchars($mensaje) . "</p>"; ?>

<form class="formulario" method="POST" enctype="multipart/form-data">

<!-- Seleccionar múltiples franjas horarias -->
<div class="franjas-container">
    <label><strong>Selecciona las franjas afectadas:</strong></label>
    <div class="franjas-list">
    <?php
    $sql = "SELECT h.id_h, hr.dia, hr.hora, h.modulo FROM horario h JOIN hora hr ON h.id_hora = hr.id_hora WHERE h.dni_u = '$dni' AND LOWER(h.modulo) != 'guardia' ORDER BY hr.dia, hr.hora";
    $res = mysqli_query($conexion, $sql);
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<label class='franja-checkbox'>
            <input type='checkbox' name='id_h[]' value='" . $row['id_h'] . "'>
            <span>" . $row['dia'] . " - " . $row['hora'] . " (" . $row['modulo'] . ")</span>
        </label>";
    }
    ?>
    </div>
</div>

<!-- Campos del formulario -->
<input type="text" name="tipo" placeholder="Tipo (Ej: Guardia)" required>
<input type="text" name="aula" placeholder="Aula (Ej: A-203)" required>
<input type="date" name="fecha" required>
<textarea name="texto" placeholder="Descripción de la ausencia" required></textarea>

<label>Justificante (pdf) – solo lo ve el admin</label>
<input type="file" name="justificante" accept="application/pdf">

<label>Tarea (pdf) – visible a todos</label>
<input type="file" name="tarea" accept="application/pdf">

<button class="btn" type="submit">Enviar ausencia</button>

</form>

</div>

<footer>
    <p>© 2026 Voltic. Todos los derechos reservados.</p>
</footer>

<script>
// Validar que se seleccione al menos una franja
document.querySelector('.formulario').addEventListener('submit', function(e) {
    const checkboxes = document.querySelectorAll('input[name="id_h[]"]');
    const checked = Array.from(checkboxes).some(cb => cb.checked);
    
    if (!checked) {
        e.preventDefault();
        alert('Debes seleccionar al menos una franja horaria.');
    }
});
</script>

</body>
</html>
