<?php
// fetch_products.php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');

$stmt = $pdo->query("SELECT id, name, description, price, image_url, category FROM products ORDER BY id ASC");
$rows = $stmt->fetchAll();
echo json_encode($rows);
