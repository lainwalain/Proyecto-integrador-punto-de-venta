<?php
function obtenerProductos($pdo) {
    $sql = "SELECT * FROM tb_almacen WHERE stock > 0 ORDER BY nombre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerProductoPorId($pdo, $id) {
    $sql = "SELECT * FROM tb_almacen WHERE id_producto = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function obtenerCategorias($pdo) {
    $sql = "SELECT * FROM tb_categorias ORDER BY nombre_categoria";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>