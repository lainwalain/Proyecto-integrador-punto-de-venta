<?php
// Definir los IDs de roles según tu base de datos
define('ROL_ADMINISTRADOR', 1);
define('ROL_VENDEDOR', 3);
define('ROL_ALMACEN', 5);
define('ROL_CONTADOR', 4);

// Función para verificar acceso a páginas
function verificarAcceso($rolesPermitidos) {
    if(!isset($_SESSION['id_rol'])) {
        header('Location: ../login');
        exit();
    }
    
    if(is_array($rolesPermitidos)) {
        if(!in_array($_SESSION['id_rol'], $rolesPermitidos)) {
            header('Location: ../acceso-denegado');
            exit();
        }
    } else {
        if($_SESSION['id_rol'] != $rolesPermitidos) {
            header('Location: ../acceso-denegado');
            exit();
        }
    }
}

// Función para verificar acceso en el menú
function tieneAcceso($rolesPermitidos) {
    if(!isset($_SESSION['id_rol'])) {
        return false;
    }
    
    if(is_array($rolesPermitidos)) {
        return in_array($_SESSION['id_rol'], $rolesPermitidos);
    } else {
        return $_SESSION['id_rol'] == $rolesPermitidos;
    }
}

// Función para obtener el nombre del rol actual
function obtenerRolActual() {
    return isset($_SESSION['nombre_rol']) ? $_SESSION['nombre_rol'] : 'Sin rol';
}
?>
