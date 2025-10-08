<?php
$host = "localhost";
$user = "root";       // usuario de MySQL
$pass = "";           // contraseña de MySQL
$db   = "marketgo_usuarios"; // nombre de la base de datos

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>