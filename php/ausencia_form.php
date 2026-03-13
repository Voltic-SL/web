<?php
// ================================================
// PÁGINA: Comunicar Ausencia
// ================================================
// Permite al profesor logueado registrar ausencias
// en franjas horarias específicas del día.
// ================================================

// === INICIAR SESIÓN ===
session_start();
require_once '../config/conexion.php';

// === VALIDAR SESIÓN ===
if (!isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit();
}

// Variable para mostrar mensajes
$mensaje = "";
$dni = $_SESSION['dni'];

// === PROCESAR FORMULARIO POST ===
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener datos del formulario
    $id_h_array = isset($_POST["id_h"]) ? $_POST["id_h"] : [];
    $tipo = trim($_POST["tipo"]);
    $aula = trim($_POST["aula"]);
    $texto = trim($_POST["texto"]);
    $fecha = $_POST["fecha"];
    
    // Validar que se seleccionó al menos una franja horaria
    if (empty($id_h_array)) {
        $mensaje = "❌ Debe seleccionar al menos una franja horaria.";
    } else {
        
        // === PROCESAR ARCHIVOS SUBIDOS ===
        $justificante = "";
        $tarea = "";
        
        // --- Procesar justificante (PDF) ---
        if (!empty($_FILES["justificante"]["name"])) {
            $ext = strtolower(pathinfo($_FILES["justificante"]["name"], PATHINFO_EXTENSION));
            if ($ext == "pdf") {
                $carpeta = "/var/www/pdfs/carpeta1/";
                if (!is_dir($carpeta)) {
                    mkdir($carpeta, 0755, true);
                }
                // Nombre único con timestamp
                $nombre = time() . "_" . $_FILES["justificante"]["name"];
                $ruta = $carpeta . $nombre;
                if (move_uploaded_file($_FILES["justificante"]["tmp_name"], $ruta)) {
                    $justificante = $ruta;
                }
            }
        }
        
        // --- Procesar tarea (PDF) ---
        if (!empty($_FILES["tarea"]["name"])) {
            $ext = strtolower(pathinfo($_FILES["tarea"]["name"], PATHINFO_EXTENSION));
            if ($ext == "pdf") {
                $carpeta_t = "/var/www/pdfs/carpeta2/";
                if (!is_dir($carpeta_t)) {
                    mkdir($carpeta_t, 0755, true);
                }
                // Nombre único con timestamp
                $nombre_t = time() . "_" . $_FILES["tarea"]["name"];
                $ruta_t = $carpeta_t . $nombre_t;
                if (move_uploaded_file($_FILES["tarea"]["tmp_name"], $ruta_t)) {
                    $tarea = $ruta_t;
                }
            }
        }
        
        // === INSERTAR AUSENCIAS EN BASE DE DATOS ===
        $insertadas = 0;
        $errores = "";
        
        foreach ($id_h_array as $id_h) {
            $id_h = intval($id_h);
            
            // Verificar que la franja pertenece al profesor actual
            $sql_check = "SELECT dni_u FROM horario WHERE id_h = $id_h";
            $res_check = mysqli_query($conexion, $sql_check);
            $row = mysqli_fetch_assoc($res_check);
            
            // Solo insertar si la franja pertenece al profesor
            if ($row && $row["dni_u"] == $dni) {
                $sql_insert = "
                    INSERT INTO ausencia 
                    (id_h, dni, tipo, aula, texto, justificante, tarea, estado, fecha) 
                    VALUES ($id_h, '$dni', '$tipo', '$aula', '$texto', '$justificante', '$tarea', 'pendiente', '$fecha')
                ";
                
                if (mysqli_query($conexion, $sql_insert)) {
                    $insertadas++;
                } else {
                    $errores .= "Error en franja $id_h. ";
                }
            }
        }
        
        // Mostrar resultado
        if ($insertadas > 0) {
            $mensaje = "✅ Se registraron $insertadas ausencia(s) correctamente.";
            if (!empty($errores)) {
                $mensaje .= " ⚠️ $errores";
            }
        } else {
            $mensaje = "Error al registrar las ausencias. $errores";
        }        
    }
}

// === CONSULTAR HORARIO DEL PROFESOR ===
// Obtener franjas horarias que NO son guardias (módulos asignados)
// para que solo pueda registrar ausencias en esos horarios
$sql_horario = "
    SELECT h.id_h, hr.dia, hr.hora, h.modulo 
    FROM horario h 
    JOIN hora hr ON h.id_hora = hr.id_hora 
    WHERE h.dni_u = '$dni' AND LOWER(h.modulo) != 'guardia' 
    ORDER BY hr.dia, hr.hora
";

$result_horario = mysqli_query($conexion, $sql_horario);

// === OBTENER NOMBRE DEL PROFESOR ===
$sql_nombre = "SELECT nombre FROM usuarios WHERE dni = '$dni'";
$res_nombre = mysqli_query($conexion, $sql_nombre);
$nombre_profesor = $res_nombre && mysqli_num_rows($res_nombre) > 0 
    ? mysqli_fetch_assoc($res_nombre)['nombre'] 
    : $dni;

// === CARGAR VISTA ===
include '../views/ausencia_form2.php';

// === CERRAR CONEXIÓN ===
mysqli_close($conexion);
?>
