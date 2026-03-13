<?php
// ================================================
// PÁGINA: Horario - Gestión del Horario Personal
// ================================================
// Esta página permite que cada profesor:
// - Vea su horario personal asignado
// - Agregue nuevos horarios (asignar módulo o guardia a una hora)
// - Elimine horarios que ya no necesita
// ================================================

// === INICIAR SESIÓN ===
// Verificar que el usuario está logueado
session_start();

// === VALIDAR SESIÓN ===
// Si el usuario no está logueado, redirigir a la página de login
if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

// Obtener el DNI del profesor que está logueado
$dni_profesor = $_SESSION['dni'];

// === CONECTAR A BASE DE DATOS ===
// Incluir el archivo que conecta con la base de datos
require_once '../config/conexion.php';

// === OBTENER DATOS DEL PROFESOR ===
// Buscar el nombre del profesor en la base de datos para mostrarlo después
$sql_profesor = "SELECT nombre FROM usuarios WHERE dni = '$dni_profesor'";
$res_profesor = mysqli_query($conexion, $sql_profesor);
$nombre_profesor = mysqli_num_rows($res_profesor) === 1 
    ? mysqli_fetch_assoc($res_profesor)['nombre'] 
    : $dni_profesor;

// Variable para guardar mensajes de éxito o error que se mostrarán al usuario
$mensaje = "";

// === PROCESAR FORMULARIOS POST ===
// Si el usuario envía el formulario, procesar la información
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // --- GUARDAR HORARIO ---
    // Se ejecuta cuando el usuario hace clic en el botón "Guardar"
    if (isset($_POST['asignar'])) {
        
        // Obtener el día y la hora del formulario
        $dia = $_POST['dia'];
        $hora = $_POST['hora'];
        
        // Verificar si ambas opciones están completas
        $guardia_marcada = isset($_POST['modulo_sel']) && $_POST['modulo_sel'] == 'Guardia';
        $modulo_manual_escrito = isset($_POST['modulo_manual']) && trim($_POST['modulo_manual']) != '';
        
        // Validar que NO tenga ambos rellenos (Guardia Y texto a la vez)
        if ($guardia_marcada && $modulo_manual_escrito) {
            $mensaje = "Ya has configurado esta hora como Guardia ";
        } 
        // Validar que al menos uno esté relleno
        elseif (!$guardia_marcada && !$modulo_manual_escrito) {
            $mensaje = "Debe elegir Guardia o escribir un módulo";
        } 
        // Si está todo bien, procesar el horario
        else {
            
            // Determinar qué módulo asignar (Guardia o el texto escrito)
            $modulo = $guardia_marcada ? 'Guardia' : trim($_POST['modulo_manual']);
            
            // Buscar el ID de la hora en la base de datos según el día y hora seleccionados
            $sql_hora = "SELECT id_hora FROM hora WHERE dia = '$dia' AND hora = '$hora'";
            $res_hora = mysqli_query($conexion, $sql_hora);
            
            if (mysqli_num_rows($res_hora) == 1) {
                
                // Obtener el ID de la hora
                $id_hora = mysqli_fetch_assoc($res_hora)['id_hora'];
                
                // Verificar si este profesor ya tiene un horario asignado en esa hora
                $sql_check = "SELECT id_h FROM horario WHERE dni_u = '$dni_profesor' AND id_hora = '$id_hora'";
                $res_check = mysqli_query($conexion, $sql_check);
                
                // Si ya existe el horario, actualizarlo. Si no, crearlo nuevo
                if (mysqli_num_rows($res_check) > 0) {
                    // Actualizar el módulo existente
                    $sql = "UPDATE horario SET modulo = '$modulo' WHERE dni_u = '$dni_profesor' AND id_hora = '$id_hora'";
                    $tipo_accion = "actualizado";
                } else {
                    // Crear un nuevo horario para el profesor
                    $sql = "INSERT INTO horario (dni_u, id_hora, modulo) VALUES ('$dni_profesor', '$id_hora', '$modulo')";
                    $tipo_accion = "asignado";
                }
                
                // Ejecutar la operación (actualización o inserción)
                if (mysqli_query($conexion, $sql)) {
                    // Si se ejecutó correctamente, mostrar un mensaje de éxito
                    $mensaje = "Horario $tipo_accion correctamente";
                } else {
                    $mensaje = "Error al guardar el horario";
                }
            } else {
                $mensaje = "Hora no válida";
            }
        }
    }
    
    // --- ELIMINAR HORARIO ---
    // Se ejecuta cuando el usuario hace clic en el botón "Eliminar"
    if (isset($_POST['eliminar'])) {
        // Obtener el ID del horario a eliminar
        $id_h = (int)$_POST['id_h'];
        // Eliminar el horario de la base de datos
        $sql = "DELETE FROM horario WHERE id_h = '$id_h' AND dni_u = '$dni_profesor'";
        
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "Horario eliminado correctamente";
        } else {
            $mensaje = "Error al eliminar el horario";
        }
    }
}

// === CONSULTAR HORARIO ACTUAL DEL PROFESOR ===
// Buscar en la base de datos todos los horarios que tiene asignados el profesor
$sql_horario = "
    SELECT h.id_h, hr.dia, hr.hora, h.modulo 
    FROM horario h 
    JOIN hora hr ON h.id_hora = hr.id_hora 
    WHERE h.dni_u = '$dni_profesor' 
    ORDER BY hr.dia, hr.hora
";

// Ejecutar la búsqueda
$result_horario = mysqli_query($conexion, $sql_horario);

// === CONSULTAR AUSENCIAS DEL PROFESOR ===
// Buscar los registros de ausencia que ha hecho el profesor
$sql_ausencias = "SELECT * FROM ausencia WHERE dni = '$dni_profesor' ORDER BY fecha DESC";
$result_ausencias = mysqli_query($conexion, $sql_ausencias);

// === MAPEO DE DÍAS ===
// Convertir los códigos de día (L, M, X, J, V) a nombres complete en español
// Esto se usa para mostrar "Lunes" en lugar de "L"
$dias_nombre = array(
    'L' => 'Lunes',
    'M' => 'Martes',
    'X' => 'Miércoles',
    'J' => 'Jueves',
    'V' => 'Viernes'
);

// === CARGAR VISTA ===
include '../views/horario2.php';

// === CERRAR CONEXIÓN ===
mysqli_close($conexion);
?>