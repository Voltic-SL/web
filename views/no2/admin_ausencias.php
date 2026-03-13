<?php //require_once __DIR__ . '/añadirhorariologica.php'; 
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
<a href="horario.php">
<img src="../cpifp.png" alt="Logo" class="nav-logo">
</a>
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

<div class="container-panel">

<h1>Administrar Ausencias</h1>

<a class="volver" href="admin_menu.php">← Volver</a>

<h2>Pendientes</h2>

<?php if ($pendientes->num_rows > 0): ?>

<table class="tabla-admin">

<tr>
<th>ID</th>
<th>Profesor</th>
<th>Franja</th>
<th>Tipo</th>
<th>Acción</th>
</tr>

<?php while ($a = $pendientes->fetch_assoc()): ?>

<tr>
<td><?php echo $a['id_ausencia']; ?></td>

<td><?php echo $a['nombre']." ".$a['apellido']; ?></td>

<td><?php echo $a['dia']." - ".$a['hora']; ?></td>

<td><?php echo $a['tipo']; ?></td>

<td>

<form method="post">

<input type="hidden" name="id_ausencia" value="<?php echo $a['id_ausencia']; ?>">

<button class="btn btn-aceptar" name="aceptar">Aceptar</button>

<button class="btn btn-denegar" name="denegar">Denegar</button>

</form>

</td>

</tr>

<?php endwhile; ?>

</table>

<?php else: ?>

<p>No hay pendientes</p>

<?php endif; ?>

<h2>Aceptadas / Cubiertas</h2>

<?php if ($aceptadas->num_rows > 0): ?>

<table class="tabla-admin">

<tr>
<th>ID</th>
<th>Profesor</th>
<th>Estado</th>
<th>Cubre</th>
<th>Acción</th>
</tr>

<?php while ($a = $aceptadas->fetch_assoc()): ?>

<tr>

<td><?php echo $a['id_ausencia']; ?></td>

<td><?php echo $a['nombre']." ".$a['apellido']; ?></td>

<td><?php echo $a['estado']; ?></td>

<td>

<?php
echo $a['dni_cubre']
? $a['cubre_nombre']." ".$a['cubre_apellido']
: "-";
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

<p>No hay ausencias aceptadas o cubiertas</p>

<?php endif; ?>

<h2>Denegadas</h2>

<?php if ($denegadas->num_rows > 0): ?>

<table class="tabla-admin">

<tr>
<th>ID</th>
<th>Profesor</th>
<th>Estado</th>
<th>Cubre</th>
<th>Acción</th>
</tr>

<?php while ($a = $denegadas->fetch_assoc()): ?>

<tr>

<td><?php echo $a['id_ausencia']; ?></td>

<td><?php echo $a['nombre']." ".$a['apellido']; ?></td>

<td><?php echo $a['estado']; ?></td>

<td>

<?php
echo $a['dni_cubre']
? $a['cubre_nombre']." ".$a['cubre_apellido']
: "-";
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

</div>

</body>
</html>
