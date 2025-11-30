<?php
session_start();
include ('../../config.php');

// Obtener datos del formulario
$nombres = $_POST['nombres'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$password_user = $_POST['password_user'];
$confirm_password = $_POST['confirm_password'];

// Validaciones básicas
if (empty($nombres) || empty($email) || empty($telefono) || empty($password_user) || empty($confirm_password)) {
    $_SESSION['mensaje'] = "Todos los campos son obligatorios";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/login');
    exit;
}

// Validar formato de teléfono mexicano (10 dígitos)
if (!preg_match('/^[0-9]{10}$/', $telefono)) {
    $_SESSION['mensaje'] = "El número de teléfono debe tener 10 dígitos";
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

// Validar formato de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['mensaje'] = "El formato del correo electrónico no es válido";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/login');
    exit;
}

// Verificar si el email ya existe en tb_usuarios
$sql_verificar_email = "SELECT * FROM tb_usuarios WHERE email = :email";
$query_verificar_email = $pdo->prepare($sql_verificar_email);
$query_verificar_email->bindParam(':email', $email);
$query_verificar_email->execute();

if ($query_verificar_email->rowCount() > 0) {
    $_SESSION['mensaje'] = "Este correo electrónico ya está registrado";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/login');
    exit;
}

// Verificar si el email ya existe en tb_clientes
$sql_verificar_email_cliente = "SELECT * FROM tb_clientes WHERE email_cliente = :email";
$query_verificar_email_cliente = $pdo->prepare($sql_verificar_email_cliente);
$query_verificar_email_cliente->bindParam(':email', $email);
$query_verificar_email_cliente->execute();

if ($query_verificar_email_cliente->rowCount() > 0) {
    $_SESSION['mensaje'] = "Este correo electrónico ya está registrado como cliente";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/login');
    exit;
}

// Hash de la contraseña
$password_hash = password_hash($password_user, PASSWORD_DEFAULT);

// ID del rol por defecto (6 = USUARIO)
$id_rol_default = 6;

// Fecha y hora actual
$fechaHora = date("Y-m-d H:i:s");

// Generar NIT/CI automático (formato: U + fecha + número aleatorio)
$nit_ci = 'U' . date('ymd') . rand(1000, 9999);

try {
    // Iniciar transacción para asegurar que ambos inserts se completen
    $pdo->beginTransaction();

    // 1. Insertar en tb_usuarios
    $sql_insert_usuario = "INSERT INTO tb_usuarios (nombres, email, password_user, token, id_rol, fyh_creacion, fyh_actualizacion) 
                           VALUES (:nombres, :email, :password_user, '', :id_rol, :fyh_creacion, :fyh_actualizacion)";
    
    $query_insert_usuario = $pdo->prepare($sql_insert_usuario);
    $query_insert_usuario->bindParam(':nombres', $nombres);
    $query_insert_usuario->bindParam(':email', $email);
    $query_insert_usuario->bindParam(':password_user', $password_hash);
    $query_insert_usuario->bindParam(':id_rol', $id_rol_default);
    $query_insert_usuario->bindParam(':fyh_creacion', $fechaHora);
    $query_insert_usuario->bindParam(':fyh_actualizacion', $fechaHora);
    
    if (!$query_insert_usuario->execute()) {
        throw new Exception("Error al crear el usuario");
    }

    // Obtener el ID del usuario recién insertado
    $id_usuario = $pdo->lastInsertId();

    // 2. Insertar en tb_clientes
    $sql_insert_cliente = "INSERT INTO tb_clientes (nombre_cliente, nit_ci_cliente, celular_cliente, email_cliente, fyh_creacion, fyh_actualizacion) 
                           VALUES (:nombre_cliente, :nit_ci_cliente, :celular_cliente, :email_cliente, :fyh_creacion, :fyh_actualizacion)";
    
    $query_insert_cliente = $pdo->prepare($sql_insert_cliente);
    $query_insert_cliente->bindParam(':nombre_cliente', $nombres);
    $query_insert_cliente->bindParam(':nit_ci_cliente', $nit_ci);
    $query_insert_cliente->bindParam(':celular_cliente', $telefono);
    $query_insert_cliente->bindParam(':email_cliente', $email);
    $query_insert_cliente->bindParam(':fyh_creacion', $fechaHora);
    $query_insert_cliente->bindParam(':fyh_actualizacion', $fechaHora);
    
    if (!$query_insert_cliente->execute()) {
        throw new Exception("Error al crear el cliente");
    }

    // Confirmar la transacción
    $pdo->commit();

    $_SESSION['mensaje'] = "¡Cuenta creada exitosamente! Ya puedes iniciar sesión";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'/login');
    exit;
    
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $pdo->rollBack();
    $_SESSION['mensaje'] = "Error al crear la cuenta: " . $e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/login');
    exit;
}
?>