// Funciones para el carrito
function agregarAlCarrito(idProducto, nombre, precio) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    
    const productoExistente = carrito.find(item => item.id === idProducto);
    
    if (productoExistente) {
        productoExistente.cantidad += 1;
    } else {
        carrito.push({
            id: idProducto,
            nombre: nombre,
            precio: precio,
            cantidad: 1
        });
    }
    
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarContadorCarrito();
    alert('Producto agregado al carrito');
}

function actualizarContadorCarrito() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
    document.getElementById('contador-carrito').textContent = totalItems;
}