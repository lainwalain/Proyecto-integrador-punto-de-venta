<?php


$sql_clientes = "SELECT * FROM tb_clientes where id_cliente = '$id_cliente' ";
$query_clientes = $pdo->prepare($sql_clientes);
$query_clientes->execute();
$clientes_datos = $query_clientes->fetchAll(PDO::FETCH_ASSOC);

