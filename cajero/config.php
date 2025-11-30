<?php
// Configuración de rutas para el cajero
$URL = 'http://' . $_SERVER['HTTP_HOST'] . '/sisventas';

// Si estás en localhost, asegúrate de que la ruta sea correcta
if ($_SERVER['HTTP_HOST'] == 'localhost') {
    $URL = 'http://localhost/sisventas';
}
?>