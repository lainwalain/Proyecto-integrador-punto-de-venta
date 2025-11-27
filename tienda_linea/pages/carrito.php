<?php
include '../includes/header.php';
include '../includes/conexion.php';
?>

<div class="row">
    <div class="col-md-8">
        <h2>Tu Carrito de Compras</h2>
        <div id="carrito-items">
            <!-- Los items del carrito se cargarán con JavaScript -->
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Resumen del Pedido</h5>
                <div id="resumen-pedido"></div>
                <button class="btn btn-success w-100 mt-3" onclick="procesarPago()">Proceder al Pago</button>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/carrito.js"></script>
<?php include '../includes/footer.php'; ?>