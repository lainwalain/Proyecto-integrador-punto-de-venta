<?php
session_start();
require_once '../includes/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['sesion_email']) || $_SESSION['id_rol'] != 3) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

try {
    // Limpiar carrito
    $_SESSION['carrito'] = [];
    
    echo json_encode(['success' => true, 'message' => 'Venta cancelada']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al cancelar venta: ' . $e->getMessage()]);
}
?>