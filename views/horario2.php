<?php
// ================================================
// VISTA: Mostrar el horario personal del profesor
// ================================================
// Este archivo muestra el formulario para agregar horarios
// y una tabla con los horarios ya asignados.
// ================================================

// Verificar sesión (aunque el controlador ya lo hace, esta es una doble verificación).
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Si el usuario no está logueado, redirigir a login
if (!isset($_SESSION['dni'])) {
    header("Location: ../index.php");
    exit();
}
// Cookie para recordar al usuario (opcional)
setcookie('nombre_cookie', 'valor', [
    'expires' => time() + 360000, 
    'httponly' => true,
]);

// Obtener el DNI del profesor logueado de la sesión
$dni_profesor = $_SESSION['dni'];

// Buscar el nombre del profesor en la base de datos
$sql_nombre = "SELECT nombre FROM usuarios WHERE dni='$dni_profesor'";
$res_nombre = mysqli_query($conexion, $sql_nombre);
$nombre_profesor = mysqli_num_rows($res_nombre) === 1 ? mysqli_fetch_assoc($res_nombre)['nombre'] : $dni_profesor;

// Buscar el rol del profesor (profesor, admin, etc.) en la base de datos
$sql_rol = "SELECT rol FROM usuarios WHERE dni='$dni_profesor'";
$res_rol  = mysqli_query($conexion, $sql_rol);
$rol = mysqli_num_rows($res_rol) === 1 ? mysqli_fetch_assoc($res_rol)['rol'] : '';

// Obtener todos los horarios que el profesor tiene asignados
$sql_horario = "SELECT h.id_h, hr.dia, hr.hora, h.modulo
                FROM horario h
                JOIN hora hr ON h.id_hora = hr.id_hora
                WHERE h.dni_u='$dni_profesor'
                ORDER BY hr.dia, hr.hora";
$result_horario = mysqli_query($conexion, $sql_horario);

// Buscar las ausencias registradas por el profesor
$sql_absencias = "SELECT * FROM ausencia WHERE dni = '$dni_profesor' ORDER BY fecha DESC";
$result_absencias = mysqli_query($conexion, $sql_absencias);

// Convertir los códigos de día (L, M, X, J, V) a nombres completos en español
$dias_nombre = [
    'L' => 'Lunes',
    'M' => 'Martes',
    'X' => 'Miércoles',
    'J' => 'Jueves',
    'V' => 'Viernes'
];
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
<img src="../views/cpifp.png" class="nav-logo">
</div>
<div class="nav-center">
<a href="/php/ausencia_form.php">Comunicar ausencia</a>
<a href="/php/horario_general.php">Horario general</a>
<?php if ($rol == 'admin') echo '<a href="/php/admin_menu.php">Admin</a>'; ?>
</div>
<div class="nav-right">
<span class="nav-user"><?php echo htmlspecialchars($nombre_profesor); ?></span>
<a class="logout" href="/php/logout.php">Salir</a>
</div>
</nav>
</header>

<div class="panel">
<h1>Mi horario</h1>

<?php if (!empty($mensaje)) echo '<p class="mensaje">'.htmlspecialchars($mensaje).'</p>'; ?>

<?php 
// Mostrar el formulario solo si el usuario es profesor o administrador
if ($rol === 'admin' || $rol === 'profesor'): 
?>
<!-- Este formulario permite asignar un horario al profesor -->
<form class="formulario" method="POST">
  <!-- Seleccionar el día de la semana (Lunes a Viernes) -->
  <label>Día:</label>
  <select name="dia" required>
    <option value="">Elige día</option>
    <option value="L">Lunes</option>
    <option value="M">Martes</option>
    <option value="X">Miércoles</option>
    <option value="J">Jueves</option>
    <option value="V">Viernes</option>
  </select>

  <!-- Seleccionar la hora (1 a 6) -->
  <label>Hora:</label>
  <select name="hora" required>
    <option value="">Elige hora</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
  </select>

  <!-- Seleccionar el módulo: marcar Guardia O escribir otro -->
  <label>Módulo:</label>
  <div class="modulo-container">
    <!-- Checkbox para marcar si es Guardia -->
    <label class="checkbox-label">
      <input type="checkbox" name="modulo_sel" value="Guardia" id="modulo_checkbox" onchange="limpiarModuloManual()">
      Guardia
    </label>
    <!-- Separador "o" entre las dos opciones -->
    <span class="separator">o</span>
    <!-- Campo de texto para escribir otro módulo -->
    <input type="text" name="modulo_manual" id="modulo_manual" placeholder="escribe otro módulo" oninput="desmarcarGuardia()">
  </div>
  <!-- Botón para guardar el horario -->
  <button class="btn" type="submit" name="asignar">Guardar</button>

