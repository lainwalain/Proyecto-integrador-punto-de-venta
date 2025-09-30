<?php
session_start();
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Buscar usuario por correo
    $sql = "SELECT id, nombre, contrasena FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Verificar contraseña
        if (password_verify($password, $usuario['contrasena'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];

            // Redirigir al inicio del sistema
            header("Location: index.php");
            exit;
        } else {
            echo "<script>alert('❌ Contraseña incorrecta'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('❌ El correo no está registrado'); window.location.href='login.html';</script>";
    }
}
?>
