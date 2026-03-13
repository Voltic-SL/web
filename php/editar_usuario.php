<?php
// Página para editar información de usuarios
// Solo accesible para administradores

// Iniciar sesión
session_start();

// Conectar a base de datos
require_once '../config/conexion.php';

// Verificar que el usuario está logueado
if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

// Verificar que es admin
$dni_admin = $_SESSION['dni'];
$sql = "SELECT rol FROM usuarios WHERE dni = '$dni_admin'";
$resultado = mysqli_query($conexion, $sql);
if (mysqli_num_rows($resultado) !== 1) {
    die("Usuario no encontrado");
}
$fila = mysqli_fetch_assoc($resultado);
if ($fila['rol'] !== 'admin') {
    die("No tienes permiso");
}

// Obtener el DNI del usuario a editar
if (!isset($_GET['dni'])) {
    die("DNI no especificado");
}
$dni_usuario = $_GET['dni'];

// Obtener datos actuales del usuario
$sql = "SELECT dni, nombre, apellido, ce, rol, familia FROM usuarios WHERE dni = '$dni_usuario'";
$resultado = mysqli_query($conexion, $sql);
if (mysqli_num_rows($resultado) !== 1) {
    die("Usuario no encontrado");
}
$usuario = mysqli_fetch_assoc($resultado);

// Mensaje de éxito o error
$mensaje = "";

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    // Obtener datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $ce = trim($_POST['ce']);
    $rol = trim($_POST['rol']);
    $familia = trim($_POST['familia']);
    
    // Validar que no estén vacíos
    if (empty($nombre) || empty($apellido) || empty($ce)) {
        $mensaje = "Error: Los campos Nombre, Apellido y Correo son obligatorios";
    } else {
        // Actualizar usuario en la base de datos
        $sql_update = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', ce = '$ce', rol = '$rol', familia = '$familia' WHERE dni = '$dni_usuario'";
        if (mysqli_query($conexion, $sql_update)) {
            $mensaje = "Usuario actualizado correctamente";
            // Actualizar los datos locales para mostrarlos
            $usuario['nombre'] = $nombre;
            $usuario['apellido'] = $apellido;
            $usuario['ce'] = $ce;
            $usuario['rol'] = $rol;
            $usuario['familia'] = $familia;
        } else {
            $mensaje = "Error al actualizar: " . mysqli_error($conexion);
        }
    }
}

// Nombre del admin
$sql_nombre = "SELECT nombre FROM usuarios WHERE dni = '$dni_admin'";
$res_nombre = mysqli_query($conexion, $sql_nombre);
$nombre_admin = mysqli_num_rows($res_nombre) === 1 ? mysqli_fetch_assoc($res_nombre)['nombre'] : $dni_admin;

// Cerrar conexión
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../css/basic.css">
    <link rel="stylesheet" href="../css/ver_usuarios.css">
    <style>
        .formulario-editar {
            display: flex;
            flex-direction: column;
            gap: 0;
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .formulario-grupo {
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        
        .formulario-grupo:last-of-type {
            border-bottom: none;
        }
        
        .formulario-grupo label {
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }
        
        .formulario-grupo input,
        .formulario-grupo select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .formulario-grupo input:focus,
        .formulario-grupo select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .formulario-grupo input:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }
        
        .botones-formulario {
            display: flex;
            gap: 10px;
            justify-content: center;
            padding: 20px 15px;
            border-top: 1px solid #ddd;
        }
        
        .btn-guardar {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-guardar:hover {
            background: #1d4ed8;
        }
        
        .btn-volver {
            background: #6b7280;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-volver:hover {
            background: #4b5563;
        }
        
        .mensaje {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
        }
        
        .mensaje.exito {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        
        .mensaje.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
    </style>
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
            <span class="nav-user"><?php echo htmlspecialchars($nombre_admin); ?></span>
            <a class="logout" href="/php/logout.php">Salir</a>
        </div>
    </nav>
</header>

<div class="panel">
    <div style="margin-bottom: 20px;">
        <a href="/php/ver_usuarios.php" class="btn-volver">← Volver a usuarios</a>
    </div>
    
    <h1>Editar Usuario</h1>
    
    <?php if (!empty($mensaje)): ?>
        <p class="mensaje <?php echo strpos($mensaje, 'Error') === false ? 'exito' : 'error'; ?>">
            <?php echo htmlspecialchars($mensaje); ?>
        </p>
    <?php endif; ?>
    
    <!-- Formulario para editar usuario -->
    <form method="POST" class="formulario-editar">
        <div class="formulario-grupo">
            <label>DNI (no se puede cambiar):</label>
            <input type="text" value="<?php echo htmlspecialchars($usuario['dni']); ?>" disabled>
        </div>
        
        <div class="formulario-grupo">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        </div>
        
        <div class="formulario-grupo">
            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
        </div>
        
        <div class="formulario-grupo">
            <label>Correo electrónico:</label>
            <input type="email" name="ce" value="<?php echo htmlspecialchars($usuario['ce']); ?>" required>
        </div>
        
        <div class="formulario-grupo">
            <label>Rol:</label>
            <select name="rol">
                <option value="profesor" <?php echo $usuario['rol'] === 'profesor' ? 'selected' : ''; ?>>Profesor</option>
                <option value="admin" <?php echo $usuario['rol'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        
        <div class="formulario-grupo">
            <label>Familia:</label>
            <input type="text" name="familia" value="<?php echo htmlspecialchars($usuario['familia']); ?>">
        </div>
        
        <div class="botones-formulario">
            <button type="submit" name="actualizar" class="btn-guardar">Guardar cambios</button>
        </div>
    </form>
</div>

<footer>
    <p>© 2026 Voltic. Todos los derechos reservados.</p>
</footer>

</body>
</html>
