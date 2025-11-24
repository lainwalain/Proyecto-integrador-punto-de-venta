// Toggle menú lateral
function toggleMenu() {
  document.getElementById("sidebar").classList.toggle("active");
}

// Buscar productos
function searchProducts() {
  const input = document.getElementById("searchInput").value.toLowerCase();
  const products = document.querySelectorAll("#productList .card");

  products.forEach(product => {
    const text = product.innerText.toLowerCase();
    product.style.display = text.includes(input) ? "block" : "none";
  });
}

// Filtrar por categoría
document.querySelectorAll(".cat-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const category = btn.dataset.category;
    const products = document.querySelectorAll("#productList .card");

    products.forEach(product => {
      product.style.display = product.dataset.category.includes(category) ? "block" : "none";
    });
  });
});
// Guardar producto en localStorage
function addToStorage(key, product) {
  let items = JSON.parse(localStorage.getItem(key)) || [];
  if (!items.find(p => p.id === product.id)) {
    items.push(product);
    localStorage.setItem(key, JSON.stringify(items));
  }
}

// ====================
// Acciones en usuario.html
// ====================
document.addEventListener("DOMContentLoaded", () => {
  // Botón favoritos
  document.querySelectorAll(".btn-fav").forEach(btn => {
    btn.addEventListener("click", () => {
      const card = btn.closest(".card");
      const product = {
        id: card.dataset.id,
        nombre: card.dataset.nombre,
        precio: card.dataset.precio,
        img: card.dataset.img
      };
      addToStorage("favoritos", product);
      btn.classList.toggle("active");
    });
  });

  // Botón carrito
  document.querySelectorAll(".btn-cart").forEach(btn => {
    btn.addEventListener("click", () => {
      const card = btn.closest(".card");
      const product = {
        id: card.dataset.id,
        nombre: card.dataset.nombre,
        precio: parseFloat(card.dataset.precio),
        img: card.dataset.img
      };
      addToStorage("carrito", product);
      btn.classList.add("active");
      setTimeout(() => btn.classList.remove("active"), 1000);
      window.location.href = "Carrito.html"; // redirige
    });
  });

  // ====================
  // Mostrar favoritos
  // ====================
  if (document.getElementById("favoritosList")) {
    let favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];
    let contenedor = document.getElementById("favoritosList");
    favoritos.forEach(p => {
      let div = document.createElement("div");
      div.classList.add("card");
      div.innerHTML = `
        <img src="${p.img}" alt="${p.nombre}">
        <h3>${p.nombre}</h3>
        <span class="precio">$${p.precio}</span>
      `;
      contenedor.appendChild(div);
    });
  }

  // ====================
  // Mostrar carrito
  // ====================
  if (document.getElementById("carritoList")) {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    let contenedor = document.getElementById("carritoList");
    let total = 0;
    carrito.forEach(p => {
      total += p.precio;
      let div = document.createElement("div");
      div.classList.add("card");
      div.innerHTML = `
        <img src="${p.img}" alt="${p.nombre}">
        <h3>${p.nombre}</h3>
        <span class="precio">$${p.precio}</span>
      `;
      contenedor.appendChild(div);
    });
    document.getElementById("totalPrice").textContent = total.toFixed(2);
  }
});
