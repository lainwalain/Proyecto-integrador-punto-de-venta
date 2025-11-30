<?php
session_start();
require_once '../includes/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['sesion_email']) || $_SESSION['id_rol'] != 3) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$index = $_POST['index'] ?? '';

if ($index === '' || !isset($_SESSION['carrito'][$index])) {
    echo json_encode(['success' => false, 'message' => 'Ítem no encontrado']);
    exit;
}

try {
    // Eliminar el item del carrito
    array_splice($_SESSION['carrito'], $index, 1);
    
    echo json_encode(['success' => true, 'message' => 'Producto eliminado del carrito']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar producto: ' . $e->getMessage()]);
}
?>