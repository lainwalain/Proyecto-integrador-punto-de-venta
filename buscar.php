<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "cajero");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir parámetro de búsqueda
$busqueda = isset($_GET['busqueda']) ? $conexion->real_escape_string($_GET['busqueda']) : "";

// Consultar productos que coincidan
$sql = "SELECT id, nombre, precio FROM productos 
        WHERE nombre LIKE '%$busqueda%' LIMIT 10";

$resultado = $conexion->query($sql);

// Preparar respuesta en JSON
$productos = [];
while ($fila = $resultado->fetch_assoc()) {
    $productos[] = $fila;
}

// Devolver JSON
header('Content-Type: application/json');
echo json_encode($productos);

// Cerrar conexión
$conexion->close();
?>
