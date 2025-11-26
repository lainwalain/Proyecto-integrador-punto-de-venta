document.getElementById("loginForm").addEventListener("submit", function(e) {
  e.preventDefault();

  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();

  // 🚨 Aquí puedes cambiar las credenciales de prueba
  const empleados = [
    { email: "empleado@marketgo.com", password: "1234" }
  ];
  const administradores = [
    { email: "admin@marketgo.com", password: "admin123" }
  ];

  let userType = null;

  // Verifica si es empleado
  if (empleados.some(user => user.email === email && user.password === password)) {
    userType = "empleado";
  }

  // Verifica si es administrador
  if (administradores.some(user => user.email === email && user.password === password)) {
    userType = "admin";
  }

  if (userType === "empleado") {
    alert("Bienvenido empleado 👨‍💼");
    window.location.href = "empleados.html";
  } else if (userType === "admin") {
    alert("Bienvenido administrador 👑");
    window.location.href = "administradores.html";
  } else {
    alert("❌ Credenciales incorrectas. Intenta de nuevo.");
  }
});
Register
// ==================== Registro ====================
document.addEventListener("DOMContentLoaded", () => {
  const registerForm = document.getElementById("registerForm");

  if (registerForm) {
    registerForm.addEventListener("submit", function(e) {
      e.preventDefault();

      const name = document.getElementById("name").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();
      const role = document.getElementById("role").value;

      if (!name || !email || !password || !role) {
        alert("⚠️ Todos los campos son obligatorios.");
        return;
      }

      // Recuperar usuarios previos o crear lista vacía
      const users = JSON.parse(localStorage.getItem("users")) || [];

      // Verificar si el correo ya existe
      if (users.some(user => user.email === email)) {
        alert("❌ El correo ya está registrado. Usa otro.");
        return;
      }

      // Guardar nuevo usuario
      users.push({ name, email, password, role });
      localStorage.setItem("users", JSON.stringify(users));

      alert("✅ Registro exitoso. Ahora puedes iniciar sesión.");
      window.location.href = "login.html";
    });
  }
});
