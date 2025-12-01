<?php
include '../includes/conexion.php';
include '../functions/productos_functions.php';

$id_producto = $_GET['id'] ?? null;
$producto = $id_producto ? obtenerProductoPorId($pdo, $id_producto) : null;

if (!$producto) {
    header('Location: ../index.php');
    exit;
}

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-6">
        <img src="../assets/images/productos/<?= $producto['imagen'] ?>" 
             class="img-fluid" alt="<?= $producto['nombre'] ?>">
    </div>
    <div class="col-md-6">
        <h1><?= $producto['nombre'] ?></h1>
        <p class="text-muted"><?= $producto['descripcion'] ?></p>
        <h3 class="precio">S/ <?= $producto['precio_venta'] ?></h3>
        <p>Stock disponible: <?= $producto['stock'] ?></p>
        
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad:</label>
            <input type="number" id="cantidad" class="form-control" value="1" min="1" max="<?= $producto['stock'] ?>">
        </div>
        
        <button class="btn btn-primary btn-lg" 
                onclick="agregarAlCarrito(<?= $producto['id_producto'] ?>, '<?= $producto['nombre'] ?>', <?= $producto['precio_venta'] ?>)">
            Agregar al Carrito
        </button>
    </div>
</div>

<?php include '../includes/footer.php'; ?>