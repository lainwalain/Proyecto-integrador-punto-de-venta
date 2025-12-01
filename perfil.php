<?php
session_start();
include '../includes/config.php';
include '../functions/usuario_functions.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login/index.php');
    exit();
}

$usuario_logueado = $_SESSION;
$usuario_info = obtenerUsuarioPorId($pdo, $usuario_logueado['id_usuario']);
$rol_usuario = obtenerRolUsuario($pdo, $usuario_info['id_rol']);
$estadisticas = obtenerEstadisticasUsuario($pdo, $usuario_logueado['id_usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Market Go</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #2ecc71;
            --color-secondary: #27ae60;
            --color-accent: #16a085;
            --color-light: #ecf0f1;
            --color-dark: #2c3e50;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%) !important;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .avatar-lg {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border: 3px solid white;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-left: 4px solid var(--color-primary);
        }
        
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .badge-rol {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">
                <i class="fas fa-store me-2"></i>
                <span>MARKET GO</span>
                <small class="badge bg-light text-dark ms-2">PRO</small>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-home me-1"></i>Inicio
                </a>
                <a class="nav-link" href="carrito.php">
                    <i class="fas fa-shopping-cart me-1"></i>Carrito
                </a>
                <a class="nav-link active" href="perfil.php">
                    <i class="fas fa-user me-1"></i>Mi Perfil
                </a>
                <a class="nav-link" href="#" onclick="cerrarSesion()">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <!-- Header del Perfil -->
        <div class="profile-header text-center">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <div class="avatar-lg rounded-circle d-flex align-items-center justify-content-center mx-auto">
                        <span class="fs-2 fw-bold"><?= strtoupper(substr($usuario_info['nombres'], 0, 1)) ?></span>
                    </div>
                </div>
                <div class="col-md-8 text-start">
                    <h2 class="mb-1"><?= htmlspecialchars($usuario_info['nombres']) ?></h2>
                    <p class="mb-1"><?= htmlspecialchars($usuario_info['email']) ?></p>
                    <span class="badge badge-rol"><?= $rol_usuario ?></span>
                </div>
                <div class="col-md-2 text-end">
                    <small>Miembro desde:<br><?= date('M Y', strtotime($usuario_info['fyh_creacion'])) ?></small>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Estadísticas -->
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-primary mb-0"><?= $estadisticas['total_pedidos'] ?? 0 ?></h3>
                            <p class="text-muted mb-0">Pedidos Realizados</p>
                        </div>
                        <i class="fas fa-shopping-bag fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-success mb-0">$<?= number_format($estadisticas['total_gastado'] ?? 0, 2) ?></h3>
                            <p class="text-muted mb-0">Total Gastado</p>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-info mb-0">
                                <?= $estadisticas['ultima_compra'] ? date('d M Y', strtotime($estadisticas['ultima_compra'])) : 'Sin compras' ?>
                            </h6>
                            <p class="text-muted mb-0">Última Compra</p>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Información Personal -->
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h4 class="mb-4">
                        <i class="fas fa-user-circle me-2"></i>Información Personal
                    </h4>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Nombre Completo</label>
                        <p class="fs-6 mb-0"><?= htmlspecialchars($usuario_info['nombres']) ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Correo Electrónico</label>
                        <p class="fs-6 mb-0"><?= htmlspecialchars($usuario_info['email']) ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Rol en el Sistema</label>
                        <p class="fs-6 mb-0">
                            <span class="badge bg-primary"><?= $rol_usuario ?></span>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Fecha de Registro</label>
                        <p class="fs-6 mb-0"><?= date('d/m/Y H:i', strtotime($usuario_info['fyh_creacion'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <h4 class="mb-4">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h4>
                    
                    <div class="d-grid gap-2">
                        <a href="mis_pedidos.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Ver Mis Pedidos
                        </a>
                        
                        <a href="carrito.php" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-shopping-cart me-2"></i>Ir al Carrito
                        </a>
                        
                        <a href="../index.php" class="btn btn-outline-secondary">
                            <i class="fas fa-store me-2"></i>Seguir Comprando
                        </a>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            ¿Necesitas ayuda? Contacta a soporte
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function cerrarSesion() {
        if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
            localStorage.removeItem('carrito');
            window.location.href = '../logout.php';
        }
    }
    </script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
  const translations = {
    es: {
      inicio: "Inicio",
      carrito: "Carrito",
      perfil: "Mi Perfil",
      cerrarSesion: "Cerrar Sesión",
      pedidosRealizados: "Pedidos Realizados",
      totalGastado: "Total Gastado",
      ultimaCompra: "Última Compra",
      infoPersonal: "Información Personal",
      nombreCompleto: "Nombre Completo",
      correo: "Correo Electrónico",
      rol: "Rol en el Sistema",
      fechaRegistro: "Fecha de Registro",
      accionesRapidas: "Acciones Rápidas",
      verPedidos: "Ver Mis Pedidos",
      irCarrito: "Ir al Carrito",
      seguirComprando: "Seguir Comprando",
      ayuda: "¿Necesitas ayuda? Contacta a soporte",
      miembroDesde: "Miembro desde:"
    },
    en: {
      inicio: "Home",
      carrito: "Cart",
      perfil: "My Profile",
      cerrarSesion: "Log Out",
      pedidosRealizados: "Orders Placed",
      totalGastado: "Total Spent",
      ultimaCompra: "Last Purchase",
      infoPersonal: "Personal Information",
      nombreCompleto: "Full Name",
      correo: "Email Address",
      rol: "System Role",
      fechaRegistro: "Registration Date",
      accionesRapidas: "Quick Actions",
      verPedidos: "View My Orders",
      irCarrito: "Go to Cart",
      seguirComprando: "Continue Shopping",
      ayuda: "Need help? Contact support",
      miembroDesde: "Member since:"
    }
  };

  let currentLang = localStorage.getItem("lang") || "es";
  const btnLanguage = document.getElementById("btnLanguage");

  function applyLanguage(lang) {
    const t = translations[lang];

    document.querySelector(".navbar-nav a[href='../index.php']").innerHTML = `<i class="fas fa-home me-1"></i>${t.inicio}`;
    document.querySelector(".navbar-nav a[href='carrito.php']").innerHTML = `<i class="fas fa-shopping-cart me-1"></i>${t.carrito}`;
    document.querySelector(".navbar-nav a[href='perfil.php']").innerHTML = `<i class="fas fa-user me-1"></i>${t.perfil}`;
    document.querySelector(".navbar-nav a[onclick='cerrarSesion()']").innerHTML = `<i class="fas fa-sign-out-alt me-1"></i>${t.cerrarSesion}`;

    // Estadísticas
    const statCards = document.querySelectorAll(".stat-card p.text-muted");
    if (statCards.length >= 3) {
      statCards[0].textContent = t.pedidosRealizados;
      statCards[1].textContent = t.totalGastado;
      statCards[2].textContent = t.ultimaCompra;
    }

    // Info personal
    const infoCard = document.querySelector(".info-card h4");
    if (infoCard) infoCard.innerHTML = `<i class="fas fa-user-circle me-2"></i>${t.infoPersonal}`;

    const labels = document.querySelectorAll(".info-card label");
    if (labels.length >= 4) {
      labels[0].textContent = t.nombreCompleto;
      labels[1].textContent = t.correo;
      labels[2].textContent = t.rol;
      labels[3].textContent = t.fechaRegistro;
    }

    // Acciones rápidas
    const accionesCard = document.querySelectorAll(".info-card h4")[1];
    if (accionesCard) accionesCard.innerHTML = `<i class="fas fa-bolt me-2"></i>${t.accionesRapidas}`;

    const accionesBtns = document.querySelectorAll(".info-card .btn");
    if (accionesBtns.length >= 3) {
      accionesBtns[0].innerHTML = `<i class="fas fa-shopping-bag me-2"></i>${t.verPedidos}`;
      accionesBtns[1].innerHTML = `<i class="fas fa-shopping-cart me-2"></i>${t.irCarrito}`;
      accionesBtns[2].innerHTML = `<i class="fas fa-store me-2"></i>${t.seguirComprando}`;
    }

    const ayudaText = document.querySelector(".info-card small.text-muted");
    if (ayudaText) ayudaText.innerHTML = `<i class="fas fa-info-circle me-1"></i>${t.ayuda}`;

    const miembroDesde = document.querySelector(".profile-header small");
    if (miembroDesde) {
      const original = miembroDesde.innerHTML.split("<br>");
      miembroDesde.innerHTML = `${t.miembroDesde}<br>${original[1]}`;
    }

    btnLanguage.innerHTML = lang === "es" ? `<i class="fas fa-globe me-1"></i>English` : `<i class="fas fa-globe me-1"></i>Español`;
  }

  applyLanguage(currentLang);

  btnLanguage.addEventListener("click", () => {
    currentLang = currentLang === "es" ? "en" : "es";
    localStorage.setItem("lang", currentLang);
    applyLanguage(currentLang);
  });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const DARK_STYLE_ID = "dark-mode-overrides";
  const btn = document.getElementById("btnDarkMode");

  const darkCSS = `
    body { background-color:#0b1220 !important; color:#e5e7eb !important; }
    .navbar { background:linear-gradient(135deg,#0f172a,#111827)!important; }
    .profile-header { background:linear-gradient(135deg,#0f172a,#111827)!important; color:#f3f4f6!important; }
    .stat-card, .info-card { background:#111827!important; color:#f3f4f6!important; box-shadow:0 8px 30px rgba(0,0,0,0.6)!important; }
    .badge-rol { background:linear-gradient(135deg,#0ea5e9,#1d4ed8)!important; }
    .btn-primary { background:linear-gradient(135deg,#22c55e,#16a34a)!important; color:#0b1220!important; }
    .btn-outline-primary { border-color:#22c55e!important; color:#22c55e!important; }
    .btn-outline-secondary { border-color:#9ca3af!important; color:#9ca3af!important; }
  `;

  function isDarkEnabled() { return !!document.getElementById(DARK_STYLE_ID); }
  function enableDark() {
    if (isDarkEnabled()) return;
    const style = document.createElement("style");
    style.id = DARK_STYLE_ID;
    style.textContent = darkCSS;
    document.head.appendChild(style);
    btn.innerHTML = `<i class="fas fa-sun"></i> Light`;
    localStorage.setItem("theme","dark");
  }
  function disableDark() {
    const style = document.getElementById(DARK_STYLE_ID);
    if (style) style.remove();
    btn.innerHTML = `<i class="fas fa-moon"></i> Dark`;
    localStorage.setItem("theme","light");
  }

  const pref = localStorage.getItem("theme") || "light";
  if (pref==="dark") enableDark(); else disableDark();

  btn.addEventListener("click", () => {
    if (isDarkEnabled()) disableDark(); else enableDark();
  });
});
</script>

</body>
</html>