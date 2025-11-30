<div class="panel">
    <h3>🛍️ <?php echo t('product_search'); ?></h3>
    <div class="busqueda-container mb-3">
        <input type="text" id="buscarProducto" class="busqueda-input" 
               placeholder="<?php echo t('search_placeholder'); ?>" 
               autocomplete="off" autofocus>
    </div>
    <button onclick="buscarProducto()" class="btn-buscar">
        🔍 <?php echo t('search_products'); ?>
    </button>
    
    <div class="mt-3">
        <small class="text-muted"><?php echo t('search_tips'); ?></small>
    </div>
    
    <div id="resultadosBusqueda" class="lista-productos mt-3">
        <p class="text-muted text-center"><?php echo t('search_results'); ?></p>
    </div>
</div>