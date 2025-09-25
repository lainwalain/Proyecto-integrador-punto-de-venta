// Mostrar/ocultar secciones
function showSection(id) {
  const sections = document.querySelectorAll(".tab-content");
  sections.forEach(section => section.classList.remove("active"));
  document.getElementById(id).classList.add("active");
}

// Mostrar/ocultar sidebar
function toggleMenu() {
  document.getElementById("sidebar").classList.toggle("active");
}
