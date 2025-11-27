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

// Obtener pedidos del usuario
$pedidos = obtenerPedidosUsuario($pdo, $usuario_logueado['id_usuario']);

// Para testing: si no hay pedidos, mostrar mensaje específico
$cliente_existe = obtenerClientePorEmail($pdo, $usuario_info['email']);
$todos_los_pedidos = obtenerTodosLosPedidos($pdo);

$estadisticas = obtenerEstadisticasUsuario($pdo, $usuario_logueado['id_usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Market Go</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #2ecc71;
            --color-secondary: #27ae60;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%) !important;
        }
        
        .pedido-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            border-left: 4px solid var(--color-primary);
            transition: all 0.3s ease;
        }
        
        .pedido-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        
        .estado-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .info-alert {
            border-left: 4px solid #17a2b8;
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
                <a class="nav-link" href="perfil.php">
                    <i class="fas fa-user me-1"></i>Mi Perfil
                </a>
                <a class="nav-link active" href="mis_pedidos.php">
                    <i class="fas fa-shopping-bag me-1"></i>Mis Pedidos
                </a>
                <a class="nav-link" href="#" onclick="cerrarSesion()">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0">
                        <i class="fas fa-shopping-bag me-2"></i>Mis Pedidos
                    </h1>
                    <div class="text-end">
                        <small class="text-muted d-block">
                            <i class="fas fa-user me-1"></i>
                            <?= htmlspecialchars($usuario_info['nombres']) ?>
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-envelope me-1"></i>
                            <?= htmlspecialchars($usuario_info['email']) ?>
                        </small>
                    </div>
                </div>
                
                <!-- Información del estado del cliente -->
                <?php if(!$cliente_existe): ?>
                    <div class="alert alert-info info-alert">
                        <h6><i class="fas fa-info-circle me-2"></i>Información importante</h6>
                        <p class="mb-2">Aún no tienes un perfil de cliente asociado. Se creará automáticamente cuando realices tu primera compra.</p>
                        <small class="text-muted">Email registrado: <strong><?= htmlspecialchars($usuario_info['email']) ?></strong></small>
                    </div>
                <?php endif; ?>
                
                <?php if(empty($pedidos)): ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>No tienes pedidos aún</h3>
                        <p class="text-muted mb-4">
                            <?php if(!$cliente_existe): ?>
                                Realiza tu primera compra para crear tu perfil de cliente y ver tus pedidos aquí.
                            <?php else: ?>
                                Tu perfil de cliente está listo. Realiza tu primera compra para ver tus pedidos.
                            <?php endif; ?>
                        </p>
                        <a href="../index.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-store me-2"></i>Comenzar a Comprar
                        </a>
                        
                        <!-- Información de debugging para administradores -->
                        <?php if($usuario_info['id_rol'] == 1): ?>
                            <div class="mt-4 p-3 bg-light rounded">
                                <h6 class="text-muted">Información para administrador:</h6>
                                <small class="text-muted">
                                    <strong>Cliente existe:</strong> <?= $cliente_existe ? 'Sí' : 'No' ?><br>
                                    <strong>Total de pedidos en sistema:</strong> <?= count($todos_los_pedidos) ?><br>
                                    <strong>Email del usuario:</strong> <?= htmlspecialchars($usuario_info['email']) ?>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <!-- Estadísticas Rápidas -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-1"><?= count($pedidos) ?></h4>
                                    <small>Total Pedidos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-1">$<?= number_format(array_sum(array_column($pedidos, 'total_pagado')), 2) ?></h4>
                                    <small>Total Gastado</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1"><?= array_sum(array_column($pedidos, 'total_productos')) ?></h6>
                                    <small>Productos Comprados</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1"><?= $estadisticas['ultima_compra'] ? date('d/m/Y', strtotime($estadisticas['ultima_compra'])) : 'N/A' ?></h6>
                                    <small>Última Compra</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Pedidos -->
                    <div class="mb-4">
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-history me-2"></i>
                            Historial de Pedidos (<?= count($pedidos) ?>)
                        </h5>
                        
                        <?php foreach($pedidos as $pedido): ?>
                            <div class="pedido-card">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <h6 class="mb-1 text-primary">#<?= $pedido['nro_venta'] ?></h6>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($pedido['fyh_creacion'])) ?>
                                        </small>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Productos (<?= $pedido['total_productos'] ?? 0 ?>):</small>
                                        <p class="mb-0 small text-truncate" title="<?= htmlspecialchars($pedido['productos'] ?? 'No especificado') ?>">
                                            <?= htmlspecialchars($pedido['productos'] ?? 'No especificado') ?>
                                        </p>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <small class="text-muted d-block">Total:</small>
                                        <strong class="text-success fs-5">$<?= number_format($pedido['total_pagado'], 2) ?> MX</strong>
                                    </div>
                                    
                                    <div class="col-md-4 text-end">
                                        <span class="badge bg-success estado-badge mb-2">
                                            <i class="fas fa-check me-1"></i>Completado
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            <?= htmlspecialchars($pedido['nombre_cliente'] ?? 'Cliente') ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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