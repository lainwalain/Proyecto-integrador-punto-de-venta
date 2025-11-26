<?php
// ===============================
// Conexión a Base de Datos
// ===============================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marketgo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Error de conexión: " . $conn->connect_error
    ]));
}

// ===============================
// Guardar Pedido
// ===============================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener datos JSON desde JS
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['total'], $data['metodo'], $data['productos'])) {
        echo json_encode([
            "success" => false,
            "message" => "Datos incompletos"
        ]);
        exit;
    }

    $total = $data['total'];
    $metodo = $data['metodo'];
    $productos = $data['productos'];

    // Preparar statement para insertar pedido
    $stmt = $conn->prepare("INSERT INTO pedidos (total, metodo_pago, fecha) VALUES (?, ?, NOW())");
    $stmt->bind_param("ds", $total, $metodo);

    if ($stmt->execute()) {
        $pedido_id = $stmt->insert_id;

        // Insertar detalle de productos
        $stmt_detalle = $conn->prepare("INSERT INTO detalle_pedido (id_pedido, nombre_producto, precio, cantidad) VALUES (?, ?, ?, ?)");
        foreach ($productos as $p) {
            $nombre = $p['nombre'];
            $precio = $p['precio'];
            $cantidad = $p['cantidad'];
            $stmt_detalle->bind_param("isdi", $pedido_id, $nombre, $precio, $cantidad);
            $stmt_detalle->execute();
        }

        echo json_encode([
            "success" => true,
            "message" => "Pedido guardado correctamente",
            "pedido_id" => $pedido_id
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al guardar pedido: " . $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
}
?>
