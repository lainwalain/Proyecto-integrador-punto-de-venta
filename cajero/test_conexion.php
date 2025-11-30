<?php
session_start();
require_once 'includes/database.php';

echo "<h1>Test de Conexión</h1>";

try {
    // Test conexión
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_almacen");
    $result = $stmt->fetch();
    echo "<p>Conexión OK. Productos en BD: " . $result['total'] . "</p>";
    
    // Test sesión
    echo "<p>Sesión: " . ($_SESSION['sesion_email'] ?? 'No iniciada') . "</p>";
    echo "<p>Rol: " . ($_SESSION['id_rol'] ?? 'No definido') . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>