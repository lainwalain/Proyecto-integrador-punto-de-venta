<?php
session_start();
require_once '../includes/database.php';
require_once '../includes/funciones.php';
require_once '../includes/translations.php';

header('Content-Type: application/json');

if (!isset($_SESSION['sesion_email']) || $_SESSION['id_rol'] != 3) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$nombre_cliente = $_POST['nombre_cliente'] ?? '';
$nit_cliente = $_POST['nit_cliente'] ?? '';
$celular_cliente = $_POST['celular_cliente'] ?? '';
$email_cliente = $_POST['email_cliente'] ?? '';

// Validar email si se proporcionó
if (!empty($email_cliente) && !filter_var($email_cliente, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => t('invalid_email')]);
    exit;
}

if (empty($nombre_cliente)) {
    echo json_encode(['success' => false, 'message' => t('customer_name_required')]);
    exit;
}

if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) === 0) {
    echo json_encode(['success' => false, 'message' => t('empty_cart')]);
    exit;
}

try {
    $pdo->beginTransaction();
    
    // 1. Verificar stock antes de procesar
    foreach ($_SESSION['carrito'] as $item) {
        $stmt = $pdo->prepare("SELECT stock, nombre FROM tb_almacen WHERE id_producto = ?");
        $stmt->execute([$item['id_producto']]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$producto) {
            throw new Exception("Producto no encontrado: " . $item['nombre']);
        }
        
        if ($producto['stock'] < $item['cantidad']) {
            throw new Exception("Stock insuficiente para: " . $producto['nombre'] . ". Disponible: " . $producto['stock']);
        }
    }
    
    // 2. Registrar o obtener cliente
    $id_cliente = null;
    if (!empty($nit_cliente)) {
        $stmt = $pdo->prepare("SELECT id_cliente FROM tb_clientes WHERE nit_ci_cliente = ?");
        $stmt->execute([$nit_cliente]);
        $cliente_existente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($cliente_existente) {
            $id_cliente = $cliente_existente['id_cliente'];
            
            // Actualizar datos del cliente existente si se proporcionaron
            if (!empty($celular_cliente) || !empty($email_cliente)) {
                $updateFields = [];
                $updateParams = [];
                
                if (!empty($celular_cliente)) {
                    $updateFields[] = "celular_cliente = ?";
                    $updateParams[] = $celular_cliente;
                }
                if (!empty($email_cliente)) {
                    $updateFields[] = "email_cliente = ?";
                    $updateParams[] = $email_cliente;
                }
                
                if (!empty($updateFields)) {
                    $updateParams[] = $id_cliente;
                    $stmt = $pdo->prepare("UPDATE tb_clientes SET " . implode(', ', $updateFields) . " WHERE id_cliente = ?");
                    $stmt->execute($updateParams);
                }
            }
        }
    }
    
    if (!$id_cliente) {
        $stmt = $pdo->prepare("INSERT INTO tb_clientes (nombre_cliente, nit_ci_cliente, celular_cliente, email_cliente, fyh_creacion, fyh_actualizacion) 
                              VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([$nombre_cliente, $nit_cliente ?: 'CF', $celular_cliente, $email_cliente]);
        $id_cliente = $pdo->lastInsertId();
    }
    
    // 3. Generar número de venta único
    $nro_venta = mt_rand(1000, 9999);
    
    // 4. Calcular total
    $total_pagado = calcularTotalCarrito($_SESSION['carrito']);
    
    // 5. Registrar venta
    $stmt = $pdo->prepare("INSERT INTO tb_ventas (nro_venta, id_cliente, total_pagado, fyh_creacion, fyh_actualizacion) 
                          VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->execute([$nro_venta, $id_cliente, $total_pagado]);
    $id_venta = $pdo->lastInsertId();
    
    // 6. Registrar items del carrito y actualizar stock
    foreach ($_SESSION['carrito'] as $item) {
        // Registrar en tb_carrito
        $stmt = $pdo->prepare("INSERT INTO tb_carrito (nro_venta, id_producto, cantidad, fyh_creacion, fyh_actualizacion) 
                              VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([$nro_venta, $item['id_producto'], $item['cantidad']]);
        
        // Actualizar stock
        $stmt = $pdo->prepare("UPDATE tb_almacen SET stock = stock - ? WHERE id_producto = ?");
        $stmt->execute([$item['cantidad'], $item['id_producto']]);
    }
    
    $pdo->commit();
    
    // Limpiar carrito
    $_SESSION['carrito'] = [];
    
    echo json_encode([
        'success' => true, 
        'message' => "Venta #$nro_venta procesada exitosamente. Total: $" . number_format($total_pagado, 2),
        'nro_venta' => $nro_venta,
        'total' => $total_pagado
    ]);
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al procesar venta: ' . $e->getMessage()]);
}
?>