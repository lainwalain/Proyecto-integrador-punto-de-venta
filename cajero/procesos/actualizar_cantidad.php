<?php
session_start();
require_once '../includes/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['sesion_email']) || $_SESSION['id_rol'] != 3) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$index = $_POST['index'] ?? '';
$cambio = intval($_POST['cambio'] ?? 0);

if ($index === '' || !isset($_SESSION['carrito'][$index])) {
    echo json_encode(['success' => false, 'message' => 'Ítem no encontrado']);
    exit;
}

try {
    $item = &$_SESSION['carrito'][$index];
    $nuevaCantidad = $item['cantidad'] + $cambio;
    
    // Verificar stock disponible
    $stmt = $pdo->prepare("SELECT stock FROM tb_almacen WHERE id_producto = ?");
    $stmt->execute([$item['id_producto']]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$producto) {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
        exit;
    }
    
    if ($nuevaCantidad < 1) {
        echo json_encode(['success' => false, 'message' => 'La cantidad no puede ser menor a 1']);
        exit;
    }
    
    if ($nuevaCantidad > $producto['stock']) {
        echo json_encode(['success' => false, 'message' => 'Stock insuficiente. Disponible: ' . $producto['stock']]);
        exit;
    }
    
    $item['cantidad'] = $nuevaCantidad;
    $item['stock_disponible'] = $producto['stock'];
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar cantidad: ' . $e->getMessage()]);
}
?>