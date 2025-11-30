<?php
session_start();
include 'includes/config.php';
include 'functions/productos_functions.php';

$usuario_logueado = isset($_SESSION['id_usuario']) ? $_SESSION : null;
$productos = obtenerProductos($pdo);
$categorias = obtenerCategorias($pdo);

// Definir la ruta del logo
$logoPath = '../public/images/Logo2MarketGo.png';
$logoExists = file_exists($logoPath);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Go - Sistema de Gestión para Abarrotes</title>
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
            --color-carrito: #e74c3c;
            --color-carrito-hover: #c0392b;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 16px;
            line-height: 1.6;
        }
        
        /* LOGO STYLES - MÁS GRANDE Y VISIBLE */
        .navbar-logo {
            height: 70px; /* Aumentado de 45px */
            width: auto;
            margin-right: 15px;
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
        }
        
        .navbar-logo:hover {
            transform: scale(1.08);
        }
        
        .footer-logo {
            height: 50px; /* Aumentado de 35px */
            width: auto;
            margin-right: 10px;
            filter: brightness(0) invert(1);
        }
        
        .hero-logo {
            height: 140px; /* Aumentado de 80px */
            width: auto;
            margin-bottom: 25px;
            filter: drop-shadow(0 6px 12px rgba(0,0,0,0.4));
        }
        
        .logo-placeholder {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
            margin-right: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        
        /* CARRO DE COMPRAS MEJORADO - MÁS VISIBLE */
        .carrito-fijo {
            position: fixed;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            z-index: 1000;
            background: linear-gradient(135deg, var(--color-carrito), var(--color-carrito-hover));
            color: white;
            border-radius: 50px;
            padding: 20px 25px;
            box-shadow: 0 8px 30px rgba(231, 76, 60, 0.4);
            border: 4px solid white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 700;
            font-size: 1.2em;
            min-width: 80px;
            text-align: center;
        }
        
        .carrito-fijo:hover {
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 12px 40px rgba(231, 76, 60, 0.6);
            background: linear-gradient(135deg, var(--color-carrito-hover), var(--color-carrito));
        }
        
        .contador-carrito-grande {
            background: #f39c12;
            color: white;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1em;
            position: absolute;
            top: -8px;
            right: -8px;
            border: 3px solid white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .icono-carrito-grande {
            font-size: 1.8em;
            margin-bottom: 5px;
        }
        
        .texto-carrito {
            font-size: 0.9em;
            font-weight: 600;
        }
        
        .total-carrito {
            background: #f1c40f;
            color: #2c3e50;
            padding: 4px 8px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 0.9em;
            margin-top: 5px;
        }

        /* BOTÓN FLOTANTE PARA PERSONAS MAYORES */
        .boton-accesibilidad {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 25px rgba(52, 152, 219, 0.4);
            border: 4px solid white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.8em;
        }
        
        .boton-accesibilidad:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 30px rgba(52, 152, 219, 0.6);
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%) !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.15);
            padding: 15px 0; /* Aumentado para acomodar logo más grande */
        }
        
        .navbar-brand {
            font-weight: 800 !important;
            font-size: 1.8rem !important;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
        }
        
        .nav-link {
            font-weight: 600 !important;
            font-size: 1.1rem;
            padding: 12px 20px !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin: 0 2px;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.15) !important;
            transform: translateY(-1px);
        }
        
        /* BOTONES MÁS GRANDES Y VISIBLES */
        .btn-comprar {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            border: none;
            border-radius: 12px;
            font-weight: 700;
            padding: 16px 24px;
            transition: all 0.3s ease;
            font-size: 1.2rem;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }
        
        .btn-comprar:hover {
            background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(46, 204, 113, 0.4);
        }
        
        .btn-comprar:active {
            transform: translateY(-1px);
        }
        
        .icono-btn-grande {
            font-size: 1.4em;
        }
        
        .hero {
            background: linear-gradient(rgba(46, 204, 113, 0.92), rgba(39, 174, 96, 0.88)), 
                        url('https://images.unsplash.com/photo-1604719312566-8912dc04c7a7?ixlib=rb-4.0.3') center/cover;
            color: white;
            border-radius: 0 0 30px 30px;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
            padding: 40px 0;
        }
        
        .producto-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            border: 2px solid #e9ecef;
        }
        
        .producto-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.18);
        }
        
        .producto-imagen {
            height: 220px;
            object-fit: cover;
            border-bottom: 4px solid var(--color-primary);
        }
        
        .precio {
            color: var(--color-secondary);
            font-weight: 800;
            font-size: 1.6em;
        }
        
        .badge-oferta {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 1;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 1em;
        }
        
        .categoria-badge {
            background: var(--color-light);
            color: var(--color-primary);
            border: 2px solid var(--color-primary);
            font-weight: 600;
            font-size: 0.9em;
            padding: 6px 12px;
        }
        
        .store-info {
            background: linear-gradient(135deg, #fff, #f8f9fa);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border: 2px solid #e9ecef;
        }
        
        .feature-icon {
            font-size: 2.8rem;
            color: var(--color-primary);
            margin-bottom: 1rem;
        }
        
        .section-title {
            color: var(--color-secondary);
            border-left: 6px solid var(--color-primary);
            padding-left: 20px;
            margin: 2.5rem 0 1.5rem 0;
            font-weight: 800;
            font-size: 2rem;
        }
        
        .search-box {
            border-radius: 12px;
            border: 3px solid var(--color-primary);
            padding: 16px 24px;
            font-size: 1.1rem;
        }
        
        .filter-select {
            border-radius: 12px;
            border: 3px solid var(--color-primary);
            padding: 16px 24px;
            font-size: 1.1rem;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            border: none;
            border-radius: 12px;
            color: white;
            transition: all 0.3s ease;
            font-weight: 700;
            padding: 14px 24px;
            font-size: 1.1rem;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none;
            border-radius: 12px;
            color: white;
            transition: all 0.3s ease;
            font-weight: 700;
            padding: 14px 24px;
            font-size: 1.1rem;
        }
        
        .btn-logout:hover {
            background: linear-gradient(135deg, #c0392b, #e74c3c);
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }
        
        .market-go-logo {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            color: white !important;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
            letter-spacing: -0.5px;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            margin-right: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .user-dropdown {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            border: none;
            border-radius: 15px;
            min-width: 250px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.2);
        }
        
        .user-dropdown .dropdown-item {
            color: white;
            font-weight: 600;
            padding: 12px 20px;
            transition: all 0.2s ease;
            font-size: 1.05rem;
        }
        
        .user-dropdown .dropdown-item:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateX(8px);
        }
        
        .dropdown-header {
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .hero h1 {
            font-weight: 800;
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        
        .hero .lead {
            font-size: 1.4rem;
            font-weight: 500;
            opacity: 0.95;
        }
        
        .commercial-badge {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 700;
        }
        
        .text-primary {
            color: var(--color-primary) !important;
        }
        
        .bg-primary-light {
            background-color: #f0f9f4;
        }
        
        /* ESTILOS PARA TEXTO MÁS GRANDE (ACCEBILIDAD) */
        .texto-grande .producto-card h5 {
            font-size: 1.4rem;
        }
        
        .texto-grande .producto-card .precio {
            font-size: 1.8rem;
        }
        
        .texto-grande .btn-comprar {
            font-size: 1.3rem;
            padding: 18px 28px;
        }
        
        .texto-grande .nav-link {
            font-size: 1.2rem;
            padding: 14px 22px !important;
        }
        
        /* NOTIFICACIONES MEJORADAS */
        .notificacion-grande {
            min-width: 350px;
            font-size: 1.1rem;
        }
        
        .notificacion-grande .toast-body {
            padding: 20px;
            font-weight: 600;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .carrito-fijo {
                bottom: 20px;
                top: auto;
                right: 20px;
                transform: none;
                padding: 15px 20px;
            }
            
            .carrito-fijo:hover {
                transform: scale(1.1);
            }
            
            .boton-accesibilidad {
                bottom: 100px;
                left: 20px;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .btn-comprar {
                padding: 14px 20px;
                font-size: 1.1rem;
            }
            
            .navbar-logo {
                height: 50px;
            }
            
            .hero-logo {
                height: 100px;
            }
            
            .footer-logo {
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <!-- BOTÓN DE ACCESIBILIDAD PARA TEXTO GRANDE -->
    <div class="boton-accesibilidad" onclick="alternarTextoGrande()" title="Texto más grande para mejor visibilidad">
        <i class="fas fa-text-height"></i>
    </div>

    <!-- CARRO DE COMPRAS FIJO Y VISIBLE -->
    <div class="carrito-fijo" onclick="irAlCarrito()">
        <div class="icono-carrito-grande">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="texto-carrito">Mi Carrito</div>
        <div class="contador-carrito-grande" id="contador-carrito-grande">0</div>
        <div class="total-carrito" id="total-carrito-grande">$0.00</div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <?php if($logoExists): ?>
                    <img src="<?php echo $logoPath; ?>" alt="Market Go" class="navbar-logo">
                <?php else: ?>
                    <div class="logo-placeholder">
                        <i class="fas fa-store"></i>
                    </div>
                <?php endif; ?>
                <span class="market-go-logo">MARKET GO</span>
                <small class="commercial-badge ms-2">PRO</small>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto align-items-center">
                    <a class="nav-link active" href="index.php">
                        <i class="fas fa-home me-2"></i>Inicio
                    </a>
                    <a class="nav-link position-relative" href="pages/carrito.php">
                        <i class="fas fa-shopping-cart me-2"></i>Carrito
                        <span id="contador-carrito" class="badge bg-warning text-dark ms-1" style="display: none;">0</span>
                    </a>
                    
                    <?php if($usuario_logueado): ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <?= strtoupper(substr($usuario_logueado['nombres'], 0, 1)) ?>
                            </div>
                            <span class="d-none d-md-inline fw-semibold">Mi Cuenta</span>
                        </a>
                        <ul class="dropdown-menu user-dropdown">
                            <li class="dropdown-header text-white">
                                <small>Conectado como</small><br>
                                <strong><?= htmlspecialchars($usuario_logueado['nombres']) ?></strong>
                            </li>
                            <li><hr class="dropdown-divider border-light"></li>
                            <li><a class="dropdown-item" href="pages/perfil.php"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                            <li><a class="dropdown-item" href="pages/mis_pedidos.php"><i class="fas fa-shopping-bag me-2"></i>Mis Pedidos</a></li>
                            <li><hr class="dropdown-divider border-light"></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="cerrarSesion()">
                                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(!$usuario_logueado): ?>
                    <a class="nav-link btn-login ms-2" href="login/index.php">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                    <?php else: ?>
                    <button class="nav-link btn-logout ms-2" onclick="cerrarSesion()">
                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero text-white py-5">
        <div class="container text-center">
            <?php if($logoExists): ?>
                <img src="<?php echo $logoPath; ?>" alt="Market Go" class="hero-logo">
            <?php endif; ?>
            <h1 class="display-4 fw-bold mb-3">
                MARKET GO PRO
            </h1>
            <p class="lead fs-4 mb-4">Sistema profesional de gestión para tu abarrotes</p>
            <div class="row mt-5">
                <div class="col-md-3 mb-4">
                    <i class="fas fa-chart-line fs-1 mb-3"></i>
                    <h5>Gestión de Inventario</h5>
                    <p class="small opacity-90">Control total de stock y ventas</p>
                </div>
                <div class="col-md-3 mb-4">
                    <i class="fas fa-peso-sign fs-1 mb-3"></i>
                    <h5>Precios Competitivos</h5>
                    <p class="small opacity-90">Los mejores precios mayoristas</p>
                </div>
                <div class="col-md-3 mb-4">
                    <i class="fas fa-truck-fast fs-1 mb-3"></i>
                    <h5>Envíos Inmediatos</h5>
                    <p class="small opacity-90">Entrega rápida y confiable</p>
                </div>
                <div class="col-md-3 mb-4">
                    <i class="fas fa-headset fs-1 mb-3"></i>
                    <h5>Soporte 24/7</h5>
                    <p class="small opacity-90">Atención personalizada</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="store-info text-center">
            <h3 class="mb-3 text-primary">
                <i class="fas fa-bullhorn me-2"></i>
                ¡Ofertas Comerciales de la Semana!
            </h3>
            <p class="lead">Precios mayoristas especiales para negocios y abarrotes</p>
            <div class="row mt-4">
                <div class="col-md-4">
                    <i class="fas fa-tags feature-icon"></i>
                    <h5 class="text-primary">Precios Mayoristas</h5>
                    <p class="text-muted">Descuentos especiales por volumen</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-boxes feature-icon"></i>
                    <h5 class="text-primary">Stock Garantizado</h5>
                    <p class="text-muted">Disponibilidad inmediata</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-award feature-icon"></i>
                    <h5 class="text-primary">Calidad Comercial</h5>
                    <p class="text-muted">Productos para reventa</p>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                    <input type="text" id="buscador" class="form-control search-box border-start-0" 
                           placeholder="Buscar productos... Ej: arroz, aceite, azúcar, lácteos...">
                </div>
            </div>
            <div class="col-md-4">
                <select id="filtro-categoria" class="form-select filter-select">
                    <option value="">📦 Todas las categorías</option>
                    <?php foreach($categorias as $categoria): ?>
                        <option value="<?= $categoria['id_categoria'] ?>">
                            <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <h2 class="section-title">
            <i class="fas fa-box-open me-2"></i>
            Catálogo de Productos
        </h2>
        
        <?php if(empty($productos)): ?>
            <div class="alert alert-warning text-center py-4">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h4>¡Próximamente más productos!</h4>
                <p>Estamos actualizando nuestro inventario. Vuelve pronto para ver nuestras novedades</p>
            </div>
        <?php else: ?>
            <div class="row" id="lista-productos">
                <?php foreach($productos as $producto): 
                    $es_oferta = rand(0, 1) && $producto['stock'] > 0;
                    
                    $ruta_imagen = '../almacen/img_productos/' . $producto['imagen'];
                    $imagen_existe = file_exists($ruta_imagen);
                    $imagen_final = $imagen_existe ? $ruta_imagen : 'assets/images/placeholder.jpg';
                    
                    $nombre_categoria = 'General';
                    foreach($categorias as $cat) {
                        if($cat['id_categoria'] == $producto['id_categoria']) {
                            $nombre_categoria = $cat['nombre_categoria'];
                            break;
                        }
                    }
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4" 
                         data-categoria="<?= $producto['id_categoria'] ?>" 
                         data-nombre="<?= strtolower($producto['nombre']) ?>">
                        <div class="card producto-card h-100">
                            <?php if($es_oferta): ?>
                                <span class="badge badge-oferta">
                                    <i class="fas fa-bolt me-1"></i>OFERTA
                                </span>
                            <?php endif; ?>
                            
                            <div class="position-relative">
                                <img src="<?= $imagen_final ?>" 
                                     class="card-img-top producto-imagen" 
                                     alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                     loading="lazy"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-dark fw-bold"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                <span class="badge categoria-badge mb-2 align-self-start">
                                    <?= htmlspecialchars($nombre_categoria) ?>
                                </span>
                                
                                <p class="card-text text-muted small flex-grow-1">
                                    <?= htmlspecialchars($producto['descripcion'] ?: 'Producto de calidad para tu negocio') ?>
                                </p>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="text-primary precio mb-0">
                                            $<?= number_format($producto['precio_venta'], 2) ?> MX
                                        </h4>
                                        <?php if($es_oferta): ?>
                                            <small class="text-danger text-decoration-line-through fw-bold">
                                                $<?= number_format($producto['precio_venta'] * 1.15, 2) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p class="card-text mb-3">
                                        <small class="<?= $producto['stock'] > 10 ? 'text-success' : 'text-warning' ?> fw-semibold">
                                            <i class="fas fa-warehouse me-1"></i>
                                            <?= $producto['stock'] > 10 ? 'Stock disponible' : 'Últimas unidades' ?> (<?= $producto['stock'] ?>)
                                        </small>
                                    </p>
                                    
                                    <div class="d-grid">
                                        <button class="btn btn-comprar btn-lg" 
                                                onclick="agregarAlCarrito(
                                                    <?= $producto['id_producto'] ?>, 
                                                    '<?= addslashes($producto['nombre']) ?>', 
                                                    <?= $producto['precio_venta'] ?>,
                                                    '<?= $producto['imagen'] ?>'
                                                )"
                                                <?= $producto['stock'] <= 0 ? 'disabled' : '' ?>>
                                            <?php if($producto['stock'] > 0): ?>
                                                <i class="fas fa-cart-plus icono-btn-grande"></i>
                                                AGREGAR AL CARRITO
                                            <?php else: ?>
                                                <i class="fas fa-times icono-btn-grande"></i>
                                                AGOTADO
                                            <?php endif; ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>
                        <?php if($logoExists): ?>
                            <img src="<?php echo $logoPath; ?>" alt="Market Go" class="footer-logo">
                        <?php else: ?>
                            <i class="fas fa-store me-2"></i>
                        <?php endif; ?>
                        Market Go Pro
                    </h5>
                    <p class="mb-0">Sistema profesional para abarrotes y negocios</p>
                </div>
                <div class="col-md-4">
                    <h6>Horario Comercial</h6>
                    <p class="mb-1">Lunes a Sábado: 6:00 AM - 10:00 PM</p>
                    <p class="mb-0">Domingos: 7:00 AM - 3:00 PM</p>
                </div>
               <div class="col-md-4">
                    <h6>Contacto Comercial</h6>
                    <p class="mb-1">
                        <i class="fas fa-phone me-2"></i>
                        <a href="https://wa.me/5213141665887" 
                        target="_blank" 
                        class="text-decoration-none text-white"
                        style="transition: all 0.3s ease;">
                            3141665887
                        </a>
                    </p>
                    <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Manzanillo, Colima, México</p>
                </div>
            </div>
            <hr class="my-3">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Market Go Pro. Sistema de gestión para comercios.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // FUNCIONES MEJORADAS PARA ACCESIBILIDAD
    function alternarTextoGrande() {
        document.body.classList.toggle('texto-grande');
        mostrarNotificacion('Modo texto grande ' + (document.body.classList.contains('texto-grande') ? 'activado' : 'desactivado'));
    }

    function irAlCarrito() {
        window.location.href = 'pages/carrito.php';
    }

    function agregarAlCarrito(idProducto, nombre, precio, imagen) {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        
        const productoExistente = carrito.find(item => item.id === idProducto);
        
        if (productoExistente) {
            productoExistente.cantidad += 1;
        } else {
            carrito.push({
                id: idProducto,
                nombre: nombre,
                precio: parseFloat(precio),
                imagen: imagen,
                cantidad: 1
            });
        }
        
        localStorage.setItem('carrito', JSON.stringify(carrito));
        actualizarContadorCarrito();
        
        mostrarNotificacion('¡Producto agregado! ' + nombre);
    }

    function mostrarNotificacion(mensaje) {
        const notification = document.createElement('div');
        notification.className = 'position-fixed bottom-0 end-0 p-3';
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            <div class="toast show notificacion-grande" role="alert">
                <div class="toast-header bg-success text-white">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong class="me-auto">¡Éxito!</strong>
                    <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.parentElement.parentElement.remove()"></button>
                </div>
                <div class="toast-body">
                    ${mensaje}
                </div>
            </div>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.parentElement.removeChild(notification);
            }
        }, 4000);
    }

    function actualizarContadorCarrito() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
        const totalPrecio = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        
        // Contador en navbar
        const contador = document.getElementById('contador-carrito');
        if (contador) {
            contador.textContent = totalItems;
            contador.style.display = totalItems > 0 ? 'inline' : 'none';
        }
        
        // Contador en carrito flotante
        const contadorGrande = document.getElementById('contador-carrito-grande');
        const totalGrande = document.getElementById('total-carrito-grande');
        
        if (contadorGrande) {
            contadorGrande.textContent = totalItems;
        }
        
        if (totalGrande) {
            totalGrande.textContent = '$' + totalPrecio.toFixed(2);
        }
    }

    function cerrarSesion() {
        if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
            localStorage.removeItem('carrito');
            window.location.href = 'logout.php';
        }
    }

    function filtrarProductos() {
        const buscarTexto = document.getElementById('buscador').value.toLowerCase();
        const categoriaSeleccionada = document.getElementById('filtro-categoria').value;
        const productos = document.querySelectorAll('#lista-productos .col-lg-3');
        
        productos.forEach(producto => {
            const nombre = producto.getAttribute('data-nombre');
            const categoria = producto.getAttribute('data-categoria');
            
            const coincideNombre = nombre.includes(buscarTexto);
            const coincideCategoria = !categoriaSeleccionada || categoria === categoriaSeleccionada;
            
            producto.style.display = (coincideNombre && coincideCategoria) ? 'block' : 'none';
        });
    }

    // Event listeners
    document.getElementById('buscador').addEventListener('input', filtrarProductos);
    document.getElementById('filtro-categoria').addEventListener('change', filtrarProductos);
    
    // Inicializar
    document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);
    </script>
</body>
</html>