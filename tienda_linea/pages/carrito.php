<?php
session_start();
include '../includes/config.php';
include '../functions/productos_functions.php';
include '../functions/usuario_functions.php';

// Verificar si el usuario está logueado
$usuario_logueado = isset($_SESSION['id_usuario']) ? $_SESSION : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Market Go</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2ecc71;
            --primary-dark: #27ae60;
            --secondary-color: #16a085;
            --accent-color: #e74c3c;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }
        
        .btn-success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border: none;
            border-radius: 25px;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none;
            border-radius: 25px;
        }
        
        .carrito-vacio {
            text-align: center;
            padding: 60px 20px;
        }
        
        .carrito-vacio i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .producto-imagen {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .cantidad-input {
            width: 70px;
            text-align: center;
        }
        
        .resumen-pedido {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px;
        }
        
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
    </style>
</head>
<body>
    <!-- Contenedor para alertas -->
    <div id="alert-container" class="alert-container"></div>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">
                <i class="fas fa-shopping-basket me-2"></i>
                MARKET GO
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-home me-1"></i>Inicio
                </a>
                <a class="nav-link position-relative" href="carrito.php">
                    <i class="fas fa-shopping-cart me-1"></i>Carrito
                    <span id="contador-carrito" class="badge bg-warning text-dark ms-1" style="display: none;">0</span>
                </a>
                <?php if($usuario_logueado): ?>
                    <a class="nav-link" href="perfil.php">
                        <i class="fas fa-user me-1"></i>Mi Perfil
                    </a>
                    <a class="nav-link" href="#" onclick="cerrarSesion()">
                        <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                    </a>
                <?php else: ?>
                    <a class="nav-link" href="../login/index.php">
                        <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                    </a>
                    <!-- Contenedor de botones -->
<div style="position: fixed; top: 15px; right: 20px; z-index: 9999; display:flex; gap:10px;">
  <button id="btnLanguage" class="btn btn-sm">
    <i class="fas fa-globe me-1"></i>English
  </button>
  <button id="btnDarkMode" class="btn btn-sm">
    <i class="fas fa-moon"></i>Dark
  </button>
</div>

                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Tu Carrito de Compras</h1>
                <?php if(!$usuario_logueado): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Debes <a href="../login/index.php" class="alert-link">iniciar sesión</a> para poder procesar tu pedido.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div id="carrito-items">
                        </div>
                        
                        <div id="carrito-vacio" class="carrito-vacio" style="display: none;">
                            <i class="fas fa-shopping-cart"></i>
                            <h3>Tu carrito está vacío</h3>
                            <p class="text-muted">Agrega algunos productos increíbles de nuestra tienda</p>
                            <a href="../index.php" class="btn btn-primary btn-lg mt-3">
                                <i class="fas fa-store me-2"></i>Seguir Comprando
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card resumen-pedido">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-receipt me-2"></i>Resumen del Pedido
                        </h5>
                        
                        <div id="resumen-pedido">
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button id="btn-procesar-pago" class="btn btn-success btn-lg" <?php echo !$usuario_logueado ? 'disabled' : ''; ?>>
                                <i class="fas fa-credit-card me-2"></i>
                                <?php echo $usuario_logueado ? 'Proceder al Pago' : 'Inicia Sesión para Pagar'; ?>
                            </button>
                            <a href="../index.php" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i>Seguir Comprando
                            </a>
                            <?php if($usuario_logueado): ?>
                                <button id="btn-vaciar-carrito" class="btn btn-outline-warning mt-2" style="display: none;">
                                    <i class="fas fa-trash me-2"></i>Vaciar Carrito
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-body">
                        <h6><i class="fas fa-shipping-fast me-2"></i>Información de Envío</h6>
                        <small class="text-muted">
                            • Envío gratis en compras mayores a $500 MX<br>
                            • Tiempo de entrega: 1 a 2 horas<br>
                            • Zona de cobertura: Manzanillo, Colima y áreas cercanas
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            cargarCarrito();
            actualizarContadorCarrito();
            
            // Event listener para el botón de procesar pago
            document.getElementById('btn-procesar-pago').addEventListener('click', procesarPago);
            
            // Event listener para el botón de vaciar carrito
            document.getElementById('btn-vaciar-carrito').addEventListener('click', vaciarCarrito);
        });

        function cargarCarrito() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const carritoItems = document.getElementById('carrito-items');
            const carritoVacio = document.getElementById('carrito-vacio');
            const resumenPedido = document.getElementById('resumen-pedido');
            const btnProcesarPago = document.getElementById('btn-procesar-pago');
            const btnVaciarCarrito = document.getElementById('btn-vaciar-carrito');

            if (carrito.length === 0) {
                carritoItems.style.display = 'none';
                carritoVacio.style.display = 'block';
                resumenPedido.innerHTML = '<p class="text-center">No hay productos en el carrito</p>';
                btnProcesarPago.disabled = true;
                if (btnVaciarCarrito) btnVaciarCarrito.style.display = 'none';
                return;
            }

            carritoItems.style.display = 'block';
            carritoVacio.style.display = 'none';
            if (btnVaciarCarrito) btnVaciarCarrito.style.display = 'block';
            
            carritoItems.innerHTML = '';
            let subtotal = 0;

            carrito.forEach((producto, index) => {
                const totalProducto = producto.precio * producto.cantidad;
                subtotal += totalProducto;

                const productoHTML = `
                    <div class="row align-items-center mb-4 pb-3 border-bottom">
                        <div class="col-md-2">
                            <img src="../../almacen/img_productos/${producto.imagen}" 
                                 class="producto-imagen" 
                                 alt="${producto.nombre}"
                                 onerror="this.src='../../almacen/img_productos/default.jpg'">
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1">${producto.nombre}</h6>
                            <p class="text-muted mb-0 small">SKU: ${producto.id}</p>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-0 fw-bold text-success">$${producto.precio.toFixed(2)} MX</p>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group input-group-sm">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="cambiarCantidad(${index}, -1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" 
                                       class="form-control cantidad-input" 
                                       value="${producto.cantidad}" 
                                       min="1" 
                                       onchange="actualizarCantidad(${index}, this.value)">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="cambiarCantidad(${index}, 1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-0 fw-bold">$${totalProducto.toFixed(2)} MX</p>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-danger btn-sm" 
                                    onclick="eliminarDelCarrito(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                carritoItems.innerHTML += productoHTML;
            });

            const envio = subtotal > 500 ? 0 : 50;
            const total = subtotal + envio;

            resumenPedido.innerHTML = `
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>$${subtotal.toFixed(2)} MX</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Envío:</span>
                    <span>${envio === 0 ? 'GRATIS' : `$${envio.toFixed(2)} MX`}</span>
                </div>
                ${envio === 0 ? '<div class="alert alert-success py-1 mb-2"><small>¡Envío gratis aplicado!</small></div>' : ''}
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong>$${total.toFixed(2)} MX</strong>
                </div>
            `;

            // Solo habilitar el botón si el usuario está logueado y hay productos
            btnProcesarPago.disabled = !carrito.length;
        }

        function cambiarCantidad(index, cambio) {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const nuevaCantidad = carrito[index].cantidad + cambio;
            
            if (nuevaCantidad < 1) {
                eliminarDelCarrito(index);
                return;
            }
            
            carrito[index].cantidad = nuevaCantidad;
            localStorage.setItem('carrito', JSON.stringify(carrito));
            cargarCarrito();
            actualizarContadorCarrito();
        }

        function actualizarCantidad(index, nuevaCantidad) {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            nuevaCantidad = parseInt(nuevaCantidad);
            
            if (nuevaCantidad < 1) {
                eliminarDelCarrito(index);
                return;
            }
            
            carrito[index].cantidad = nuevaCantidad;
            localStorage.setItem('carrito', JSON.stringify(carrito));
            cargarCarrito();
            actualizarContadorCarrito();
        }

        function eliminarDelCarrito(index) {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const productoEliminado = carrito[index].nombre;
            
            carrito.splice(index, 1);
            localStorage.setItem('carrito', JSON.stringify(carrito));
            
            mostrarNotificacion(`Producto eliminado: ${productoEliminado}`, 'warning');
            
            cargarCarrito();
            actualizarContadorCarrito();
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

        function mostrarNotificacion(mensaje, tipo = 'info') {
            const alertContainer = document.getElementById('alert-container');
            const alertId = 'alert-' + Date.now();
            
            const alertHTML = `
                <div id="${alertId}" class="alert alert-${tipo === 'error' ? 'danger' : tipo} alert-dismissible fade show" role="alert">
                    ${mensaje}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            alertContainer.innerHTML += alertHTML;
            
            // Auto-eliminar después de 5 segundos
            setTimeout(() => {
                const alertElement = document.getElementById(alertId);
                if (alertElement) {
                    alertElement.remove();
                }
            }, 5000);
        }

        function procesarPago() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            
            if (carrito.length === 0) {
                mostrarNotificacion('Tu carrito está vacío', 'warning');
                return;
            }
            
            const btn = document.getElementById('btn-procesar-pago');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
            btn.disabled = true;
            
            // Calcular totales
            const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
            const envio = subtotal > 500 ? 0 : 50;
            const total = subtotal + envio;
            
            // Enviar datos al servidor
            fetch('../functions/procesar_pago.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    carrito: carrito,
                    total: total,
                    subtotal: subtotal,
                    envio: envio
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion(`¡Pedido procesado exitosamente! Número de pedido: ${data.nro_venta}`, 'success');
                    
                    // Limpiar carrito
                    localStorage.removeItem('carrito');
                    cargarCarrito();
                    actualizarContadorCarrito();
                    
                    // Redirigir a mis pedidos después de 3 segundos
                    setTimeout(() => {
                        window.location.href = 'mis_pedidos.php';
                    }, 3000);
                    
                } else {
                    mostrarNotificacion('Error al procesar el pedido: ' + data.error, 'error');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión con el servidor', 'error');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        function vaciarCarrito() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            if (carrito.length === 0) return;
            
            if (confirm('¿Estás seguro de que quieres vaciar todo el carrito?')) {
                localStorage.removeItem('carrito');
                cargarCarrito();
                actualizarContadorCarrito();
                mostrarNotificacion('Carrito vaciado correctamente', 'info');
            }
        }

        function cerrarSesion() {
            if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                localStorage.removeItem('carrito');
                window.location.href = '../logout.php';
            }
        }
    </script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
  const translations = {
    es: {
      inicio: "Inicio",
      carrito: "Carrito",
      perfil: "Mi Perfil",
      cerrarSesion: "Cerrar Sesión",
      iniciarSesion: "Iniciar Sesión",
      tituloCarrito: "Tu Carrito de Compras",
      alertaLogin: "Debes iniciar sesión para poder procesar tu pedido.",
      carritoVacio: "Tu carrito está vacío",
      carritoVacioDesc: "Agrega algunos productos increíbles de nuestra tienda",
      seguirComprando: "Seguir Comprando",
      resumen: "Resumen del Pedido",
      procesarPago: "Proceder al Pago",
      loginParaPagar: "Inicia Sesión para Pagar",
      vaciarCarrito: "Vaciar Carrito",
      envioInfo: "Información de Envío",
      envioGratis: "• Envío gratis en compras mayores a $500 MX",
      tiempoEntrega: "• Tiempo de entrega: 1 a 2 horas",
      zonaCobertura: "• Zona de cobertura: Manzanillo, Colima y áreas cercanas"
    },
    en: {
      inicio: "Home",
      carrito: "Cart",
      perfil: "My Profile",
      cerrarSesion: "Log Out",
      iniciarSesion: "Log In",
      tituloCarrito: "Your Shopping Cart",
      alertaLogin: "You must log in to process your order.",
      carritoVacio: "Your cart is empty",
      carritoVacioDesc: "Add some amazing products from our store",
      seguirComprando: "Continue Shopping",
      resumen: "Order Summary",
      procesarPago: "Proceed to Payment",
      loginParaPagar: "Log In to Pay",
      vaciarCarrito: "Empty Cart",
      envioInfo: "Shipping Information",
      envioGratis: "• Free shipping on orders over $500 MX",
      tiempoEntrega: "• Delivery time: 1 to 2 hours",
      zonaCobertura: "• Coverage area: Manzanillo, Colima and nearby areas"
    }
  };

  let currentLang = localStorage.getItem("lang") || "es";
  const btnLanguage = document.getElementById("btnLanguage");

  function applyLanguage(lang) {
    const t = translations[lang];

    // Navbar
    document.querySelector(".navbar-nav a[href='../index.php']").innerHTML = `<i class="fas fa-home me-1"></i>${t.inicio}`;
    document.querySelector(".navbar-nav a[href='carrito.php']").innerHTML = `<i class="fas fa-shopping-cart me-1"></i>${t.carrito}`;
    const perfilLink = document.querySelector(".navbar-nav a[href='perfil.php']");
    if (perfilLink) perfilLink.innerHTML = `<i class="fas fa-user me-1"></i>${t.perfil}`;
    const cerrarLink = document.querySelector(".navbar-nav a[onclick='cerrarSesion()']");
    if (cerrarLink) cerrarLink.innerHTML = `<i class="fas fa-sign-out-alt me-1"></i>${t.cerrarSesion}`;
    const loginLink = document.querySelector(".navbar-nav a[href='../login/index.php']");
    if (loginLink) loginLink.innerHTML = `<i class="fas fa-sign-in-alt me-1"></i>${t.iniciarSesion}`;

    // Título principal
    const titulo = document.querySelector("h1.mb-4");
    if (titulo) titulo.innerHTML = `<i class="fas fa-shopping-cart me-2"></i>${t.tituloCarrito}`;

    // Alerta login
    const alerta = document.querySelector(".alert.alert-warning");
    if (alerta) alerta.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${t.alertaLogin}`;

    // Carrito vacío
    const vacio = document.getElementById("carrito-vacio");
    if (vacio) {
      vacio.querySelector("h3").textContent = t.carritoVacio;
      vacio.querySelector("p").textContent = t.carritoVacioDesc;
      const btnSeguir = vacio.querySelector("a.btn-primary");
      if (btnSeguir) btnSeguir.innerHTML = `<i class="fas fa-store me-2"></i>${t.seguirComprando}`;
    }

    // Resumen del pedido
    const resumenTitle = document.querySelector(".resumen-pedido h5");
    if (resumenTitle) resumenTitle.innerHTML = `<i class="fas fa-receipt me-2"></i>${t.resumen}`;

    const btnPago = document.getElementById("btn-procesar-pago");
    if (btnPago) {
      btnPago.innerHTML = `<i class="fas fa-credit-card me-2"></i>${btnPago.disabled ? t.loginParaPagar : t.procesarPago}`;
    }

    const btnVaciar = document.getElementById("btn-vaciar-carrito");
    if (btnVaciar) btnVaciar.innerHTML = `<i class="fas fa-trash me-2"></i>${t.vaciarCarrito}`;

    // Botón "Seguir Comprando" en resumen
    const btnSeguirResumen = document.querySelector(".resumen-pedido a.btn-outline-light");
    if (btnSeguirResumen) btnSeguirResumen.innerHTML = `<i class="fas fa-arrow-left me-2"></i>${t.seguirComprando}`;

    // Información de envío
    const envioCard = document.querySelector(".card.mt-3 h6");
    if (envioCard) envioCard.innerHTML = `<i class="fas fa-shipping-fast me-2"></i>${t.envioInfo}`;
    const envioSmall = document.querySelector(".card.mt-3 small");
    if (envioSmall) envioSmall.innerHTML = `${t.envioGratis}<br>${t.tiempoEntrega}<br>${t.zonaCobertura}`;

    // Botón idioma
    btnLanguage.innerHTML = lang === "es" ? `<i class="fas fa-globe me-1"></i>English` : `<i class="fas fa-globe me-1"></i>Español`;
  }

  applyLanguage(currentLang);

  btnLanguage.addEventListener("click", () => {
    currentLang = currentLang === "es" ? "en" : "es";
    localStorage.setItem("lang", currentLang);
    applyLanguage(currentLang);
  });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const DARK_STYLE_ID = "dark-mode-overrides";
  const btn = document.getElementById("btnDarkMode");

  const darkCSS = `
    /* Fondo y texto base */
    body { background-color:#0b1220 !important; color:#f9fafb !important; }
    h1,h2,h3,h4,h5,h6,strong { color:#f9fafb !important; }
    .text-muted { color:#d1d5db !important; }

    /* Navbar */
    .navbar { background:linear-gradient(135deg,#0f172a,#111827)!important; }
    .navbar-brand { color:#f9fafb !important; }
    .nav-link { color:#e5e7eb !important; }
    .nav-link:hover { color:#ffffff !important; background:rgba(255,255,255,0.08)!important; border-radius:8px; }

    /* Tarjetas */
    .card { background:#111827!important; color:#f9fafb!important; box-shadow:0 8px 30px rgba(0,0,0,0.6)!important; }
    .card-title, .card-body, .card-text { color:#f9fafb!important; }
    .card small { color:#d1d5db!important; }

    /* Alertas */
    .alert { background:#1f2937!important; color:#f9fafb!important; border-color:#374151!important; }
    .alert a { color:#93c5fd!important; }

    /* Carrito vacío */
    .carrito-vacio h3 { color:#f9fafb!important; }
    .carrito-vacio p { color:#d1d5db!important; }
    .carrito-vacio i { color:#64748b!important; }

    /* Resumen del pedido */
    .resumen-pedido { background:#111827!important; color:#f9fafb!important; }
    .resumen-pedido h5 { color:#f9fafb!important; }
    .resumen-pedido small { color:#d1d5db!important; }

    /* Botones */
    .btn-primary {
      background:linear-gradient(135deg,#22c55e,#16a34a)!important;
      color:#0b1220!important; border:none!important;
      box-shadow:0 6px 20px rgba(34,197,94,0.35)!important;
    }
    .btn-primary:hover { background:linear-gradient(135deg,#16a34a,#22c55e)!important; }

    .btn-success {
      background:linear-gradient(135deg,#16a34a,#22c55e)!important;
      color:#0b1220!important;
    }

    .btn-danger {
      background:linear-gradient(135deg,#ef4444,#b91c1c)!important;
      color:#f9fafb!important;
    }

    .btn-outline-light { border-color:#f9fafb!important; color:#f9fafb!important; }
    .btn-outline-light:hover { background:#f9fafb!important; color:#0b1220!important; }

    .btn-outline-warning { border-color:#f59e0b!important; color:#f59e0b!important; }
    .btn-outline-warning:hover { background:#f59e0b!important; color:#0b1220!important; }

    /* Totales y etiquetas */
    .total-carrito, .precio { color:#34d399!important; }
    .badge.bg-light.text-dark { background:#374151!important; color:#f9fafb!important; }
  `;

  function isDarkEnabled() { return !!document.getElementById(DARK_STYLE_ID); }
  function enableDark() {
    if (isDarkEnabled()) return;
    const style = document.createElement("style");
    style.id = DARK_STYLE_ID;
    style.textContent = darkCSS;
    document.head.appendChild(style);
    if (btn) btn.innerHTML = '<i class="fas fa-sun me-1"></i>Light';
    localStorage.setItem("theme","dark");
  }
  function disableDark() {
    const style = document.getElementById(DARK_STYLE_ID);
    if (style) style.remove();
    if (btn) btn.innerHTML = '<i class="fas fa-moon me-1"></i>Dark';
    localStorage.setItem("theme","light");
  }

  const pref = localStorage.getItem("theme") || "light";
  if (pref==="dark") enableDark(); else disableDark();

  if (btn) {
    btn.addEventListener("click", () => {
      if (isDarkEnabled()) disableDark(); else enableDark();
    });
  }
});
</script>

</body>
</html>