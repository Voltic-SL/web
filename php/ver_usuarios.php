<?php
// Esta página permite a los admins ver y borrar usuarios.

// Iniciar sesión.
session_start();

// Conectar a BD.
require_once '../config/conexion.php';

// Si no hay sesión, ir a login.
if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

// DNI del admin.
$dni_admin = $_SESSION['dni'];

// Verificar que es admin.
$sql = "SELECT rol FROM usuarios WHERE dni = '$dni_admin'";
$resultado = mysqli_query($conexion, $sql);
if (mysqli_num_rows($resultado) !== 1) {
    die("Usuario no encontrado");
}
$fila = mysqli_fetch_assoc($resultado);
if ($fila['rol'] !== 'admin') {
    die("No tienes permiso");
}

// Si se pulsó borrar usuario.
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['borrar_usuario'])) {
    $dni_borrar = $_POST['dni_usuario'];

    // No borrar a sí mismo.
    if ($dni_borrar !== $dni_admin) {

        // Obtener id_h de horarios del usuario.
        $sql_get_id_h = "SELECT id_h FROM horario WHERE dni_u = '$dni_borrar'";
        $res_id_h = mysqli_query($conexion, $sql_get_id_h);
        $id_h_list = [];
        while ($row = mysqli_fetch_assoc($res_id_h)) {
            $id_h_list[] = $row['id_h'];
        }

        // Si hay horarios, eliminar ausencias relacionadas.
        if (!empty($id_h_list)) {
            $id_h_str = implode(',', $id_h_list);

            // Borrar archivos de justificantes y tareas.
            $sql_files = "SELECT justificante, tarea FROM ausencia WHERE id_h IN ($id_h_str)";
            $res_files = mysqli_query($conexion, $sql_files);
            while ($row = mysqli_fetch_assoc($res_files)) {
                if (!empty($row['justificante']) && file_exists($row['justificante'])) {
                    unlink($row['justificante']);
                }
                if (!empty($row['tarea']) && file_exists($row['tarea'])) {
                    unlink($row['tarea']);
                }
            }

            // Eliminar ausencias.
            $sql_del_aus_h = "DELETE FROM ausencia WHERE id_h IN ($id_h_str)";
            mysqli_query($conexion, $sql_del_aus_h);
        }

        // Limpiar referencias donde cubre.
        $sql1 = "UPDATE ausencia SET dni_cubre = NULL WHERE dni_cubre = '$dni_borrar'";
        mysqli_query($conexion, $sql1);

        // Borrar archivos de sus ausencias.
        $sql_files = "SELECT justificante, tarea FROM ausencia WHERE dni = '$dni_borrar'";
        $res_files = mysqli_query($conexion, $sql_files);
        while ($row = mysqli_fetch_assoc($res_files)) {
            if (!empty($row['justificante']) && file_exists($row['justificante'])) {
                unlink($row['justificante']);
            }
            if (!empty($row['tarea']) && file_exists($row['tarea'])) {
                unlink($row['tarea']);
            }
        }

        // Eliminar ausencias del usuario.
        $sql2 = "DELETE FROM ausencia WHERE dni = '$dni_borrar'";
        mysqli_query($conexion, $sql2);

        // Eliminar horarios.
        $sql3 = "DELETE FROM horario WHERE dni_u = '$dni_borrar'";
        mysqli_query($conexion, $sql3);

        // Eliminar usuario.
        $sql4 = "DELETE FROM usuarios WHERE dni='$dni_borrar'";
        mysqli_query($conexion, $sql4);
    }
}

// Consultar todos los usuarios.
$sql_usuarios = "SELECT dni, nombre, apellido, ce, rol, familia FROM usuarios ORDER BY nombre, apellido";
$resultado_usuarios = mysqli_query($conexion, $sql_usuarios);
if (!$resultado_usuarios) {
    die("Error en consulta");
}
$numero_usuarios = mysqli_num_rows($resultado_usuarios);

// Nombre del admin.
$dni_profesor = $_SESSION['dni'];
$sql = "SELECT nombre FROM usuarios WHERE dni = '$dni_profesor'";
$res_nombre = mysqli_query($conexion, $sql);
$nombre_profesor = mysqli_num_rows($res_nombre) === 1 ? mysqli_fetch_assoc($res_nombre)['nombre'] : $dni_profesor;

// Mostrar vista.
include '../views/ver_usuarios2.php';

// Cerrar conexión.
mysqli_close($conexion);
?>
