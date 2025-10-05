<?php
// Simulación de productos (sin base de datos)
$productos = [
  ["nombre"=>"Manzanas", "descripcion"=>"Manzanas frescas y dulces", "precio"=>"35.00", "stock"=>50, "categoria"=>"Frutas", "imagen"=>"manzanas.jpg"],
  ["nombre"=>"Leche", "descripcion"=>"Leche entera 1L", "precio"=>"22.00", "stock"=>30, "categoria"=>"Lácteos", "imagen"=>"leche.jpg"],
  ["nombre"=>"Pan integral", "descripcion"=>"Pan artesanal integral", "precio"=>"40.00", "stock"=>20, "categoria"=>"Panadería", "imagen"=>"pan.jpg"],
  ["nombre"=>"Yogurt", "descripcion"=>"Yogurt natural 500ml", "precio"=>"18.00", "stock"=>15, "categoria"=>"Lácteos", "imagen"=>"yogurt.jpg"],
  ["nombre"=>"Refresco", "descripcion"=>"Refresco de cola 2L", "precio"=>"28.00", "stock"=>25, "categoria"=>"Bebidas", "imagen"=>"refresco.jpg"],
  ["nombre"=>"Botanas", "descripcion"=>"Variedad de snacks", "precio"=>"22.00", "stock"=>15, "categoria"=>"Snacks", "imagen"=>"snacks.jpg"],
];

foreach ($productos as $row) {
  echo "
  <div class='product-card' data-categoria='{$row['categoria']}'>
    <img src='img/{$row['imagen']}' alt='{$row['nombre']}'>
    <h3>{$row['nombre']}</h3>
    <p>{$row['descripcion']}</p>
    <p><strong>$ {$row['precio']}</strong></p>
    <p>Stock: {$row['stock']}</p>
    <button>Agregar al carrito</button>
  </div>";
}
?>
