<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MarketGo - Inicio</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Barra de navegación -->
  <div class="navbar">
    <div class="logo">
      <a href="index.php">MarketGo</a>
    </div>

    <div class="menu">
      <div class="dropdown">
        <button class="dropbtn">
          <img src="user-icon.png" alt="Usuario" class="user-icon">
        </button>
        <div class="dropdown-content">
          <?php if ($usuario): ?>
            <p class="user-name">👤 <?php echo htmlspecialchars($usuario); ?></p>
            <a href="carrito.php">🛒 Carrito de Compras</a>
            <a href="perfil.php">⚙️ Mi Cuenta</a>
            <a href="logout.php">🚪 Cerrar Sesión</a>
          <?php else: ?>
            <a href="login.html">🔑 Iniciar Sesión</a>
            <a href="registro.html">📝 Registrarse</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>


  <!-- ===== Encabezado ===== -->

  <header class="header">
   <a href="system.html" class="logo">
  <div class="logo-circle">
    <!-- SVG -->
  </div>
  <div class="logo-text">
    <span class="brand">MarketGo</span>
    <p class="slogan">¡Siempre fresco, siempre cerca!</p>
  </div>
</a>

    <!-- Menú principal -->
    <nav class="nav-links">
      <a href="Index.html">Inicio</a>
      <a href="products.html">Productos</a>
      <a href="cart.html">Carrito</a>
      <a href="contact.html">Contacto</a>
    </nav>
    <!-- Ícono de menú -->
    <div class="menu-icon" onclick="toggleMenu()">&#9776;</div>
  </header>

  <!-- ===== Menú lateral ===== -->
  <aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
      <h2>MarketGo</h2>
    </div>
    <ul>
      <li><a href="login.html">Inicio de Sesión</a></li>
      <li><a href="register.html">Registro</a></li>
    </ul>
    <div class="close-btn" onclick="toggleMenu()">✖</div>
  </aside>

  <!-- ===== Sección Hero ===== -->
  <section class="hero">
    <div class="hero-content">
      <h1>¡Bienvenido a <span>MarketGo</span>!</h1>
      <p>Tu tienda de abarrotes favorita con los mejores productos frescos</p>
      <div class="search-container">
        <input type="text" id="searchInput" placeholder="Buscar productos..." class="search-bar">
        <button class="search-btn" onclick="searchProducts()">🔍</button>
      </div>
    </div>
  </section>

  <!-- ===== Categorías ===== -->
  <section class="categorias">
    <h2>Categorías</h2>
    <div class="categorias-grid">
      <button class="cat-btn" data-category="limpieza">Productos de Limpieza</button>
      <button class="cat-btn" data-category="cuidado">Cuidado Personal</button>
      <button class="cat-btn" data-category="frutas-verduras">Frutas y Verduras</button>
      <button class="cat-btn" data-category="golocinas">Golosinas</button>
      <button class="cat-btn" data-category="papas">Papas Fritas</button>
      <button class="cat-btn" data-category="refrescos">Refrescos</button>
      <button class="cat-btn" data-category="jugos">Jugos</button>
      <button class="cat-btn" data-category="agua">Agua</button>
      <button class="cat-btn" data-category="papeleria">Papelería</button>
      <button class="cat-btn" data-category="embutidos">Embutidos</button>
      <button class="cat-btn" data-category="lacteos">Lácteos</button>
      <button class="cat-btn" data-category="carne">Carne</button>
      <button class="cat-btn" data-category="pollo">Pollo</button>
      <button class="cat-btn" data-category="cigarrillos">Cigarrillos</button>
      <button class="cat-btn" data-category="cerveza">Cervezas y Alcohol</button>
    </div>
  </section>

  <!-- ===== Productos destacados ===== -->
  <section class="productos">
    <h2>Productos Destacados</h2>
    <div class="cards" id="productList">
      <div class="card" data-category="cereal golocinas">
        <img src="producto1.jpg" alt="Cereales y Granola">
        <h3>Cereales y Granola</h3>
        <p>Cereales integrales y granola natural</p>
        <span class="precio">$35</span>
        <span class="stock">Stock: 3</span>
        <button class="btn">Agregar al Carrito</button>
      </div>
      <div class="card" data-category="refrescos">
        <img src="producto2.jpg" alt="Bebidas Refrescantes">
        <h3>Bebidas Refrescantes</h3>
        <p>Refrescos y aguas saborizadas</p>
        <span class="precio">$18.50</span>
        <span class="stock agotado">Stock: 0</span>
        <button class="btn disabled" disabled>Agotado</button>
      </div>
      <div class="card" data-category="golocinas papas">
        <img src="producto3.jpg" alt="Snacks y Botanas">
        <h3>Snacks y Botanas</h3>
        <p>Variedad de botanas y snacks</p>
        <span class="precio">$22.99</span>
        <span class="stock">Stock: 15</span>
        <button class="btn">Agregar al Carrito</button>
      </div>
      <div class="card" data-category="lacteos">
        <img src="producto4.jpg" alt="Lácteos">
        <h3>Lácteos</h3>
        <p>Leche, quesos y yogures</p>
        <span class="precio">$45.00</span>
        <span class="stock">Stock: 10</span>
        <button class="btn">Agregar al Carrito</button>
      </div>
      <div class="card" data-category="frutas-verduras">
        <img src="producto5.jpg" alt="Frutas">
        <h3>Frutas</h3>
        <p>Frutas frescas de temporada</p>
        <span class="precio">$30.00</span>
        <span class="stock">Stock: 20</span>
        <button class="btn">Agregar al Carrito</button>
      </div>
    </div>
  </section>

  <!-- ===== Footer ===== -->
  <footer class="footer">
    <p> &copy; 2025 MarketGo - Todos los derechos reservados</p>
  </footer>

  <script src="main.js"></script>
</body>
</html>