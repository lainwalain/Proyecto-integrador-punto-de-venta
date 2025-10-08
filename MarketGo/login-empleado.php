<?php
include("db_conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Buscamos solo empleados
    $sql = "SELECT * FROM usuarios WHERE email = ? AND rol = 'empleado'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Validar contraseña (usa password_verify si usas hash)
        if ($password === $usuario['contraseña']) {
            echo "<script>alert('Bienvenido Empleado: " . $usuario['nombre'] . "'); window.location.href='system.html';</script>";
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location.href='login-empleado.html';</script>";
        }
    } else {
        echo "<script>alert('Cuenta inexistente o no autorizada'); window.location.href='login-empleado.html';</script>";
    }
}
?>