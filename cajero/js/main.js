// Variables globales
const BASE_URL = window.location.origin + '/sisventas/cajero';

// Textos dinámicos - estos se cargarán desde PHP
let textos = {
    // Búsqueda
    search_results: 'Los resultados de búsqueda aparecerán aquí',
    enter_search_term: 'Ingrese un código o nombre para buscar',
    searching: 'Buscando productos...',
    search_error: 'Error al buscar productos',
    no_products_found: 'No se encontraron productos',
    stock: 'Stock',
    code: 'Código',
    
    // Alertas
    empty_cart: 'El carrito está vacío',
    customer_name_required: 'Ingrese el nombre del cliente',
    processing_sale: 'Procesando venta...',
    sale_cancelled: 'Venta cancelada',
    product_added: 'Producto agregado al carrito',
    product_removed: 'Producto eliminado del carrito',
    insufficient_stock: 'Stock insuficiente',
    product_not_found: 'Producto no encontrado',
    invalid_email: 'Ingrese un correo válido',
    invalid_rfc: 'Ingrese un RFC válido (ej: XAXX010101000)',
    
    // Confirmaciones
    confirm_remove: '¿Está seguro de eliminar este producto del carrito?',
    confirm_cancel: '¿Está seguro de cancelar la venta? Se perderán todos los productos del carrito.',
    confirm_sale: '¿Confirmar venta para %1?',
    
    // Carrito
    each: 'c/u',
    remove: 'Eliminar',
    
    // Cliente
    customer_phone: 'Celular (opcional)',
    customer_email: 'Correo (opcional)'
};

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    cargarTextosDesdeDOM();
    configurarBusqueda();
    configurarEventos();
    configurarValidaciones();
    document.getElementById('buscarProducto').focus();
});

// Cargar textos desde data attributes en el DOM
function cargarTextosDesdeDOM() {
    const textContainer = document.getElementById('textos-js');
    if (textContainer) {
        textos = JSON.parse(textContainer.textContent);
    }
}

// Configurar eventos
function configurarEventos() {
    // Enter en búsqueda
    document.getElementById('buscarProducto').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            buscarProducto();
        }
    });
}

// Configurar validaciones de campos
function configurarValidaciones() {
    // Validar campo de celular (solo números y +)
    const celularInput = document.getElementById('celularCliente');
    if (celularInput) {
        celularInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });
        
        celularInput.addEventListener('keypress', function(e) {
            // Permitir solo números, +, backspace, delete, tab
            if (!/[0-9+]/.test(e.key) && 
                e.key !== 'Backspace' && 
                e.key !== 'Delete' && 
                e.key !== 'Tab' &&
                e.key !== 'ArrowLeft' &&
                e.key !== 'ArrowRight') {
                e.preventDefault();
            }
        });
    }
    
    // Validar campo de RFC (solo mayúsculas, números y caracteres permitidos)
    const rfcInput = document.getElementById('nitCliente');
    if (rfcInput) {
        rfcInput.addEventListener('input', function(e) {
            // Convertir a mayúsculas y permitir solo caracteres válidos para RFC
            this.value = this.value.toUpperCase().replace(/[^A-ZÑ&0-9]/g, '');
        });
        
        rfcInput.addEventListener('keypress', function(e) {
            // Permitir solo letras, números, Ñ, &, backspace, delete, tab
            if (!/[A-ZÑ&0-9]/.test(e.key.toUpperCase()) && 
                e.key !== 'Backspace' && 
                e.key !== 'Delete' && 
                e.key !== 'Tab' &&
                e.key !== 'ArrowLeft' &&
                e.key !== 'ArrowRight') {
                e.preventDefault();
            }
        });
    }
    
    // Enter en nombre del cliente para finalizar venta
    const nombreClienteInput = document.getElementById('nombreCliente');
    if (nombreClienteInput) {
        nombreClienteInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                finalizarVenta();
            }
        });
    }
}

