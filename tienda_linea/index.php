<?php
// Incluir configuración y funciones
include 'includes/config.php';
include 'functions/productos_functions.php';

// Obtener productos y categorías
$productos = obtenerProductos($pdo);
$categorias = obtenerCategorias($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Go - Tu Tienda de Abarrotes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --color-primary: #e67e22;
            --color-secondary: #f39c12;
            --color-accent: #e74c3c;
            --color-light: #fef9f3;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--color-primary) 0%, #d35400 100%) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .hero {
            background: linear-gradient(rgba(230, 126, 34, 0.9), rgba(243, 156, 18, 0.8)), 
                        url('https://images.unsplash.com/photo-1604719312566-8912dc04c7a7?ixlib=rb-4.0.3') center/cover;
            color: white;
            border-radius: 0 0 30px 30px;
            margin-bottom: 2rem;
        }
        
        .producto-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .producto-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .producto-imagen {
            height: 180px;
            object-fit: cover;
            border-bottom: 3px solid var(--color-secondary);
        }
        
        .precio {
            color: var(--color-accent);
            font-weight: bold;
            font-size: 1.3em;
        }
        
        .badge-oferta {
            background: var(--color-accent);
            color: white;
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
        }
        
        .btn-comprar {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            border: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-comprar:hover {
            background: linear-gradient(135deg, #d35400, var(--color-primary));
            transform: scale(1.05);
        }
        
        .categoria-badge {
            background: var(--color-light);
            color: var(--color-primary);
            border: 1px solid var(--color-primary);
        }
        
        .store-info {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .feature-icon {
            font-size: 2rem;
            color: var(--color-primary);
            margin-bottom: 1rem;
        }
        
        .section-title {
            color: var(--color-primary);
            border-left: 5px solid var(--color-secondary);
            padding-left: 15px;
            margin: 2rem 0 1rem 0;
        }
        
        .search-box {
            border-radius: 25px;
            border: 2px solid var(--color-primary);
            padding: 10px 20px;
        }
        
        .filter-select {
            border-radius: 25px;
            border: 2px solid var(--color-primary);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border: none;
            border-radius: 20px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #229954, #27ae60);
            transform: scale(1.05);
            color: white;
        }
        
        .market-go-logo {
            font-family: 'Arial', sans-serif;
            font-weight: 800;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <!-- Navbar estilo Market Go -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-shopping-basket me-2"></i>
                <span class="market-go-logo">MARKET GO</span>
            </a>
            
            <!-- Botón para mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-home me-1"></i>Inicio
                    </a>
                    <a class="nav-link position-relative" href="pages/carrito.php">
                        <i class="fas fa-shopping-cart me-1"></i>Carrito
                        <span id="contador-carrito" class="badge bg-warning text-dark ms-1" style="display: none;">0</span>
                    </a>
                    
                    <!-- Separador visual -->
                    <div class="nav-item dropdown-divider d-none d-lg-block mx-2 my-1 border-light"></div>
                    
                    <!-- Botón para regresar al login -->
                    <a class="nav-link btn-login ms-2" href="../login/index.php">
                        <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section mejorado -->
    <section class="hero text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">
                <i class="fas fa-store me-3"></i>
                Bienvenido a Market Go
            </h1>
            <p class="lead fs-4 mb-4">Los mejores precios y la mejor calidad para tu hogar</p>
            <div class="row mt-4">
                <div class="col-md-3">
                    <i class="fas fa-truck fs-1 mb-2"></i>
                    <h5>Envíos Rápidos</h5>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-peso-sign fs-1 mb-2"></i>
                    <h5>Precios en MX</h5>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-award fs-1 mb-2"></i>
                    <h5>Calidad Garantizada</h5>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-clock fs-1 mb-2"></i>
                    <h5>Horario Extendido</h5>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Información de la tienda -->
        <div class="store-info text-center">
            <h3 class="mb-3"><i class="fas fa-bolt me-2"></i>¡Ofertas de la Semana!</h3>
            <p class="lead">Descuentos especiales en productos seleccionados. ¡Aprovecha ahora!</p>
            <div class="row mt-4">
                <div class="col-md-4">
                    <i class="fas fa-tags feature-icon"></i>
                    <h5>Precios Bajos</h5>
                    <p class="text-muted">Siempre los mejores precios del mercado</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-fresh feature-icon">🥬</i>
                    <h5>Productos Frescos</h5>
                    <p class="text-muted">Calidad garantizada en todos nuestros productos</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-smile feature-icon"></i>
                    <h5>Atención Personalizada</h5>
                    <p class="text-muted">Te atendemos con amabilidad y rapidez</p>
                </div>
            </div>
        </div>

        <!-- Filtros mejorados -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-warning"></i>
                    </span>
                    <input type="text" id="buscador" class="form-control search-box border-start-0" 
                           placeholder="¿Qué estás buscando hoy? Ej: arroz, aceite, azúcar...">
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

        <!-- Productos -->
        <h2 class="section-title">
            <i class="fas fa-boxes me-2"></i>
            Nuestros Productos
        </h2>
        
        <?php if(empty($productos)): ?>
            <div class="alert alert-warning text-center py-4">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h4>¡Próximamente más productos!</h4>
                <p>Estamos surtiendo nuestra tienda. Vuelve pronto para ver nuestras novedades</p>
            </div>
        <?php else: ?>
            <div class="row" id="lista-productos">
                <?php foreach($productos as $producto): 
                    // Determinar si es oferta (ejemplo aleatorio para demostración)
                    $es_oferta = rand(0, 1) && $producto['stock'] > 0;
                    
                    // RUTA CORREGIDA DE IMÁGENES
                    $ruta_imagen = '../almacen/img_productos/' . $producto['imagen'];
                    $imagen_existe = file_exists($ruta_imagen);
                    $imagen_final = $imagen_existe ? $ruta_imagen : 'assets/images/placeholder.jpg';
                    
                    // Obtener nombre de categoría
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
                            <!-- Badge de oferta -->
                            <?php if($es_oferta): ?>
                                <span class="badge badge-oferta">
                                    <i class="fas fa-bolt me-1"></i>OFERTA
                                </span>
                            <?php endif; ?>
                            
                            <!-- IMAGEN CON RUTA CORREGIDA -->
                            <div class="position-relative">
                                <img src="<?= $imagen_final ?>" 
                                     class="card-img-top producto-imagen" 
                                     alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                     loading="lazy"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <!-- Nombre y categoría -->
                                <h5 class="card-title text-dark"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                <span class="badge categoria-badge mb-2 align-self-start">
                                    <?= htmlspecialchars($nombre_categoria) ?>
                                </span>
                                
                                <!-- Descripción -->
                                <p class="card-text text-muted small flex-grow-1">
                                    <?= htmlspecialchars($producto['descripcion'] ?: 'Producto de calidad para tu hogar') ?>
                                </p>
                                
                                <!-- Precio y acciones -->
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="text-success precio mb-0">
                                            $<?= number_format($producto['precio_venta'], 2) ?> MX
                                        </h4>
                                        <?php if($es_oferta): ?>
                                            <small class="text-danger text-decoration-line-through">
                                                $<?= number_format($producto['precio_venta'] * 1.2, 2) ?> MX
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Stock -->
                                    <p class="card-text mb-3">
                                        <small class="<?= $producto['stock'] > 10 ? 'text-success' : 'text-warning' ?>">
                                            <i class="fas fa-boxes me-1"></i>
                                            <?= $producto['stock'] > 10 ? 'Disponible' : 'Últimas unidades' ?> (<?= $producto['stock'] ?>)
                                        </small>
                                    </p>
                                    
                                    <!-- Botón de compra -->
                                    <div class="d-grid">
                                        <button class="btn btn-comprar btn-lg" 
                                                onclick="agregarAlCarrito(
                                                    <?= $producto['id_producto'] ?>, 
                                                    '<?= addslashes($producto['nombre']) ?>', 
                                                    <?= $producto['precio_venta'] ?>
                                                )"
                                                <?= $producto['stock'] <= 0 ? 'disabled' : '' ?>>
                                            <?php if($producto['stock'] > 0): ?>
                                                <i class="fas fa-cart-plus me-2"></i>
                                                Agregar al Carrito
                                            <?php else: ?>
                                                <i class="fas fa-times me-2"></i>
                                                Agotado
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

    <!-- Footer mejorado -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-shopping-basket me-2"></i>Market Go</h5>
                    <p class="mb-0">Tu tienda de confianza para el día a día</p>
                </div>
                <div class="col-md-4">
                    <h6>Horario de Atención</h6>
                    <p class="mb-1">Lunes a Sábado: 7:00 AM - 9:00 PM</p>
                    <p class="mb-0">Domingos: 8:00 AM - 2:00 PM</p>
                </div>
                <div class="col-md-4">
                    <h6>Contacto</h6>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i>3141665887</p>
                    <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>CDMX, México</p>
                </div>
            </div>
            <hr class="my-3">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Market Go. Tu tienda de abarrotes en México.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Funciones del Carrito
    function agregarAlCarrito(idProducto, nombre, precio) {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        
        const productoExistente = carrito.find(item => item.id === idProducto);
        
        if (productoExistente) {
            productoExistente.cantidad += 1;
        } else {
            carrito.push({
                id: idProducto,
                nombre: nombre,
                precio: parseFloat(precio),
                cantidad: 1
            });
        }
        
        localStorage.setItem('carrito', JSON.stringify(carrito));
        actualizarContadorCarrito();
        
        // Notificación más elegante
        mostrarNotificacion('¡Agregado! ' + nombre);
    }

    function mostrarNotificacion(mensaje) {
        const notification = document.createElement('div');
        notification.className = 'position-fixed bottom-0 end-0 p-3';
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header bg-success text-white">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong class="me-auto">Producto Agregado</strong>
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
        }, 3000);
    }

    function actualizarContadorCarrito() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
        
        const contador = document.getElementById('contador-carrito');
        if (contador) {
            contador.textContent = totalItems;
            contador.style.display = totalItems > 0 ? 'inline' : 'none';
        }
    }

    // Filtros
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

    // Event Listeners
    document.getElementById('buscador').addEventListener('input', filtrarProductos);
    document.getElementById('filtro-categoria').addEventListener('change', filtrarProductos);
    
    // Inicializar contador al cargar
    document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);
    </script>
</body>
</html>