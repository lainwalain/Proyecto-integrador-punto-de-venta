<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 14/3/2023
 * Time: 16:31
 */

$sql_clientes = "SELECT * FROM tb_clientes where id_cliente = '$id_cliente' ";
$query_clientes = $pdo->prepare($sql_clientes);
$query_clientes->execute();
$clientes_datos = $query_clientes->fetchAll(PDO::FETCH_ASSOC);

