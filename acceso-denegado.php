<?php 
session_start();
include ('layout/parte1.php'); 
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Acceso Denegado</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="alert alert-danger">
                <h4><i class="fas fa-ban"></i> No tienes permisos para acceder a esta sección</h4>
                <p>Tu rol actual: <span class="badge badge-warning"><?php echo isset($_SESSION['nombre_rol']) ? $_SESSION['nombre_rol'] : 'No identificado'; ?></span></p>
                <p>Contacta al administrador si necesitas acceso.</p>
            </div>
            <a href="admin/" class="btn btn-primary">
                <i class="fas fa-arrow-left mr-1"></i> Volver al Dashboard
            </a>
        </div>
    </div>
</div>

<?php include ('layout/parte2.php'); ?>