// Re-configurar validaciones después de actualizar carrito
function reconfigurarValidaciones() {
    configurarValidaciones();
    
    // Re-asignar evento Enter en nombre del cliente
    const nombreClienteInput = document.getElementById('nombreCliente');
    if (nombreClienteInput) {
        nombreClienteInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                finalizarVenta();
            }
        });
    }
}

// Configurar búsqueda en tiempo real
function configurarBusqueda() {
    const busquedaInput = document.getElementById('buscarProducto');
    let timeout;
    
    busquedaInput.addEventListener('input', function(e) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            if (e.target.value.length >= 2) {
                buscarProducto();
            } else if (e.target.value.length === 0) {
                document.getElementById('resultadosBusqueda').innerHTML = `<p class="text-muted text-center">${textos.search_results}</p>`;
            }
        }, 300);
    });
}

// Buscar productos
function buscarProducto() {
    const termino = document.getElementById('buscarProducto').value.trim();
    
    if (termino.length === 0) {
        document.getElementById('resultadosBusqueda').innerHTML = `<p class="text-muted text-center">${textos.enter_search_term}</p>`;
        return;
    }
    
    // Mostrar loading
    document.getElementById('resultadosBusqueda').innerHTML = `<p class="text-center">${textos.searching}</p>`;
    
    fetch(`${BASE_URL}/procesos/buscar_producto.php?termino=${encodeURIComponent(termino)}`)
        .then(response => {
            console.log('Respuesta búsqueda:', response);
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos búsqueda:', data);
            mostrarResultadosBusqueda(data);
        })
        .catch(error => {
            console.error('Error en búsqueda:', error);
            document.getElementById('resultadosBusqueda').innerHTML = `<p class="text-danger text-center">${textos.search_error}</p>`;
        });
}

// Mostrar resultados de búsqueda
function mostrarResultadosBusqueda(productos) {
    const contenedor = document.getElementById('resultadosBusqueda');
    
    if (!productos || productos.length === 0) {
        contenedor.innerHTML = `<p class="text-muted text-center">${textos.no_products_found}</p>`;
        return;
    }
    
    let html = '';
    productos.forEach(producto => {
        html += `
            <div class="producto-item" onclick="agregarAlCarrito(${producto.id_producto})">
                <div class="producto-info">
                    <h5>${producto.nombre}</h5>
                    <div class="d-flex justify-content-between">
                        <span class="precio">$${parseFloat(producto.precio_venta).toFixed(2)}</span>
                        <span class="stock">${textos.stock}: ${producto.stock}</span>
                    </div>
                    <small class="text-muted">${textos.code}: ${producto.codigo}</small>
                </div>
                <button class="btn-agregar" onclick="event.stopPropagation(); agregarAlCarrito(${producto.id_producto})">
                    +
                </button>
            </div>
        `;
    });
    
    contenedor.innerHTML = html;
}

// Agregar producto al carrito
function agregarAlCarrito(idProducto) {
    console.log('Intentando agregar producto ID:', idProducto);
    
    fetch(`${BASE_URL}/procesos/agregar_carrito.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_producto=${idProducto}`
    })
    .then(response => {
        console.log('Respuesta agregar:', response);
        if (!response.ok) {
            throw new Error('Error HTTP: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos agregar:', data);
        if (data.success) {
            // Limpiar búsqueda
            document.getElementById('buscarProducto').value = '';
            document.getElementById('resultadosBusqueda').innerHTML = `<p class="text-muted text-center">${textos.search_results}</p>`;
            
            // Actualizar carrito sin recargar
            actualizarVistaCarrito();
            mostrarAlerta('✅ ' + textos.product_added, 'success');
        } else {
            const mensajeError = data.message === 'Stock insuficiente' ? textos.insufficient_stock : 
                               data.message === 'Producto no encontrado' ? textos.product_not_found : 
                               data.message;
            mostrarAlerta('❌ ' + mensajeError, 'error');
        }
    })
    .catch(error => {
        console.error('Error completo agregar:', error);
        mostrarAlerta('❌ Error al agregar producto: ' + error.message, 'error');
    });
}

