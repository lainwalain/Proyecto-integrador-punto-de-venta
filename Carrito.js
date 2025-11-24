function toggleMenu() {
  document.getElementById('sidebar').classList.toggle('active');
}

// Manejo de carrito con localStorage
function agregarAlCarrito(productId) {
  let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  carrito.push(productId);
  localStorage.setItem('carrito', JSON.stringify(carrito));
  mostrarCarrito();
}

function eliminarDelCarrito(productId) {
  let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  carrito = carrito.filter(id => id !== productId);
  localStorage.setItem('carrito', JSON.stringify(carrito));
  mostrarCarrito();
}

function mostrarCarrito() {
  const carritoList = document.getElementById('carritoList');
  const totalDiv = document.getElementById('totalCarrito');
  carritoList.innerHTML = '';
  totalDiv.innerHTML = '';

  const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  if(carrito.length === 0){
    carritoList.innerHTML = '<p>Tu carrito está vacío.</p>';
    return;
  }

  let total = 0;
  carrito.forEach(id => {
    const card = document.querySelector(`.card[data-id='${id}']`);
    if(card){
      const clone = card.cloneNode(true);
      clone.classList.add('carrito-card');
      const price = parseFloat(card.querySelector('.precio').textContent.replace('$',''));
      total += price;
      const btnRemove = document.createElement('button');
      btnRemove.textContent = 'Eliminar';
      btnRemove.classList.add('btn-remove');
      btnRemove.onclick = () => eliminarDelCarrito(id);
      clone.appendChild(btnRemove);
      carritoList.appendChild(clone);
    }
  });

  totalDiv.innerHTML = `<h3>Total: $${total.toFixed(2)}</h3>`;
}

document.addEventListener('DOMContentLoaded', mostrarCarrito);
