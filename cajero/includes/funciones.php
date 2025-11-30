<?php
function formatearPrecio($precio) {
    return '$' . number_format(floatval($precio), 2);
}

function calcularTotalCarrito($carrito) {
    $total = 0;
    foreach ($carrito as $item) {
        $total += floatval($item['precio_venta']) * $item['cantidad'];
    }
    return $total;
}

function obtenerProductoPorCodigo($codigo, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM tb_almacen WHERE codigo = ? AND stock > 0");
    $stmt->execute([$codigo]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function obtenerProductosBusqueda($termino, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM tb_almacen WHERE (codigo LIKE ? OR nombre LIKE ?) AND stock > 0 LIMIT 10");
    $stmt->execute(["%$termino%", "%$termino%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function mostrarAlerta($mensaje, $tipo = 'success') {
    $clases = [
        'success' => 'alert-success',
        'error' => 'alert-danger', 
        'warning' => 'alert-warning',
        'info' => 'alert-info'
    ];
    
    $clase = $clases[$tipo] ?? $clases['success'];
    
    return "<div class='alert $clase alert-dismissible fade show' role='alert'>
                $mensaje
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}
?>