// Actualizar cantidad en carrito
function actualizarCantidad(index, cambio) {
    console.log('Actualizando cantidad - Index:', index, 'Cambio:', cambio);
    
    fetch(`${BASE_URL}/procesos/actualizar_cantidad.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `index=${index}&cambio=${cambio}`
    })
    .then(response => {
        console.log('Respuesta actualizar:', response);
        if (!response.ok) {
            throw new Error('Error HTTP: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos actualizar:', data);
        if (data.success) {
            // Actualizar carrito sin recargar
            actualizarVistaCarrito();
        } else {
            mostrarAlerta('❌ ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error completo actualizar:', error);
        mostrarAlerta('❌ Error al actualizar cantidad: ' + error.message, 'error');
    });
}

// Eliminar producto del carrito
function eliminarDelCarrito(index) {
    if (!confirm(textos.confirm_remove)) {
        return;
    }
    
    console.log('Eliminando producto index:', index);
    
    fetch(`${BASE_URL}/procesos/eliminar_carrito.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `index=${index}`
    })
    .then(response => {
        console.log('Respuesta eliminar:', response);
        if (!response.ok) {
            throw new Error('Error HTTP: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos eliminar:', data);
        if (data.success) {
            // Actualizar carrito sin recargar
            actualizarVistaCarrito();
            mostrarAlerta('🗑️ ' + textos.product_removed, 'success');
        } else {
            mostrarAlerta('❌ ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error completo eliminar:', error);
        mostrarAlerta('❌ Error al eliminar producto: ' + error.message, 'error');
    });
}

// Función para actualizar la vista del carrito sin recargar la página
function actualizarVistaCarrito() {
    // Hacer una petición para obtener el HTML actualizado del carrito
    fetch(`${BASE_URL}/procesos/obtener_carrito.php`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener carrito');
            }
            return response.text();
        })
        .then(html => {
            // Reemplazar TODO el contenido del carritoContainer
            document.getElementById('carritoContainer').innerHTML = html;
            
            // Re-configurar validaciones después de actualizar
            reconfigurarValidaciones();
        })
        .catch(error => {
            console.error('Error al actualizar carrito:', error);
            // Fallback: recargar la página si hay error
            location.reload();
        });
}

// Función para validar email
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Función para validar RFC mexicano
function isValidRFCMexico(rfc) {
    if (!rfc || rfc.trim() === '') return true; // Opcional, si está vacío es válido
    
    const rfcLimpio = rfc.toUpperCase().trim();
    
    // Longitudes válidas para RFC mexicano
    if (rfcLimpio.length < 12 || rfcLimpio.length > 13) {
        return false;
    }
    
    // Patrón para RFC mexicano:
    // - Personas morales: 3 letras + 6 números + 3 caracteres alfanuméricos
    // - Personas físicas: 4 letras + 6 números + 3 caracteres alfanuméricos  
    const rfcRegex = /^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/;
    
    return rfcRegex.test(rfcLimpio);
}

