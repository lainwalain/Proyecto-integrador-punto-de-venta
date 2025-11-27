<?php
/**
 * Configuración de la Base de Datos - SisVentas
 */

// Configuración de la Base de Datos
define('SERVIDOR','localhost');
define('USUARIO','root');
define('PASSWORD','');
define('BD','sisventas');

// Configuración de la URL
define('URL','http://localhost/SISTEMA_VENTAS/tienda_linea');

// Zona horaria
date_default_timezone_set("America/Caracas");

// Conexión a la base de datos
try{
    $servidor = "mysql:dbname=".BD.";host=".SERVIDOR;
    $pdo = new PDO($servidor, USUARIO, PASSWORD, array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));
}catch (PDOException $e){
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>