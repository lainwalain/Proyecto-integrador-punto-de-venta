<?php
// buscar.php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');

$q = $_GET['busqueda'] ?? '';
$q = trim($q);
if ($q === '') {
    echo json_encode([]);
    exit;
}

// búsqueda simple con LIKE (usa prepared)
$stmt = $pdo->prepare("SELECT id, name AS nombre, price FROM products WHERE name LIKE ? LIMIT 20");
$term = "%$q%";
$stmt->execute([$term]);
$rows = $stmt->fetchAll();
echo json_encode($rows);
