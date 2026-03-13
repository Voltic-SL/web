<?php
session_start();
require_once '../config/conexion.php';

if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

$dni = $_SESSION['dni'];

$sql = "SELECT rol FROM usuarios WHERE dni = '$dni'";
$resultado = mysqli_query($conexion, $sql);

$rol = "";
if (mysqli_num_rows($resultado) == 1) {
    $rol = mysqli_fetch_assoc($resultado)['rol'];
}

if ($rol != "admin") {
    echo "Acceso denegado";
    exit();
}

/* ACCIONES ADMIN */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = intval($_POST['id_ausencia']);

    if (isset($_POST['aceptar'])) {
        $sql = "UPDATE ausencia SET estado='aceptada' WHERE id_ausencia=$id";
        mysqli_query($conexion, $sql);
    }

    if (isset($_POST['denegar'])) {
        $sql = "UPDATE ausencia SET estado='denegada' WHERE id_ausencia=$id";
        mysqli_query($conexion, $sql);
    }

    if (isset($_POST['eliminar'])) {

        // obtener rutas de justificante y tarea para borrar archivos físicos
        $sql = "SELECT justificante, tarea FROM ausencia WHERE id_ausencia=$id";
        $res = mysqli_query($conexion, $sql);

        if (mysqli_num_rows($res) == 1) {
            $fila = mysqli_fetch_assoc($res);
            if (!empty($fila['justificante']) && file_exists($fila['justificante'])) {
                unlink($fila['justificante']);
            }
            if (!empty($fila['tarea']) && file_exists($fila['tarea'])) {
                unlink($fila['tarea']);
            }
        }

        $sql = "DELETE FROM ausencia WHERE id_ausencia=$id";
        mysqli_query($conexion, $sql);
    }
}

/* CONSULTAS */

$pendientes = $conexion->query("
SELECT a.*, u.nombre, u.apellido, hr.dia, hr.hora
FROM ausencia a
LEFT JOIN usuarios u ON a.dni = u.dni
LEFT JOIN horario h ON a.id_h = h.id_h
LEFT JOIN hora hr ON h.id_hora = hr.id_hora
WHERE a.estado = 'pendiente'
");
// (la consulta ya trae todas las columnas de a.*, incluidas justificante y tarea si existen)

$aceptadas = $conexion->query("
SELECT a.*, u.nombre, u.apellido, hr.dia, hr.hora,
uc.nombre AS cubre_nombre, uc.apellido AS cubre_apellido, uc.dni AS dni_cubre
FROM ausencia a
LEFT JOIN usuarios u ON a.dni = u.dni
LEFT JOIN horario h ON a.id_h = h.id_h
LEFT JOIN hora hr ON h.id_hora = hr.id_hora
LEFT JOIN usuarios uc ON a.dni_cubre = uc.dni
WHERE a.estado = 'aceptada' OR a.estado = 'cubierta'
");

$denegadas = $conexion->query("
SELECT a.*, u.nombre, u.apellido, hr.dia, hr.hora,
uc.nombre AS cubre_nombre, uc.apellido AS cubre_apellido, uc.dni AS dni_cubre
FROM ausencia a
LEFT JOIN usuarios u ON a.dni = u.dni
LEFT JOIN horario h ON a.id_h = h.id_h
LEFT JOIN hora hr ON h.id_hora = hr.id_hora
LEFT JOIN usuarios uc ON a.dni_cubre = uc.dni
WHERE a.estado = 'denegada'
");

$dni_profesor = $_SESSION['dni'];
$sql = "SELECT nombre FROM usuarios WHERE dni = '$dni_profesor'";
$res_nombre = mysqli_query($conexion, $sql);
$nombre_profesor = mysqli_num_rows($res_nombre) === 1 ? mysqli_fetch_assoc($res_nombre)['nombre'] : $dni_profesor;

include '../views/admin_ausencias2.php';

$conexion->close();
?>
