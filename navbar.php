<?php
session_start();
?>

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
        <?php if (isset($_SESSION['usuario'])): ?>
          <p class="user-name">👤 <?php echo $_SESSION['usuario']; ?></p>
          <a href="carrito.php">🛒 Carrito de Compras</a>
          <a href="perfil.php">⚙️ Mi Cuenta</a>
          <a href="logout.php">🚪 Cerrar Sesión</a>
        <?php else: ?>
          <a href="login.php">🔑 Iniciar Sesión</a>
          <a href="registro.php">📝 Registrarse</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
