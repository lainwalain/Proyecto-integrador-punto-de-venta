<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 10/3/2023
 * Time: 17:23
 */
$sql_ventas = "SELECT *, cli.nombre_cliente as nombre_cliente 
               FROM tb_ventas as ve INNER JOIN tb_clientes as cli ON cli.id_cliente = ve.id_cliente";
$query_ventas = $pdo->prepare($sql_ventas);
$query_ventas->execute();
$ventas_datos = $query_ventas->fetchAll(PDO::FETCH_ASSOC);