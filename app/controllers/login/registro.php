<?php
session_start();
include ('../../config.php');

// Obtener datos del formulario
$nombres = $_POST['nombres'];
$email = $_POST['email'];
$password_user = $_POST['password_user'];
$confirm_password = $_POST['confirm_password'];

// Validaciones básicas
if (empty($nombres) || empty($email) || empty($password_user) || empty($confirm_password)) {
    $_SESSION['mensaje'] = "Todos los campos son obligatorios";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/login');
    exit;
}

if ($password_user !== $confirm_password) {
    $_SESSION['mensaje'] = "Las contraseñas no coinciden";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/login');
    exit;
}

if (strlen($password_user) < 6) {
    $_SESSION['mensaje'] = "La contraseña debe tener al menos 6 caracteres";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/login');
    exit;
}

// Verificar si el email ya existe
$sql_verificar = "SELECT * FROM tb_usuarios WHERE email = :email";
$query_verificar = $pdo->prepare($sql_verificar);
$query_verificar->bindParam(':email', $email);
$query_verificar->execute();

if ($query_verificar->rowCount() > 0) {
    $_SESSION['mensaje'] = "Este correo electrónico ya está registrado";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/login');
    exit;
}

// Hash de la contraseña
$password_hash = password_hash($password_user, PASSWORD_DEFAULT);

// ID del rol por defecto (3 = Vendedor/Usuario normal)
$id_rol_default = 3;

// Insertar nuevo usuario
try {
    $sql_insert = "INSERT INTO tb_usuarios (nombres, email, password_user, id_rol, fyh_creacion, fyh_actualizacion) 
                   VALUES (:nombres, :email, :password_user, :id_rol, :fyh_creacion, :fyh_actualizacion)";
    
    $query_insert = $pdo->prepare($sql_insert);
    $query_insert->bindParam(':nombres', $nombres);
    $query_insert->bindParam(':email', $email);
    $query_insert->bindParam(':password_user', $password_hash);
    $query_insert->bindParam(':id_rol', $id_rol_default);
    $query_insert->bindParam(':fyh_creacion', $fechaHora);
    $query_insert->bindParam(':fyh_actualizacion', $fechaHora);
    
    if ($query_insert->execute()) {
        $_SESSION['mensaje'] = "¡Cuenta creada exitosamente! Ya puedes iniciar sesión";
        $_SESSION['icono'] = "success";
        header('Location: '.$URL.'/login');
        exit;
    } else {
        throw new Exception("Error al crear la cuenta");
    }
    
} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/login');
    exit;
}
?>