<?php
include('../../config.php');

$email = $_POST['email'];
$password_user = $_POST['password_user'];

$contador = 0;
$sql = "SELECT * FROM tb_usuarios WHERE email = '$email' ";
$query = $pdo->prepare($sql);
$query->execute();
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $usuario){
    $contador = $contador + 1;
    $email_tabla = $usuario['email'];
    $nombres = $usuario['nombres'];
    $password_user_tabla = $usuario['password_user'];
    $id_rol_tabla = $usuario['id_rol']; // Obtener el rol
    $id_usuario_tabla = $usuario['id_usuario'];
}

if( ($contador > 0) && (password_verify($password_user, $password_user_tabla)) ){
    session_start();
    $_SESSION['sesion_email'] = $email;
    $_SESSION['id_usuario'] = $id_usuario_tabla;
    $_SESSION['nombres'] = $nombres;
    $_SESSION['id_rol'] = $id_rol_tabla;
    
    // Redirección por rol
    if ($id_rol_tabla == 1) {
        // Administrador - va al panel admin
        header('Location: '.$URL.'/index.php');
    } else {
        // Usuario normal - va a la tienda online
        header('Location: '.$URL.'/tienda_linea/index.php');
    }
    exit;
    
}else{
    session_start();
    $_SESSION['mensaje'] = "Error datos incorrectos";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/login');
    exit;
}
?>