<?php
include 'db_conn.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = $_POST['nombre'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $rol      = $_POST['rol'];

    
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('El correo ya está registrado.'); window.location.href='register.html';</script>";
    } else {
        
        $sql = "INSERT INTO usuarios (nombre, email, contrasena, rol) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $email, $passwordHash, $rol);

        if ($stmt->execute()) {
            echo "<script>alert(' Registro exitoso, ahora puedes iniciar sesión.'); window.location.href='login.html';</script>";
        } else {
            echo "<script>alert(' Error al registrar usuario: " . $stmt->error . "'); window.location.href='register.html';</script>";
        }
    }

    $check->close();
    $conn->close();
}
?>
