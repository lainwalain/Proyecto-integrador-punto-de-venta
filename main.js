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
