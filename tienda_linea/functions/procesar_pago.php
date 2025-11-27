<?php
session_start();
include '../includes/config.php';
include 'usuario_functions.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit();
}

// Obtener datos del carrito desde localStorage (se enviarán por AJAX)
$input = json_decode(file_get_contents('php://input'), true);
$carrito = $input['carrito'] ?? [];

if (empty($carrito)) {
    echo json_encode(['success' => false, 'error' => 'Carrito vacío']);
    exit();
}

// Calcular total
$subtotal = 0;
foreach ($carrito as $producto) {
    $subtotal += $producto['precio'] * $producto['cantidad'];
}
$envio = $subtotal > 500 ? 0 : 50;
$total_pagado = $subtotal + $envio;

// Procesar el pedido
$resultado = procesarPedido($pdo, $_SESSION['id_usuario'], $carrito, $total_pagado);

if ($resultado['success']) {
    echo json_encode([
        'success' => true, 
        'nro_venta' => $resultado['nro_venta'],
        'mensaje' => 'Pedido procesado exitosamente'
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'error' => $resultado['error']
    ]);
}
?>