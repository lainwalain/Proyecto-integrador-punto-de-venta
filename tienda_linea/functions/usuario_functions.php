<?php
function obtenerUsuarioPorId($pdo, $id_usuario) {
    $sql = "SELECT * FROM tb_usuarios WHERE id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function obtenerRolUsuario($pdo, $id_rol) {
    $sql = "SELECT rol FROM tb_roles WHERE id_rol = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_rol]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['rol'] : 'Usuario';
}

// Obtener el ID del cliente basado en el email del usuario
function obtenerClientePorEmail($pdo, $email) {
    $sql = "SELECT id_cliente FROM tb_clientes WHERE email_cliente = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['id_cliente'] : null;
}

// Obtener pedidos del cliente basado en el email del usuario
function obtenerPedidosUsuario($pdo, $id_usuario) {
    // Primero obtener la información del usuario
    $usuario = obtenerUsuarioPorId($pdo, $id_usuario);
    if (!$usuario) {
        return [];
    }
    
    // Obtener el ID del cliente basado en el email del usuario
    $id_cliente = obtenerClientePorEmail($pdo, $usuario['email']);
    
    if (!$id_cliente) {
        return [];
    }
    
    // Consulta corregida - usando id_cliente
    $sql = "SELECT 
                v.id_venta,
                v.nro_venta,
                v.total_pagado,
                v.fyh_creacion,
                c.nombre_cliente,
                c.email_cliente,
                GROUP_CONCAT(DISTINCT p.nombre SEPARATOR ', ') as productos,
                SUM(car.cantidad) as total_productos
            FROM tb_ventas v
            INNER JOIN tb_clientes c ON v.id_cliente = c.id_cliente
            LEFT JOIN tb_carrito car ON v.nro_venta = car.nro_venta
            LEFT JOIN tb_almacen p ON car.id_producto = p.id_producto
            WHERE v.id_cliente = ?
            GROUP BY v.id_venta, v.nro_venta, v.total_pagado, v.fyh_creacion, c.nombre_cliente, c.email_cliente
            ORDER BY v.fyh_creacion DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_cliente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para crear un cliente automáticamente si no existe
function crearClienteDesdeUsuario($pdo, $usuario_info) {
    $sql = "INSERT INTO tb_clientes (nombre_cliente, nit_ci_cliente, celular_cliente, email_cliente, fyh_creacion, fyh_actualizacion) 
            VALUES (?, ?, ?, ?, NOW(), NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    // Generar un NIT/CI temporal basado en el ID del usuario
    $nit_temporal = 'U' . $usuario_info['id_usuario'] . date('Ymd');
    $celular_temporal = '0000000000';
    
    $stmt->execute([
        $usuario_info['nombres'],
        $nit_temporal,
        $celular_temporal,
        $usuario_info['email']
    ]);
    
    return $pdo->lastInsertId();
}

// Obtener o crear cliente para el usuario
function obtenerOCrearCliente($pdo, $usuario_info) {
    $id_cliente = obtenerClientePorEmail($pdo, $usuario_info['email']);
    
    if (!$id_cliente) {
        $id_cliente = crearClienteDesdeUsuario($pdo, $usuario_info);
    }
    
    return $id_cliente;
}

function obtenerEstadisticasUsuario($pdo, $id_usuario) {
    // Primero obtener la información del usuario
    $usuario = obtenerUsuarioPorId($pdo, $id_usuario);
    if (!$usuario) {
        return [
            'total_pedidos' => 0,
            'total_gastado' => 0,
            'ultima_compra' => null
        ];
    }
    
    // Obtener el ID del cliente
    $id_cliente = obtenerClientePorEmail($pdo, $usuario['email']);
    
    if (!$id_cliente) {
        return [
            'total_pedidos' => 0,
            'total_gastado' => 0,
            'ultima_compra' => null
        ];
    }
    
    $sql = "SELECT 
                COUNT(*) as total_pedidos,
                SUM(total_pagado) as total_gastado,
                MAX(fyh_creacion) as ultima_compra
            FROM tb_ventas 
            WHERE id_cliente = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_cliente]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Si no hay resultados, retornar valores por defecto
    if (!$result || $result['total_pedidos'] === null) {
        return [
            'total_pedidos' => 0,
            'total_gastado' => 0,
            'ultima_compra' => null
        ];
    }
    
    return $result;
}

function obtenerTodosLosPedidos($pdo) {
    $sql = "SELECT 
                v.id_venta,
                v.nro_venta,
                v.total_pagado,
                v.fyh_creacion,
                c.nombre_cliente,
                c.email_cliente,
                GROUP_CONCAT(DISTINCT p.nombre SEPARATOR ', ') as productos,
                SUM(car.cantidad) as total_productos
            FROM tb_ventas v
            LEFT JOIN tb_clientes c ON v.id_cliente = c.id_cliente
            LEFT JOIN tb_carrito car ON v.nro_venta = car.nro_venta
            LEFT JOIN tb_almacen p ON car.id_producto = p.id_producto
            GROUP BY v.id_venta, v.nro_venta, v.total_pagado, v.fyh_creacion, c.nombre_cliente, c.email_cliente
            ORDER BY v.fyh_creacion DESC
            LIMIT 50"; 
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// NUEVAS FUNCIONES PARA PROCESAR PEDIDOS - CORREGIDAS
function procesarPedido($pdo, $id_usuario, $carrito, $total_pagado) {
    try {
        $pdo->beginTransaction();
        
        // Obtener información del usuario
        $usuario = obtenerUsuarioPorId($pdo, $id_usuario);
        if (!$usuario) {
            throw new Exception("Usuario no encontrado");
        }
        
        // Obtener o crear cliente
        $id_cliente = obtenerOCrearCliente($pdo, $usuario);
        
        // Generar número de venta único (usando el mismo formato que tu sistema)
        $nro_venta = rand(1000, 9999); // Número aleatorio como en tu estructura
        
        // Insertar en tb_ventas
        $sql_venta = "INSERT INTO tb_ventas (nro_venta, id_cliente, total_pagado, fyh_creacion, fyh_actualizacion) 
                      VALUES (?, ?, ?, NOW(), NOW())";
        $stmt_venta = $pdo->prepare($sql_venta);
        $stmt_venta->execute([$nro_venta, $id_cliente, $total_pagado]);
        $id_venta = $pdo->lastInsertId();
        
        // Insertar productos en tb_carrito (según tu estructura actual SIN precio_unitario)
        foreach ($carrito as $producto) {
            $sql_carrito = "INSERT INTO tb_carrito (nro_venta, id_producto, cantidad, fyh_creacion, fyh_actualizacion) 
                           VALUES (?, ?, ?, NOW(), NOW())";
            $stmt_carrito = $pdo->prepare($sql_carrito);
            $stmt_carrito->execute([
                $nro_venta,
                $producto['id'],
                $producto['cantidad']
            ]);
            
            // Actualizar stock en tb_almacen
            $sql_stock = "UPDATE tb_almacen SET stock = stock - ? WHERE id_producto = ?";
            $stmt_stock = $pdo->prepare($sql_stock);
            $stmt_stock->execute([$producto['cantidad'], $producto['id']]);
        }
        
        $pdo->commit();
        return ['success' => true, 'nro_venta' => $nro_venta, 'id_venta' => $id_venta];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// Función para obtener el detalle completo de un pedido
function obtenerDetallePedido($pdo, $nro_venta) {
    $sql = "SELECT 
                v.*,
                c.nombre_cliente,
                c.email_cliente,
                c.celular_cliente,
                GROUP_CONCAT(CONCAT(p.nombre, ' (x', car.cantidad, ')') SEPARATOR ', ') as productos_detalle,
                SUM(car.cantidad) as total_productos
            FROM tb_ventas v
            INNER JOIN tb_clientes c ON v.id_cliente = c.id_cliente
            LEFT JOIN tb_carrito car ON v.nro_venta = car.nro_venta
            LEFT JOIN tb_almacen p ON car.id_producto = p.id_producto
            WHERE v.nro_venta = ?
            GROUP BY v.id_venta";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nro_venta]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>