<?php
// Esta vista muestra las ausencias pendientes, aceptadas y denegadas.
// El admin aquí acepta o deniega las ausencias que los profesores reportan.

setcookie('nombre_cookie', 'valor', [
    'expires' => time() + 3600, // Expira en una hora
    'httponly' => true,
])
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Administrar ausencias</title>
<link rel="stylesheet" href="../css/basic.css">
<link rel="stylesheet" href="../css/admin_ausencias.css">
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
<a href="/php/admin_menu.php">Admin</a>
</div>

<div class="nav-right">
<span class="nav-user"><?php echo htmlspecialchars($nombre_profesor); ?></span>
<a class="logout" href="/php/logout.php">Salir</a>
</div>

</nav>
</header>

<div class="container-panel">

<h1>Administrar Ausencias</h1>



<!-- Tabla de ausencias pendientes -->
<h2>Pendientes (para que aceptes o deniegues)</h2>

<?php if ($pendientes->num_rows > 0): ?>

<table class="tabla-admin">

<tr>
<th>ID</th>
<th>Profesor</th>
<th>Franja</th>
<th>Tipo</th>
<th>Justificante</th>
<th>Tarea</th>
<th>Acción</th>
</tr>

<?php while ($a = $pendientes->fetch_assoc()): ?>

<tr>
<td><?php echo $a['id_ausencia']; ?></td>
<td><?php echo $a['nombre']." ".$a['apellido']; ?></td>
<td><?php echo $a['dia']." - ".$a['hora']; ?></td>
<td><?php echo $a['tipo']; ?></td>

<!-- Ver archivo justificante -->
<td>
<?php
if (!empty($a['justificante'])) {
    $link = str_replace("../", "/", $a['justificante']);
    echo "<a href='$link' target='_blank'>Ver</a>";
} else {
    echo "-";
}
?>
</td>

<!-- Ver archivo tarea -->
<td>
<?php
if (!empty($a['tarea'])) {
    $link = str_replace("../", "/", $a['tarea']);
    echo "<a href='$link' target='_blank'>Ver</a>";
} else {
    echo "-";
}
?>
</td>

<!-- Botones de acción -->
<td>
<form method="post">
<input type="hidden" name="id_ausencia" value="<?php echo $a['id_ausencia']; ?>">

<!-- Si aceptas, otros profesores podrán cubrirla -->
<button class="btn btn-aceptar" name="aceptar">Aceptar</button>

<!-- Si denigas, el profesor sabrá que fue rechazada -->
<button class="btn btn-denegar" name="denegar">Denegar</button>
</form>
</td>

</tr>

<?php endwhile; ?>

</table>

<?php else: ?>
<p>No hay ausencias pendientes</p>
<?php endif; ?>

<!-- Tabla de ausencias aceptadas/cubiertas -->
<h2>Aceptadas / Cubiertas</h2>

<?php if ($aceptadas->num_rows > 0): ?>

<table class="tabla-admin">

<tr>
<th>Profesor ausente</th>
<th>Profesor que cubre</th>
<th>Franja</th>
<th>Estado</th>
</tr>

<?php while ($a = $aceptadas->fetch_assoc()): ?>

<tr>
<td><?php echo $a['nombre']." ".$a['apellido']; ?></td>
<td><?php echo $a['dni_cubre'] ? $a['cubre_nombre']." ".$a['cubre_apellido'] : "-"; ?></td>
<td><?php echo $a['dia']." - ".$a['hora']; ?></td>
<td><?php echo htmlspecialchars($a['estado']); ?></td>
</tr>

<?php endwhile; ?>

</table>

<?php else: ?>
<p>No hay ausencias aceptadas o cubiertas</p>
<?php endif; ?>

<!-- Tabla de ausencias denegadas -->
<h2>Denegadas</h2>
<?php if ($denegadas->num_rows > 0): ?>

<table class="tabla-admin">

<tr>
<th>Profesor</th>
<th>Franja</th>
</tr>

<?php while ($a = $denegadas->fetch_assoc()): ?>

<tr>
<td><?php echo $a['nombre']." ".$a['apellido']; ?></td>
<td><?php echo $a['dia']." - ".$a['hora']; ?></td>
</tr>

<?php endwhile; ?>

</table>

<?php else: ?>
<p>No hay ausencias denegadas</p>
<?php endif; ?>

</div>

</body>
</html>


<!-- <h2>Denegadas</h2>

<?php if ($denegadas->num_rows > 0): ?>

<table class="tabla-admin">

<tr>
<th>ID</th>
<th>Profesor</th>
<th>Estado</th>
<th>Cubre</th>
<th>Justificante</th>
<th>Tarea</th>
<th>Acción</th>
</tr>

<?php while ($a = $denegadas->fetch_assoc()): ?>

<tr>

<td><?php echo $a['id_ausencia']; ?></td>

<td><?php echo $a['nombre']." ".$a['apellido']; ?></td>

<td><?php echo $a['estado']; ?></td>

<td> -->

<!-- <?php
echo $a['dni_cubre']
? $a['cubre_nombre']." ".$a['cubre_apellido']
: "-";
?>

</td>

<td>
<?php
if (!empty($a['justificante'])) {
    $link = str_replace("../", "/", $a['justificante']);
    echo "<a href='$link' target='_blank'>Ver</a>";
} else {
    echo "-";
}
?>
</td>
<td>
<?php
if (!empty($a['tarea'])) {
    $link = str_replace("../", "/", $a['tarea']);
    echo "<a href='$link' target='_blank'>Ver</a>";
} else {
    echo "-";
}
?>
</td>
<td>

<form method="post">

<input type="hidden" name="id_ausencia" value="<?php echo $a['id_ausencia']; ?>">

<button class="btn btn-eliminar" name="eliminar">Eliminar</button>

</form>

</td>

</tr>

<?php endwhile; ?>

</table>

<?php else: ?>

<p>No hay ausencias denegadas</p>

<?php endif; ?>

</div> -->

<footer>
    <p>© 2026 Voltic. Todos los derechos reservados.</p>
</footer>

</body>
</html>
