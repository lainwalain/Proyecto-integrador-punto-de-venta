<?php
session_start();
require_once '../includes/database.php';
require_once '../includes/funciones.php';

header('Content-Type: application/json');

// Debug: Verificar sesión
if (!isset($_SESSION['sesion_email']) || $_SESSION['id_rol'] != 3) {
    error_log("No autorizado - Sesión: " . print_r($_SESSION, true));
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$id_producto = $_POST['id_producto'] ?? '';

if (empty($id_producto)) {
    echo json_encode(['success' => false, 'message' => 'ID de producto inválido']);
    exit;
}

try {
    // Debug: Verificar conexión
    if (!$pdo) {
        throw new Exception("No hay conexión a la base de datos");
    }

    // Obtener información del producto
    $stmt = $pdo->prepare("SELECT * FROM tb_almacen WHERE id_producto = ? AND stock > 0");
    $stmt->execute([$id_producto]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$producto) {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado o sin stock']);
        exit;
    }
    
    // Inicializar carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    
    // Verificar si el producto ya está en el carrito
    $productoEncontrado = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id_producto'] == $id_producto) {
            // Verificar stock disponible
            if ($item['cantidad'] < $producto['stock']) {
                $item['cantidad'] += 1;
                $productoEncontrado = true;
                break;
            } else {
                echo json_encode(['success' => false, 'message' => 'Stock insuficiente']);
                exit;
            }
        }
    }
    
    // Si no está en el carrito, agregarlo
    if (!$productoEncontrado) {
        $_SESSION['carrito'][] = [
            'id_producto' => $producto['id_producto'],
            'codigo' => $producto['codigo'],
            'nombre' => $producto['nombre'],
            'precio_venta' => $producto['precio_venta'],
            'cantidad' => 1,
            'stock_disponible' => $producto['stock']
        ];
    }
    
    echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito']);
    
} catch (Exception $e) {
    error_log("Error en agregar_carrito: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al agregar producto: ' . $e->getMessage()]);
}
?>