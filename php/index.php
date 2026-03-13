<?php
// Este archivo procesa el login. Aquí comprobamos el DNI y contraseña.

// Iniciar la sesión.
session_start();

// Conectar a la base de datos.
require_once '../config/conexion.php';

// Si ya está logueado, ir a horario.
if(isset($_SESSION['dni'])){
    header("Location: horario.php");
    exit();
}

// Variable para error.
$error = "";

// Si se envió formulario (POST).
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Tomar DNI y contraseña.
    $dni = $_POST["dni"] ?? "";
    $password = $_POST["password"] ?? "";

    // Si están vacíos.
    if($dni == "" || $password == ""){
        $error = "Pon tu DNI y contraseña";
    }else{

        // Buscar usuario en BD.
        $sql = "SELECT * FROM usuarios WHERE dni='$dni'";
        $resultado = mysqli_query($conexion, $sql);

        // Si encontró el usuario.
        if(mysqli_num_rows($resultado) == 1){

            // Obtener datos del usuario.
            $usuario = mysqli_fetch_assoc($resultado);

            // Contraseña en BD.
            $passwordBD = $usuario["contraseña"];

            // Verificar contraseña.
            if(password_verify($password, $passwordBD)){

                // Guardar en sesión.
                $_SESSION["dni"] = $usuario["dni"];
                $_SESSION["nombre"] = $usuario["nombre"];
                $_SESSION["rol"] = $usuario["rol"];

                // Ir a horario.
                header("Location: horario.php");
                exit();

            }else{
                $error = "Contraseña mala";
            }

        }else{
            $error = "DNI no existe";
        }
    }
}

// Mostrar la vista del login.
include '../views/login.php';

// Cerrar conexión.
mysqli_close($conexion);
?>