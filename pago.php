<?php
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('TU_CLAVE_SECRETA_AQUI'); // sk_test_...

$precio = $_POST['total'] ?? 0;

header('Content-Type: application/json');

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'mode' => 'payment',
    'line_items' => [[
        'price_data' => [
            'currency' => 'mxn',
            'product_data' => [
                'name' => 'Pago en MarketGo'
            ],
            'unit_amount' => $precio * 100,
        ],
        'quantity' => 1,
    ]],
    'success_url' => 'http://localhost/tu_ruta/pago_exitoso.php',
    'cancel_url' => 'http://localhost/tu_ruta/pago_cancelado.php',
]);

echo json_encode(['id' => $session->id]);
?>
