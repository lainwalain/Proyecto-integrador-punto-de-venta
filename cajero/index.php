<?php
session_start();
if (!isset($_SESSION['sesion_email']) || $_SESSION['id_rol'] != 3) {
    header('Location: ../../login');
    exit;
}

require_once 'includes/database.php';
require_once 'includes/funciones.php';
require_once 'includes/translations.php';
require_once 'config.php';

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Manejar cambio de idioma
if (isset($_GET['lang']) && in_array($_GET['lang'], ['es', 'en'])) {
    Translation::init($_GET['lang']);
}
?>
<!DOCTYPE html>
<html lang="<?php echo Translation::getCurrentLang(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('app_name'); ?> - <?php echo t('system_name'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Contenedor oculto para textos JavaScript -->
        <script type="application/json" id="textos-js">
    {
        "search_results": "<?php echo t('search_results'); ?>",
        "enter_search_term": "<?php echo t('enter_search_term'); ?>",
        "searching": "<?php echo t('searching'); ?>",
        "search_error": "<?php echo t('search_error'); ?>",
        "no_products_found": "<?php echo t('no_products_found'); ?>",
        "stock": "<?php echo t('stock'); ?>",
        "code": "<?php echo t('code'); ?>",
        "empty_cart": "<?php echo t('empty_cart'); ?>",
        "customer_name_required": "<?php echo t('customer_name_required'); ?>",
        "processing_sale": "<?php echo t('processing_sale'); ?>",
        "sale_cancelled": "<?php echo t('sale_cancelled'); ?>",
        "product_added": "<?php echo t('product_added'); ?>",
        "product_removed": "<?php echo t('product_removed'); ?>",
        "insufficient_stock": "<?php echo t('insufficient_stock'); ?>",
        "product_not_found": "<?php echo t('product_not_found'); ?>",
        "invalid_email": "<?php echo t('invalid_email'); ?>",
        "invalid_rfc": "<?php echo t('invalid_rfc'); ?>",
        "confirm_remove": "<?php echo t('confirm_remove'); ?>",
        "confirm_cancel": "<?php echo t('confirm_cancel'); ?>",
        "confirm_sale": "<?php echo t('confirm_sale'); ?>",
        "each": "<?php echo t('each'); ?>",
        "remove": "<?php echo t('remove'); ?>",
        "customer_phone": "<?php echo t('customer_phone'); ?>",
        "customer_email": "<?php echo t('customer_email'); ?>"
    }
    </script>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Panel de productos -->
            <div class="col-lg-6">
                <?php include 'templates/productos.php'; ?>
            </div>
            
            <!-- Panel del carrito -->
            <div class="col-lg-6">
                <?php include 'templates/carrito.php'; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/theme.js"></script>
    <script src="js/language.js"></script>
</body>
</html>