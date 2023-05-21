<?php
declare(strict_types=1);

// Función para establecer la conexión a la base de datos
    function conectarBD(): mysqli {
        $servidor = "192.168.1.101"; // Cambiar al nombre de tu servidor de base de datos
        $usuario = "MariaDBRoot"; // Cambiar al nombre de usuario de tu base de datos
        $contraseña = "2623141"; // Cambiar a la contraseña de tu base de datos
        $nombreBD = "Prueba3"; // Cambiar al nombre de tu base de datos
        
        // Crear una conexión
        $conexion = new mysqli($servidor, $usuario, $contraseña, $nombreBD);
        
        // Verificar si hay errores de conexión
        if ($conexion->connect_errno) {
            die("Error de conexión: " . $conexion->connect_error);
        }
        
        // Establecer el juego de caracteres
        $conexion->set_charset("utf8");
        
        return $conexion;
    }

    function cerrarBD(mysqli $conexion) : void {
        $conexion->close();
    }
function cerrarBD2(mysqli $conexion,mysqli_result $result) : void {
    $result->free_result();
    $conexion->close();
}
?>