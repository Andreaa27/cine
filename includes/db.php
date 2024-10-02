<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "cine_db";

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
}
?>
