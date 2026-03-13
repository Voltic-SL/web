<?php
$dias_nombre = array('L' => 'Lunes', 'M' => 'Martes', 'X' => 'Miércoles', 'J' => 'Jueves', 'V' => 'Viernes');
setcookie('nombre_cookie', 'valor', [
    'expires' => time() + 3600, // Expira en una hora
    'httponly' => true,
])
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
<a href="/php/horario.php">
<img src="../views/cpifp.png" alt="Logo" class="nav-logo">
</a>
</div>

<div class="nav-center">

<a href="/php/ausencia_form.php">Comunicar ausencia</a>
<a href="/php/horario_general.php">Horario general</a>

<?php 

$sql_rol = "SELECT rol FROM usuarios WHERE dni = '$dni_profesor'";
$res_rol = mysqli_query($conexion, $sql_rol);

if (mysqli_num_rows($res_rol) === 1 && mysqli_fetch_assoc($res_rol)['rol'] === 'admin') {

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

<h1>Registro de ausencias</h1>

<h2>Ausencias activas</h2>

<table class='tabla'>

<tr>
<th>Profesor ausente</th>
<th>Módulo</th>
<th colspan="2">Día y Hora</th>
<th>Fecha</th>
<th>Profesor que cubre</th>
<th>Estado</th>

<th>Tarea</th>
<th>Acción</th>
</tr>

<?php

if (!isset($result_ausencias)) {
    $result_ausencias = null;
}

if ($result_ausencias && $result_ausencias->num_rows > 0) {
    while ($row = $result_ausencias->fetch_assoc()) {
        // Solo mostrar ausencias que el usuario puede cubrir (tiene guardia en ese día y hora)
        if (!isset($guardias_usuario[$row['dia'].'-'.$row['hora']])) {
            continue;
        }
        $ausente = $row['nombre_ausencia']." ".$row['apellido_ausencia'];

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
        
        $dia_texto = isset($dias_nombre[$row['dia']]) ? $dias_nombre[$row['dia']] : $row['dia'];
        $dia_hora = $dia_texto . " " . $row['hora'];
        echo "<td colspan='2'>$dia_hora</td>";
        
        echo "<td>$fecha_formato</td>";
        echo "<td>$cubre</td>";
        echo "<td>{$row['estado']}</td>";
      
        echo "<td>";
        if(!empty($row['tarea'])){
            $link = str_replace('../','/',$row['tarea']);
            echo "<a href='$link' target='_blank'>Ver</a>";
        } else {
            echo '-';
        }
        echo "</td>";
        echo "<td>";

        // Solo mostrar botón 'Aceptar' si no es su propia ausencia
        if ($row['estado'] == 'pendiente' && $row['dni'] !== $dni_profesor) {
            echo "
            <form method='POST'>
            <button class='btn' name='aceptar_ausencia' value='".$row['id_ausencia']."'>
            Aceptar
            </button>
            </form>
            ";
        }

        // Solo mostrar botón 'Cubrir' si no es su propia ausencia
        if ($row['estado'] == 'aceptada' && $row['dni'] !== $dni_profesor) {
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
} else {
    echo "<tr><td colspan='8'>No hay ausencias registradas</td></tr>";
}


?>

</table>

<h2>Profesores que han cubierto guardias</h2>

<form class="filtros" method="GET">

<label>Día</label>
<select name="dia">
<option value="">Todos</option>
<option value="L" <?php if($dia_filtro=='L') echo 'selected'; ?>>Lunes</option>
<option value="M" <?php if($dia_filtro=='M') echo 'selected'; ?>>Martes</option>
<option value="X" <?php if($dia_filtro=='X') echo 'selected'; ?>>Miércoles</option>
<option value="J" <?php if($dia_filtro=='J') echo 'selected'; ?>>Jueves</option>
<option value="V" <?php if($dia_filtro=='V') echo 'selected'; ?>>Viernes</option>
</select>

<br><br>

<label>Hora</label>
<select name="hora">
<option value="">Todas</option>
<?php

for($i=1;$i<=6;$i++){

$sel = ($hora_filtro==$i)?'selected':'';

echo "<option value='$i' $sel>Hora $i</option>";

}

?>
</select>

<br><br>

<button class="btn">Filtrar</button>

</form>

<table class='tabla tabla-cubiertas'>

<tr>
<th>Profesor</th>
<th>Día</th>
<th>Hora</th>
<th>Guardias cubiertas</th>
</tr>

<?php

// Inicializar $result_cubiertas si no existe
if (!isset($result_cubiertas)) {
    $result_cubiertas = null;
}

if ($result_cubiertas && $result_cubiertas->num_rows > 0) {

    $contador = 0;
    while ($row = $result_cubiertas->fetch_assoc()) {

        $dni = $row['dni_cubre'];

        $u = $conexion->query("SELECT nombre, apellido FROM usuarios WHERE dni='$dni'");

        $prof = $u->fetch_assoc();

        $dia_texto = isset($dias_nombre[$row['dia']]) ? $dias_nombre[$row['dia']] : $row['dia'];

        $clase_fila = ($contador % 2 == 0) ? 'fila-par' : 'fila-impar';

        echo "<tr class='$clase_fila'>";

        echo "<td class='profesor-nombre'>".$prof['nombre']." ".$prof['apellido']."</td>";

        echo "<td class='dia'>".$dia_texto."</td>";

        echo "<td class='hora'>Hora ".$row['hora']."</td>";

        echo "<td class='contador'><span class='badge'>".$row['total']."</span></td>";

        echo "</tr>";

        $contador++;

    }

} else {

    echo "<tr><td colspan='4' class='sin-datos'>No hay guardias cubiertas</td></tr>";
}
?>

</table>

</div>

<footer>
    <p>© 2026 Voltic. Todos los derechos reservados.</p>
</footer>

</body>
</html>