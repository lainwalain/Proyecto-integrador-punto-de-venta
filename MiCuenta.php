<?php
/* MiCuenta.php
   Punto de acceso para datos de "Mi Cuenta".
   - GET  -> devuelve JSON con datos del usuario (simulado)
   - POST -> recibe JSON con cambios y devuelve success (simulado)
   --------------------------------------------------------------
   Instrucciones para tu compañero:
   - Reemplazar la sección de datos simulados por la conexión a MySQL,
     ejecutar un SELECT para el usuario actual y devolver los datos en JSON.
   - Para actualizaciones (POST), procesar la entrada y ejecutar un UPDATE.
   - Asegurarse de usar consultas preparadas (mysqli o PDO) para seguridad.
*/

/* Cabeceras para permitir fetch desde el mismo origen */
header('Content-Type: application/json; charset=utf-8');

/* -> Aquí podrías habilitar CORS si pruebas desde otro host:
header("Access-Control-Allow-Origin: http://tu-front.local");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }
*/

/* Simulación de datos de usuario para pruebas.
   Tu compañero cambiará esto por la consulta a DB (MySQL).
*/
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Datos de ejemplo (estructura que el frontend espera)
    $data = [
        "id" => 1,
        "nombre" => "Usuario Demo",
        "correo" => "suridayanaa@gmail.com",
        "telefono" => "",
        "nivel" => "Nivel Silver",
        "puntos" => 150,
        "estadisticas" => [
            "compras" => 0,
            "favoritos" => 0,
            "totalGastado" => 0,
            "puntos" => 150
        ]
    ];

    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/* Manejo de POST para 'guardar' cambios (simulado) */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer cuerpo JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Validación básica (tu compañero debe agregar validaciones más robustas)
    $nombre = isset($input['nombre']) ? trim($input['nombre']) : '';
    $correo = isset($input['correo']) ? trim($input['correo']) : '';
    $telefono = isset($input['telefono']) ? trim($input['telefono']) : '';

    // Aquí iría la conexión y actualización real a la DB. Ejemplo (comentado):
    /*
    // Ejemplo con mysqli
    $mysqli = new mysqli('localhost','usuario','password','marketgo');
    if ($mysqli->connect_errno) {
        http_response_code(500);
        echo json_encode(['success'=>false, 'message'=>'Error de conexión a BD']);
        exit;
    }

    // Supongamos que el id del usuario es 1 (esto debe obtenerse del session/login)
    $userId = 1;
    $stmt = $mysqli->prepare("UPDATE usuarios SET nombre=?, correo=?, telefono=? WHERE id=?");
    $stmt->bind_param('sssi', $nombre, $correo, $telefono, $userId);
    $ok = $stmt->execute();
    $stmt->close();
    $mysqli->close();

    if (!$ok) {
        http_response_code(500);
        echo json_encode(['success'=>false, 'message'=>'No se pudo actualizar en la BD']);
        exit;
    }
    */

    // Como aún no hay DB, devolvemos success simulado
    echo json_encode(['success' => true, 'message' => 'Datos actualizados (simulado)', 'data' => ['nombre'=>$nombre,'correo'=>$correo,'telefono'=>$telefono]], JSON_UNESCAPED_UNICODE);
    exit;
}

/* Método no permitido */
http_response_code(405);
echo json_encode(['success'=>false, 'message'=>'Método no permitido']);
exit;
