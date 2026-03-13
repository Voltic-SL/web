<?php
// ================================================
// PÁGINA: Horario General - Gestión de Ausencias
// ================================================
// Muestra las ausencias registradas y permite a profesores
// aceptarlas o cubrirlas según sus guardias asignadas.
// ================================================

// Iniciar sesión y conectar a base de datos
session_start();
require_once '../config/conexion.php';

// === VALIDAR SESIÓN ===
if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

$dni_profesor = $_SESSION['dni'];

// === FUNCIÓN AUXILIAR: Actualizar estado de ausencia ===
function actualizar_ausencia($conexion, $id, $nuevo_estado, $dni_cubre = null) {
    global $dni_profesor;
    
    $id = (int)$id;
    
    // Verificar que la ausencia existe
    $sql_check = "SELECT dni FROM ausencia WHERE id_ausencia = $id";
    $res_check = mysqli_query($conexion, $sql_check);
    
    if (mysqli_num_rows($res_check) !== 1) return false;
    
    $fila = mysqli_fetch_assoc($res_check);
    
    // Evitar que se acepte la propia ausencia
    if ($fila['dni'] === $dni_profesor) return false;
    
    // Actualizar estado
    if ($dni_cubre) {
        $sql = "UPDATE ausencia SET estado = '$nuevo_estado', dni_cubre = '$dni_cubre' WHERE id_ausencia = $id";
    } else {
        $sql = "UPDATE ausencia SET estado = '$nuevo_estado' WHERE id_ausencia = $id";
    }
    
    return mysqli_query($conexion, $sql);
}

// === PROCESAR FORMULARIOS POST ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['aceptar_ausencia'])) {
        actualizar_ausencia($conexion, $_POST['aceptar_ausencia'], 'aceptada');
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    if (isset($_POST['cubrir_ausencia'])) {
        actualizar_ausencia($conexion, $_POST['cubrir_ausencia'], 'cubierta', $dni_profesor);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// === OBTENER DATOS DEL PROFESOR ===
$sql_profesor = "SELECT nombre, rol FROM usuarios WHERE dni = '$dni_profesor'";
$res_profesor = mysqli_query($conexion, $sql_profesor);
$nombre_profesor = $dni_profesor;
$rol_profesor = '';

if (mysqli_num_rows($res_profesor) === 1) {
    $datos_profesor = mysqli_fetch_assoc($res_profesor);
    $nombre_profesor = $datos_profesor['nombre'];
    $rol_profesor = $datos_profesor['rol'];
}

// === MAPEO DE DÍAS ===
// Convertir códigos de día (L, M, X, etc.) a nombres legibles
$dias_nombre = array(
    'L' => 'Lunes',
    'M' => 'Martes',
    'X' => 'Miércoles',
    'J' => 'Jueves',
    'V' => 'Viernes'
);

// === CONSULTAR AUSENCIAS ACTIVAS ===
$sql_ausencias = "
    SELECT a.id_ausencia, a.dni, a.estado, a.justificante, a.tarea,
           u1.nombre as nombre_ausencia, u1.apellido as apellido_ausencia,
           u2.nombre as nombre_cubre, u2.apellido as apellido_cubre,
           h.dia, h.hora, a.fecha, a.aula as modulo
    FROM ausencia a
    JOIN usuarios u1 ON a.dni = u1.dni
    LEFT JOIN usuarios u2 ON a.dni_cubre = u2.dni
    JOIN horario hor ON a.id_h = hor.id_h
    JOIN hora h ON hor.id_hora = h.id_hora
    WHERE a.estado IN ('pendiente', 'aceptada', 'cubierta', 'denegada')
    ORDER BY a.fecha DESC
";

$result_ausencias = mysqli_query($conexion, $sql_ausencias);

// === CONSULTAR GUARDIAS DEL PROFESOR ===
// Almacena qué horarios de guardia tiene el profesor actual
$guardias_usuario = array();
$sql_guardias = "
    SELECT hr.dia, hr.hora
    FROM horario h
    JOIN hora hr ON h.id_hora = hr.id_hora
    WHERE h.dni_u = '$dni_profesor' AND h.modulo = 'Guardia'
";

$res_guardias = mysqli_query($conexion, $sql_guardias);
if ($res_guardias) {
    while ($guardia = mysqli_fetch_assoc($res_guardias)) {
        // Clave combinada día-hora para búsqueda rápida
        $guardias_usuario[$guardia['dia'] . '-' . $guardia['hora']] = true;
    }
}

// === CONSULTAR GUARDIAS CUBIERTAS ===
$dia_filtro = isset($_GET['dia']) ? $_GET['dia'] : '';
$hora_filtro = isset($_GET['hora']) ? $_GET['hora'] : '';

$sql_cubiertas = "
    SELECT a.dni_cubre, h.dia, h.hora, COUNT(*) as total
    FROM ausencia a
    JOIN horario hor ON a.id_h = hor.id_h
    JOIN hora h ON hor.id_hora = h.id_hora
    WHERE a.dni_cubre IS NOT NULL AND a.estado = 'cubierta'
";

if (!empty($dia_filtro)) {
    $sql_cubiertas .= " AND h.dia = '" . mysqli_real_escape_string($conexion, $dia_filtro) . "'";
}

if (!empty($hora_filtro)) {
    $sql_cubiertas .= " AND h.hora = " . (int)$hora_filtro;
}

$sql_cubiertas .= " GROUP BY a.dni_cubre, h.dia, h.hora";

$result_cubiertas = mysqli_query($conexion, $sql_cubiertas);

// === CARGAR VISTA ===
include '../views/horario_general2.php';

// === CERRAR CONEXIÓN ===
mysqli_close($conexion);
?>