// Finalizar venta
function finalizarVenta() {
    const carritoItems = document.querySelectorAll('.carrito-item');
    if (carritoItems.length === 0) {
        mostrarAlerta('⚠️ ' + textos.empty_cart, 'warning');
        return;
    }
    
    const nombreCliente = document.getElementById('nombreCliente').value.trim();
    const nitCliente = document.getElementById('nitCliente').value.trim();
    const celularCliente = document.getElementById('celularCliente').value.trim();
    const emailCliente = document.getElementById('emailCliente').value.trim();
    
    if (!nombreCliente) {
        mostrarAlerta('⚠️ ' + textos.customer_name_required, 'warning');
        document.getElementById('nombreCliente').focus();
        return;
    }
    
    // Validar email si se proporcionó
    if (emailCliente && !isValidEmail(emailCliente)) {
        mostrarAlerta('❌ ' + textos.invalid_email, 'error');
        document.getElementById('emailCliente').focus();
        return;
    }
    
    // Validar RFC si se proporcionó
    if (nitCliente && !isValidRFCMexico(nitCliente)) {
        mostrarAlerta('❌ ' + textos.invalid_rfc, 'error');
        document.getElementById('nitCliente').focus();
        return;
    }
    
    // Obtener texto de confirmación dinámico
    const confirmacionTexto = textos.confirm_sale ? textos.confirm_sale.replace('%1', nombreCliente) : `¿Confirmar venta para ${nombreCliente}?`;
    
    if (!confirm(confirmacionTexto)) {
        return;
    }
    
    // Mostrar loading
    mostrarAlerta('⏳ ' + textos.processing_sale, 'info');
    
    fetch(`${BASE_URL}/procesos/finalizar_venta.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `nombre_cliente=${encodeURIComponent(nombreCliente)}&nit_cliente=${encodeURIComponent(nitCliente)}&celular_cliente=${encodeURIComponent(celularCliente)}&email_cliente=${encodeURIComponent(emailCliente)}`
    })
    .then(response => {
        console.log('Respuesta finalizar:', response);
        if (!response.ok) {
            throw new Error('Error HTTP: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos finalizar:', data);
        if (data.success) {
            mostrarAlerta('✅ ' + data.message, 'success');
            // Recargar después de finalizar venta (esto es necesario)
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            mostrarAlerta('❌ ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error completo finalizar:', error);
        mostrarAlerta('❌ Error al finalizar venta: ' + error.message, 'error');
    });
}

// Cancelar venta
function cancelarVenta() {
    const carritoItems = document.querySelectorAll('.carrito-item');
    if (carritoItems.length === 0) {
        mostrarAlerta('ℹ️ ' + textos.empty_cart, 'info');
        return;
    }
    
    if (!confirm(textos.confirm_cancel)) {
        return;
    }
    
    fetch(`${BASE_URL}/procesos/cancelar_venta.php`, {
        method: 'POST'
    })
    .then(response => {
        console.log('Respuesta cancelar:', response);
        if (!response.ok) {
            throw new Error('Error HTTP: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos cancelar:', data);
        if (data.success) {
            // Recargar después de cancelar (esto limpia todo)
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error completo cancelar:', error);
        mostrarAlerta('❌ Error al cancelar venta: ' + error.message, 'error');
    });
}

// Mostrar alertas
function mostrarAlerta(mensaje, tipo) {
    // Remover alertas existentes
    const alertasExistentes = document.querySelectorAll('.alert-custom');
    alertasExistentes.forEach(alerta => alerta.remove());
    
    const alerta = document.createElement('div');
    alerta.className = `alert-custom alert alert-${tipo === 'error' ? 'danger' : tipo} alert-dismissible fade show`;
    alerta.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alerta);
    
    // Auto-remover después de 4 segundos
    setTimeout(() => {
        if (alerta.parentNode) {
            alerta.remove();
        }
    }, 4000);
}

// Teclado rápido
document.addEventListener('keydown', function(e) {
    // F1 - Buscar producto
    if (e.key === 'F1') {
        e.preventDefault();
        document.getElementById('buscarProducto').focus();
    }
    // F2 - Finalizar venta
    else if (e.key === 'F2') {
        e.preventDefault();
        finalizarVenta();
    }
    // F3 - Cancelar venta
    else if (e.key === 'F3') {
        e.preventDefault();
        cancelarVenta();
    }
    // Ctrl+Shift+L - Cambiar idioma
    else if (e.ctrlKey && e.shiftKey && e.key === 'L') {
        e.preventDefault();
        if (window.languageManager) {
            window.languageManager.toggleLanguage();
        }
    }
    // Ctrl+Shift+T - Cambiar tema
    else if (e.ctrlKey && e.shiftKey && e.key === 'T') {
        e.preventDefault();
        if (window.themeManager) {
            window.themeManager.toggleTheme();
        }
    }
    // Escape - Limpiar búsqueda
    else if (e.key === 'Escape') {
        document.getElementById('buscarProducto').value = '';
        document.getElementById('resultadosBusqueda').innerHTML = `<p class="text-muted text-center">${textos.search_results}</p>`;
        document.getElementById('buscarProducto').focus();
    }
});