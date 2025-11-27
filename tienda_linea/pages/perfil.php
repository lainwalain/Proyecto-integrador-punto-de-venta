<?php
session_start();
include '../includes/config.php';
include '../functions/usuario_functions.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login/index.php');
    exit();
}

$usuario_logueado = $_SESSION;
$usuario_info = obtenerUsuarioPorId($pdo, $usuario_logueado['id_usuario']);
$rol_usuario = obtenerRolUsuario($pdo, $usuario_info['id_rol']);
$estadisticas = obtenerEstadisticasUsuario($pdo, $usuario_logueado['id_usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Market Go</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #2ecc71;
            --color-secondary: #27ae60;
            --color-accent: #16a085;
            --color-light: #ecf0f1;
            --color-dark: #2c3e50;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%) !important;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .avatar-lg {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border: 3px solid white;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-left: 4px solid var(--color-primary);
        }
        
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .badge-rol {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">
                <i class="fas fa-store me-2"></i>
                <span>MARKET GO</span>
                <small class="badge bg-light text-dark ms-2">PRO</small>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-home me-1"></i>Inicio
                </a>
                <a class="nav-link" href="carrito.php">
                    <i class="fas fa-shopping-cart me-1"></i>Carrito
                </a>
                <a class="nav-link active" href="perfil.php">
                    <i class="fas fa-user me-1"></i>Mi Perfil
                </a>
                <a class="nav-link" href="#" onclick="cerrarSesion()">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <!-- Header del Perfil -->
        <div class="profile-header text-center">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <div class="avatar-lg rounded-circle d-flex align-items-center justify-content-center mx-auto">
                        <span class="fs-2 fw-bold"><?= strtoupper(substr($usuario_info['nombres'], 0, 1)) ?></span>
                    </div>
                </div>
                <div class="col-md-8 text-start">
                    <h2 class="mb-1"><?= htmlspecialchars($usuario_info['nombres']) ?></h2>
                    <p class="mb-1"><?= htmlspecialchars($usuario_info['email']) ?></p>
                    <span class="badge badge-rol"><?= $rol_usuario ?></span>
                </div>
                <div class="col-md-2 text-end">
                    <small>Miembro desde:<br><?= date('M Y', strtotime($usuario_info['fyh_creacion'])) ?></small>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Estadísticas -->
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-primary mb-0"><?= $estadisticas['total_pedidos'] ?? 0 ?></h3>
                            <p class="text-muted mb-0">Pedidos Realizados</p>
                        </div>
                        <i class="fas fa-shopping-bag fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-success mb-0">$<?= number_format($estadisticas['total_gastado'] ?? 0, 2) ?></h3>
                            <p class="text-muted mb-0">Total Gastado</p>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-info mb-0">
                                <?= $estadisticas['ultima_compra'] ? date('d M Y', strtotime($estadisticas['ultima_compra'])) : 'Sin compras' ?>
                            </h6>
                            <p class="text-muted mb-0">Última Compra</p>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Información Personal -->
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h4 class="mb-4">
                        <i class="fas fa-user-circle me-2"></i>Información Personal
                    </h4>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Nombre Completo</label>
                        <p class="fs-6 mb-0"><?= htmlspecialchars($usuario_info['nombres']) ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Correo Electrónico</label>
                        <p class="fs-6 mb-0"><?= htmlspecialchars($usuario_info['email']) ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Rol en el Sistema</label>
                        <p class="fs-6 mb-0">
                            <span class="badge bg-primary"><?= $rol_usuario ?></span>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Fecha de Registro</label>
                        <p class="fs-6 mb-0"><?= date('d/m/Y H:i', strtotime($usuario_info['fyh_creacion'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h4 class="mb-4">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h4>
                    
                    <div class="d-grid gap-2">
                        <a href="mis_pedidos.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Ver Mis Pedidos
                        </a>
                        
                        <a href="carrito.php" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-shopping-cart me-2"></i>Ir al Carrito
                        </a>
                        
                        <a href="../index.php" class="btn btn-outline-secondary">
                            <i class="fas fa-store me-2"></i>Seguir Comprando
                        </a>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            ¿Necesitas ayuda? Contacta a soporte
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function cerrarSesion() {
        if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
            localStorage.removeItem('carrito');
            window.location.href = '../logout.php';
        }
    }
    </script>
</body>
</html>