<?php
session_start();
require_once '../includes/database.php';
require_once '../includes/funciones.php';

header('Content-Type: application/json');

if (!isset($_SESSION['sesion_email']) || $_SESSION['id_rol'] != 3) {
    echo json_encode([]);
    exit;
}

$termino = $_GET['termino'] ?? '';

if (strlen($termino) < 2) {
    echo json_encode([]);
    exit;
}

try {
    $productos = obtenerProductosBusqueda($termino, $pdo);
    echo json_encode($productos);
} catch (Exception $e) {
    echo json_encode([]);
}
?>