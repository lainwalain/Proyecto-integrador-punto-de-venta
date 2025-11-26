<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 13/3/2023
 * Time: 10:29
 */

$sql_clientes = "SELECT * FROM tb_clientes";
$query_clientes = $pdo->prepare($sql_clientes);
$query_clientes->execute();
$clientes_datos = $query_clientes->fetchAll(PDO::FETCH_ASSOC);