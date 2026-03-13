<?php //require_once __DIR__ . '/añadirhorariologica.php'; 
setcookie('session', 'valor', [
    'expires' => time() + 3600, // Expira en una hora
    'httponly' => true,
])
?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Usuarios</title>

<link rel="stylesheet" href="../css/basic.css">
<link rel="stylesheet" href="../css/ver_usuarios.css">
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

<div class="panel">



<h1>Usuarios registrados</h1>

<input type="text" id="buscador" class="buscador" placeholder="Buscar usuario...">

<?php if ($numero_usuarios > 0): ?>

<table class="tabla">

<thead>

<tr>
<th>DNI</th>
<th>Nombre</th>
<th>Apellido</th>
<th>Correo</th>
<th>Rol</th>
<th>Familia</th>
<th>Acción</th>
</tr>

</thead>

<tbody id="tablaUsuarios">

<?php while ($usuario = $resultado_usuarios->fetch_assoc()): ?>

<tr>

<td><?php echo htmlspecialchars($usuario['dni']); ?></td>

<td><?php echo htmlspecialchars($usuario['nombre']); ?></td>

<td><?php echo htmlspecialchars($usuario['apellido']); ?></td>

<td><?php echo htmlspecialchars($usuario['ce']); ?></td>

<td><?php echo htmlspecialchars($usuario['rol']); ?></td>

<td><?php echo htmlspecialchars($usuario['familia']); ?></td>

<td>

<?php if ($usuario['dni'] !== $dni_admin): ?>

<div class="botones-accion">
<a href="/php/editar_usuario.php?dni=<?php echo urlencode($usuario['dni']); ?>" class="btn-editar">Editar</a>

<form method="POST" onsubmit="return confirm('¿Seguro que quieres borrar este usuario?');" style="display:inline;">

<input type="hidden" name="dni_usuario" value="<?php echo $usuario['dni']; ?>">

<button class="btn-borrar" name="borrar_usuario">Eliminar</button>

</form>
</div>

<?php else: ?>

-

<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

<?php else: ?>

<p>No hay usuarios registrados</p>

<?php endif; ?>

</div>

<script>

document.getElementById("buscador").addEventListener("keyup", function() {

let filtro = this.value.toLowerCase();

let filas = document.querySelectorAll("#tablaUsuarios tr");

filas.forEach(function(fila){

let textoFila = fila.textContent.toLowerCase();

if(textoFila.includes(filtro)){
fila.style.display = "";
}else{
fila.style.display = "none";
}

});

});

</script>

<footer>
    <p>© 2026 Voltic. Todos los derechos reservados.</p>
</footer>

</body>

</html>
