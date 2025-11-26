<?php
include "conexion.php"; // tu conexión

$codigo = $_GET["codigo"] ?? "";

$sentencia = $pdo->prepare("SELECT * FROM productos WHERE codigo_barras = ?");
$sentencia->execute([$codigo]);

echo json_encode($sentencia->fetch(PDO::FETCH_ASSOC));
