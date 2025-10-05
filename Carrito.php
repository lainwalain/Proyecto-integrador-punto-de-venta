<header class="header">
  <a href="system.html" class="logo">
    <div class="logo-circle">
      <img src="Poalce.png" alt="Logo">
    </div>
    <div class="logo-text">
      <span class="brand">MarketGo</span>
      <p class="slogan">¡Siempre fresco, siempre cerca!</p>
    </div>
  </a>

  <nav class="nav-links">
    <a href="Index.html">Inicio</a>
    <a href="productos.html">Productos</a>
    <a href="Carrito.html">Carrito</a>
    <a href="Favorito.html">Favoritos</a>
  </nav>

  <div class="menu-icon" onclick="toggleMenu()">&#9776;</div>
</header>

<aside id="sidebar" class="sidebar">
  <div class="sidebar-header">
    <h2>MarketGo</h2>
  </div>
  <ul>
    <li><a href="Index.html">Inicio</a></li>
    <li><a href="MiCuenta.html">Mi cuenta</a></li>
    <li><a href="Carrito.html">Carrito</a></li>
    <li><a href="Favorito.html">Favoritos</a></li>
    <li><a href="Historial.html">Historial</a></li>
    <li>
      <button class="btn-logout" onclick="window.location.href='Index.html'">
        Cerrar sesión
      </button>
    </li>
  </ul>
  <div class="close-btn" onclick="toggleMenu()">✖</div>
</aside>
