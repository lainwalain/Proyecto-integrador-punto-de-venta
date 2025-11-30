<div class="panel">
    <h3>🛒 <?php echo t('shopping_cart'); ?></h3>
    <div id="carritoContainer">
        <!-- El contenido del carrito se cargará dinámicamente aquí -->
        <div id="carritoItems" class="carrito-items">
            <?php
            if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
                foreach($_SESSION['carrito'] as $index => $item) {
                    $subtotal = floatval($item['precio_venta']) * $item['cantidad'];
                    echo "
                    <div class='carrito-item'>
                        <div class='item-info'>
                            <h6>{$item['nombre']}</h6>
                            <div class='d-flex justify-content-between align-items-center'>
                                <span class='item-precio'>" . formatearPrecio($item['precio_venta']) . " " . t('each') . "</span>
                                <div class='controles-cantidad'>
                                    <button class='btn-cantidad' onclick='actualizarCantidad($index, -1)'>-</button>
                                    <span class='mx-2 fw-bold'>{$item['cantidad']}</span>
                                    <button class='btn-cantidad' onclick='actualizarCantidad($index, 1)'>+</button>
                                </div>
                                <span class='fw-bold'>" . formatearPrecio($subtotal) . "</span>
                            </div>
                        </div>
                        <button class='btn-eliminar' onclick='eliminarDelCarrito($index)'>
                            🗑️ " . t('remove') . "
                        </button>
                    </div>";
                }
            } else {
                echo '<p class="text-muted text-center py-4">' . t('empty_cart') . '</p>';
            }
            ?>
        </div>
        
        <div class="total-section">
            <h4><?php echo t('total'); ?>: 
                <?php 
                $total = isset($_SESSION['carrito']) ? calcularTotalCarrito($_SESSION['carrito']) : 0;
                echo formatearPrecio($total);
                ?>
            </h4>
        </div>
        
           <!-- Datos del cliente -->
    <div class="mt-3">
        <h5><?php echo t('customer_data'); ?></h5>
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" id="nombreCliente" class="form-control" placeholder="<?php echo t('customer_name'); ?>" required>
            </div>
            <div class="col-md-6">
               <input type="text" id="nitCliente" class="form-control" placeholder="<?php echo t('customer_nit'); ?> (ej: XAXX010101000)" maxlength="13">
            </div>
            <div class="col-md-6">
                <input type="number" id="celularCliente" class="form-control" placeholder="<?php echo t('customer_phone'); ?>" pattern="[0-9+]*" title="Solo números y el signo +">
            </div>
            <div class="col-md-6">
                <input type="email" id="emailCliente" class="form-control" placeholder="<?php echo t('customer_email'); ?>">
            </div>
        </div>
        <small class="text-muted mt-1 d-block">
        </small>
    </div>
        
        <div class="acciones-venta">
            <button onclick="finalizarVenta()" class="btn-finalizar">
                <?php echo t('finish_sale'); ?>
            </button>
            <button onclick="cancelarVenta()" class="btn-cancelar">
                <?php echo t('cancel_sale'); ?>
            </button>
        </div>
        
        <div class="mt-2">
            <small class="text-muted">
                <strong><?php echo t('shortcuts'); ?>:</strong> <?php echo t('shortcut_search'); ?> | <?php echo t('shortcut_finish'); ?> | <?php echo t('shortcut_cancel'); ?> | <?php echo t('shortcut_clear'); ?>
            </small>
        </div>
    </div>
</div>

<script>
// Cargar el carrito al iniciar
document.addEventListener('DOMContentLoaded', function() {
    
});
</script>