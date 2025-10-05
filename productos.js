document.addEventListener("DOMContentLoaded", () => {
  const buscador = document.getElementById("buscador");
  const productos = document.querySelectorAll(".product-card");

  buscador.addEventListener("input", () => {
    const texto = buscador.value.toLowerCase();
    productos.forEach(prod => {
      const nombre = prod.querySelector("h3").textContent.toLowerCase();
      prod.style.display = nombre.includes(texto) ? "block" : "none";
    });
  });
});
