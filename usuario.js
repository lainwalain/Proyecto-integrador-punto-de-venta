// Sidebar toggle
function toggleMenu() {
  document.getElementById("sidebar").classList.toggle("active");
}

// Favoritos
document.querySelectorAll(".fav-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    btn.classList.toggle("active");
    // Redirigir a Favorito.html si se activa
    if (btn.classList.contains("active")) {
      window.location.href = "Favorito.html";
    }
  });
});

// Carrito
document.querySelectorAll(".cart-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    window.location.href = "Carrito.html";
  });
});
