<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_de_datos = "incidencias_db";


$conexion = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);


if ($conexion->connect_error) {

    die("La conexión a la base de datos falló: " . $conexion->connect_error);
}


$conexion->set_charset("utf8mb4");

// echo "¡Conexión exitosa a la base de datos!";

?>
