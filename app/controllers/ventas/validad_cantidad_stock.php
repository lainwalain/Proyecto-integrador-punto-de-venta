<?php


include ('../../config.php');

$cantidad = $_GET['cantidad'];
$id_producto = $_GET['id_producto'];

$sql = "SELECT stock FROM tb_almacen WHERE id_producto = '$id_producto' ";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$contador = 0;
$stock = 0;
foreach ($datos as $dato){
    $contador++;
    $stock = $dato['stock'];
}

if($cantidad <= $stock){
    //echo "Cantidad válida. Hay suficiente stock disponible.";
} else {
   // echo "Stock insuficiente. Solo hay $stock unidades disponibles.";
    ?>
    <script>
        var cantidad = '<?php echo $stock;?>';
        alert("Stock insuficiente. Solo hay "+cantidad+" unidades disponibles.");
        $('#cantidad').val('');
    </script>
    <?php
}