<?php
include '../includes/config.php';
include '../functions/productos_functions.php';
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
    </style>
</head>
<body>
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
                <a class="nav-link" href="../login/index.php">
                    <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Tu Carrito de Compras</h1>
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
                            <button id="btn-procesar-pago" class="btn btn-success btn-lg" disabled>
                                <i class="fas fa-credit-card me-2"></i>Proceder al Pago
                            </button>
                            <a href="../index.php" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i>Seguir Comprando
                            </a>
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
        });

        function cargarCarrito() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const carritoItems = document.getElementById('carrito-items');
            const carritoVacio = document.getElementById('carrito-vacio');
            const resumenPedido = document.getElementById('resumen-pedido');
            const btnProcesarPago = document.getElementById('btn-procesar-pago');

            if (carrito.length === 0) {
                carritoItems.style.display = 'none';
                carritoVacio.style.display = 'block';
                resumenPedido.innerHTML = '<p class="text-center">No hay productos en el carrito</p>';
                btnProcesarPago.disabled = true;
                return;
            }

            carritoItems.style.display = 'block';
            carritoVacio.style.display = 'none';
            
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
                                 onerror="this.src='../../almacen/img_productos'">
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

            btnProcesarPago.disabled = false;
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
            
            mostrarNotificacion(`Producto eliminado: ${productoEliminado}`);
            
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

        function mostrarNotificacion(mensaje) {
            const notification = document.createElement('div');
            notification.className = 'position-fixed bottom-0 end-0 p-3';
            notification.style.zIndex = '9999';
            notification.innerHTML = `
                <div class="toast show" role="alert">
                    <div class="toast-header bg-danger text-white">
                        <i class="fas fa-trash me-2"></i>
                        <strong class="me-auto">Producto Eliminado</strong>
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

        document.getElementById('btn-procesar-pago').addEventListener('click', function() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            
            if (carrito.length === 0) {
                alert('Tu carrito está vacío');
                return;
            }
            
            const btn = this;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
            btn.disabled = true;
            
            setTimeout(() => {
                alert('¡Pedido procesado exitosamente! En breve recibirás un correo de confirmación.');
                
                localStorage.removeItem('carrito');
                cargarCarrito();
                actualizarContadorCarrito();
                
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 2000);
        });

        function vaciarCarrito() {
            if (confirm('¿Estás seguro de que quieres vaciar todo el carrito?')) {
                localStorage.removeItem('carrito');
                cargarCarrito();
                actualizarContadorCarrito();
                mostrarNotificacion('Carrito vaciado correctamente');
            }
        }
    </script>
</body>
</html>