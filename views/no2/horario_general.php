<?php //require_once __DIR__ . '/añadirhorariologica.php'; 
    ?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro de ausencias</title>

<link rel="stylesheet" href="../css/basic.css">
<link rel="stylesheet" href="../css/horario_general.css">
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

<h1>Registro de ausencias</h1>

<h2>Ausencias activas</h2>

<table class='tabla'>

<tr>
<th>Profesor ausente</th>
<th>Módulo</th>
<th>Día</th>
<th>Hora</th>
<th>Fecha</th>
<th>Profesor que cubre</th>
<th>Estado</th>
<th>Acción</th>
</tr>

<?php

if ($result && $result->num_rows > 0) {

while ($row = $result->fetch_assoc()) {

$ausente = $row['nombre_ausente']." ".$row['apellido_ausente'];

$cubre = $row['nombre_cubre']
? $row['nombre_cubre']." ".$row['apellido_cubre']
: "-";

$fecha = DateTime::createFromFormat('Y-m-d', $row['fecha']);

$fecha_formato = $fecha
? $fecha->format('d/m/Y')
: $row['fecha'];

echo "<tr>";

echo "<td>$ausente</td>";
echo "<td>{$row['modulo']}</td>";
echo "<td>{$row['dia']}</td>";
echo "<td>{$row['hora']}</td>";
echo "<td>$fecha_formato</td>";
echo "<td>$cubre</td>";
echo "<td>{$row['estado']}</td>";

echo "<td>";

if ($row['estado'] == 'pendiente') {

echo "

<form method='POST'>

<button class='btn' name='aceptar_ausencia' value='".$row['id_ausencia']."'>
Aceptar
</button>

</form>

";

}

if ($row['estado'] == 'aceptada') {

echo "

<form method='POST'>

<button class='btn' name='cubrir_ausencia' value='".$row['id_ausencia']."'>
Cubrir
</button>

</form>

";

}

if ($row['estado'] == 'cubierta') {

echo "Ya cubierta";

}

echo "</td>";

echo "</tr>";

}

}else{

echo "<tr><td colspan='8'>No hay ausencias registradas</td></tr>";

}

?>

</table>

<h2>Profesores que han cubierto guardias</h2>

<form class="filtros" method="GET">

<label>Día</label>

<select name="dia">

<option value="">Todos</option>
<option value="L" <?php if($dia_seleccionado=='L') echo 'selected'; ?>>Lunes</option>
<option value="M" <?php if($dia_seleccionado=='M') echo 'selected'; ?>>Martes</option>
<option value="X" <?php if($dia_seleccionado=='X') echo 'selected'; ?>>Miércoles</option>
<option value="J" <?php if($dia_seleccionado=='J') echo 'selected'; ?>>Jueves</option>
<option value="V" <?php if($dia_seleccionado=='V') echo 'selected'; ?>>Viernes</option>

</select>

<label>Hora</label>

<select name="hora">

<option value="">Todas</option>

<?php

for($i=1;$i<=6;$i++){

$sel = ($hora_seleccionada==$i)?'selected':'';

echo "<option value='$i' $sel>Hora $i</option>";

}

?>

</select>

<button class="btn">Filtrar</button>

</form>

<table class='tabla'>

<tr>
<th>Profesor</th>
<th>Día</th>
<th>Hora</th>
<th>Guardias cubiertas</th>
</tr>

<?php

if ($res && $res->num_rows > 0) {

while ($row = $res->fetch_assoc()) {

$dni = $row['dni_cubre'];

$u = $conexion->query("SELECT nombre, apellido FROM usuarios WHERE dni='$dni'");

$prof = $u->fetch_assoc();

echo "<tr>";

echo "<td>".$prof['nombre']." ".$prof['apellido']."</td>";
echo "<td>".$row['dia']."</td>";
echo "<td>".$row['hora']."</td>";
echo "<td>".$row['total']."</td>";

echo "</tr>";

}

}else{

echo "<tr><td colspan='4'>No hay guardias cubiertas</td></tr>";

}

?>

</table>

</div>

</body>
</html>