<script>
// Función para limpiar el campo de texto cuando se marca Guardia
function limpiarModuloManual() {
  var guardia = document.getElementById('modulo_checkbox').checked;
  if (guardia) {
    document.getElementById('modulo_manual').value = '';
  }
}

// Función para desmarcar Guardia cuando se escribe en el campo de texto
function desmarcarGuardia() {
  var texto = document.getElementById('modulo_manual').value;
  if (texto.trim() !== '') {
    document.getElementById('modulo_checkbox').checked = false;
  }
}
</script>
</form>
<?php endif; ?>

<!-- Tabla que muestra todos los horarios asignados -->
<h2>Mi horario</h2>
<?php if (mysqli_num_rows($result_horario) > 0): ?>
<table class="tabla">
  <!-- Encabezados de la tabla: Día, Hora, Módulo y Acción -->
  <tr><th>Día</th><th>Hora</th><th>Módulo</th><th>Acción</th></tr>
  <!-- Mostrar cada horario que tiene el profesor -->
  <?php while ($horario = mysqli_fetch_assoc($result_horario)): ?>
  <tr>
    <!-- Mostrar el día en nombre completo (Lunes, Martes, etc.) -->
    <td><?php echo htmlspecialchars($dias_nombre[$horario['dia']]); ?></td>
    <!-- Mostrar la hora (1, 2, 3, etc.) -->
    <td><?php echo htmlspecialchars($horario['hora']); ?></td>
    <!-- Mostrar el módulo (Guardia o el que escribió) -->
    <td><?php echo htmlspecialchars($horario['modulo']); ?></td>
    <!-- Botón para eliminar este horario -->
    <td>
      <form method="POST">
        <!-- ID oculto del horario a eliminar -->
        <input type="hidden" name="id_h" value="<?php echo $horario['id_h']; ?>">
        <!-- Botón rojo para eliminar -->
        <button class="btn-borrar" name="eliminar">Eliminar</button>
      </form>
    </td>
  </tr>
  <?php endwhile; ?>
</table>
<?php else: ?>
<!-- Mensaje si no hay horarios asignados -->
<p>No hay horarios. Usa el formulario para añadir uno.</p>
<?php endif; ?>

<!-- <h2>Mis ausencias enviadas</h2>
<?php if ($result_absencias && $result_absencias->num_rows > 0): ?>
<table class="tabla">
<tr><th>Fecha</th><th>Tipo</th><th>Estado</th><?php if($rol==='admin'){ echo '<th>Justificante</th>'; } ?> <th>Tarea</th></tr>
<?php while ($a = $result_absencias->fetch_assoc()): ?>
<tr>
<td><?php echo htmlspecialchars($a['fecha']); ?></td>
<td><?php echo htmlspecialchars($a['tipo']); ?></td>
<td><?php echo htmlspecialchars($a['estado']); ?></td>
<?php if($rol==='admin'){ ?>
<td><?php
if (!empty($a['justificante'])) {
    $link = str_replace('../', '/', $a['justificante']);
    echo "<a href='$link' target='_blank'>Ver</a>";
} else {
    echo '-';
}
?></td>
<?php } ?>
<td><?php
if (!empty($a['tarea'])) {
    $link = str_replace('../', '/', $a['tarea']);
    echo "<a href='$link' target='_blank'>Ver</a>";
} else {
    echo '-';
}
?></td>
</tr>
<?php endwhile; ?>
</table>
<?php else: ?>
<p>No has enviado ninguna ausencia.</p>
<?php endif; ?> -->

</div>

<footer>
    <p>© 2026 Voltic. Todos los derechos reservados.</p>
</footer>

</body>
</html>
