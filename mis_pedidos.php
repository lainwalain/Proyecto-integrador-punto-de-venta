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

// Obtener pedidos del usuario
$pedidos = obtenerPedidosUsuario($pdo, $usuario_logueado['id_usuario']);

// Para testing: si no hay pedidos, mostrar mensaje específico
$cliente_existe = obtenerClientePorEmail($pdo, $usuario_info['email']);
$todos_los_pedidos = obtenerTodosLosPedidos($pdo);

$estadisticas = obtenerEstadisticasUsuario($pdo, $usuario_logueado['id_usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Market Go</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #2ecc71;
            --color-secondary: #27ae60;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%) !important;
        }
        
        .pedido-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            border-left: 4px solid var(--color-primary);
            transition: all 0.3s ease;
        }
        
        .pedido-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        
        .estado-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .info-alert {
            border-left: 4px solid #17a2b8;
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
                <a class="nav-link" href="perfil.php">
                    <i class="fas fa-user me-1"></i>Mi Perfil
                </a>
                <a class="nav-link active" href="mis_pedidos.php">
                    <i class="fas fa-shopping-bag me-1"></i>Mis Pedidos
                </a>
                <a class="nav-link" href="#" onclick="cerrarSesion()">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0">
                        <i class="fas fa-shopping-bag me-2"></i>Mis Pedidos
                    </h1>
                    <div class="text-end">
                        <small class="text-muted d-block">
                            <i class="fas fa-user me-1"></i>
                            <?= htmlspecialchars($usuario_info['nombres']) ?>
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-envelope me-1"></i>
                            <?= htmlspecialchars($usuario_info['email']) ?>
                        </small>
                    </div>
                </div>
                
                <!-- Información del estado del cliente -->
                <?php if(!$cliente_existe): ?>
                    <div class="alert alert-info info-alert">
                        <h6><i class="fas fa-info-circle me-2"></i>Información importante</h6>
                        <p class="mb-2">Aún no tienes un perfil de cliente asociado. Se creará automáticamente cuando realices tu primera compra.</p>
                        <small class="text-muted">Email registrado: <strong><?= htmlspecialchars($usuario_info['email']) ?></strong></small>
                    </div>
                <?php endif; ?>
                
                <?php if(empty($pedidos)): ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>No tienes pedidos aún</h3>
                        <p class="text-muted mb-4">
                            <?php if(!$cliente_existe): ?>
                                Realiza tu primera compra para crear tu perfil de cliente y ver tus pedidos aquí.
                            <?php else: ?>
                                Tu perfil de cliente está listo. Realiza tu primera compra para ver tus pedidos.
                            <?php endif; ?>
                        </p>
                        <a href="../index.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-store me-2"></i>Comenzar a Comprar
                        </a>
                        
                        <!-- Información de debugging para administradores -->
                        <?php if($usuario_info['id_rol'] == 1): ?>
                            <div class="mt-4 p-3 bg-light rounded">
                                <h6 class="text-muted">Información para administrador:</h6>
                                <small class="text-muted">
                                    <strong>Cliente existe:</strong> <?= $cliente_existe ? 'Sí' : 'No' ?><br>
                                    <strong>Total de pedidos en sistema:</strong> <?= count($todos_los_pedidos) ?><br>
                                    <strong>Email del usuario:</strong> <?= htmlspecialchars($usuario_info['email']) ?>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <!-- Estadísticas Rápidas -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-1"><?= count($pedidos) ?></h4>
                                    <small>Total Pedidos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-1">$<?= number_format(array_sum(array_column($pedidos, 'total_pagado')), 2) ?></h4>
                                    <small>Total Gastado</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1"><?= array_sum(array_column($pedidos, 'total_productos')) ?></h6>
                                    <small>Productos Comprados</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1"><?= $estadisticas['ultima_compra'] ? date('d/m/Y', strtotime($estadisticas['ultima_compra'])) : 'N/A' ?></h6>
                                    <small>Última Compra</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Pedidos -->
                    <div class="mb-4">
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-history me-2"></i>
                            Historial de Pedidos (<?= count($pedidos) ?>)
                        </h5>
                        
                        <?php foreach($pedidos as $pedido): ?>
                            <div class="pedido-card">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <h6 class="mb-1 text-primary">#<?= $pedido['nro_venta'] ?></h6>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($pedido['fyh_creacion'])) ?>
                                        </small>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Productos (<?= $pedido['total_productos'] ?? 0 ?>):</small>
                                        <p class="mb-0 small text-truncate" title="<?= htmlspecialchars($pedido['productos'] ?? 'No especificado') ?>">
                                            <?= htmlspecialchars($pedido['productos'] ?? 'No especificado') ?>
                                        </p>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <small class="text-muted d-block">Total:</small>
                                        <strong class="text-success fs-5">$<?= number_format($pedido['total_pagado'], 2) ?> MX</strong>
                                    </div>
                                    
                                    <div class="col-md-4 text-end">
                                        <span class="badge bg-success estado-badge mb-2">
                                            <i class="fas fa-check me-1"></i>Completado
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            <?= htmlspecialchars($pedido['nombre_cliente'] ?? 'Cliente') ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
      // ... lo que ya tenías
      noPedidos: "No tienes pedidos aún",
      comenzarComprar: "Comenzar a Comprar",
      perfilListo: "Tu perfil de cliente está listo. Realiza tu primera compra para ver tus pedidos.",
      perfilCrear: "Realiza tu primera compra para crear tu perfil de cliente y ver tus pedidos aquí."
    },
    en: {
      // ... lo que ya tenías
      noPedidos: "You have no orders yet",
      comenzarComprar: "Start Shopping",
      perfilListo: "Your customer profile is ready. Make your first purchase to see your orders.",
      perfilCrear: "Make your first purchase to create your customer profile and view your orders here."
    }
  };

  let currentLang = localStorage.getItem("lang") || "es";
  const btnLanguage = document.getElementById("btnLanguage");

  function applyLanguage(lang) {
    const t = translations[lang];

    // ... resto de cambios ya implementados

    // Empty state
    const emptyState = document.querySelector(".empty-state");
    if (emptyState) {
      emptyState.querySelector("h3").textContent = t.noPedidos;
      const btn = emptyState.querySelector("a.btn-primary");
      if (btn) btn.innerHTML = `<i class="fas fa-store me-2"></i>${t.comenzarComprar}`;

      const p = emptyState.querySelector("p.text-muted");
      if (p) {
        // Detectar si el texto corresponde a perfil listo o perfil crear
        if (p.textContent.includes("Tu perfil de cliente está listo")) {
          p.textContent = t.perfilListo;
        } else {
          p.textContent = t.perfilCrear;
        }
      }
    }

    btnLanguage.innerHTML = lang === "es"
      ? `<i class="fas fa-globe me-1"></i>English`
      : `<i class="fas fa-globe me-1"></i>Español`;
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
    /* Base y tipografía */
    body { background-color:#0b1220 !important; color:#f9fafb !important; }
    h1,h2,h3,h4,h5,h6,strong { color:#f9fafb !important; }
    .text-muted { color:#d1d5db !important; }

    /* Navbar */
    .navbar { background:linear-gradient(135deg,#0f172a,#111827)!important; }
    .navbar-brand span { color:#f9fafb !important; }
    .badge.bg-light.text-dark { background:#374151 !important; color:#f9fafb !important; }
    .nav-link { color:#e5e7eb !important; }
    .nav-link:hover { color:#ffffff !important; background:rgba(255,255,255,0.08) !important; border-radius:8px; }

    /* Tarjetas y secciones */
    .card, .pedido-card {
      background:#111827 !important; color:#f9fafb !important;
      box-shadow:0 8px 30px rgba(0,0,0,0.6) !important; border-color:#1f2937 !important;
    }
    .pedido-card:hover { box-shadow:0 10px 36px rgba(0,0,0,0.7) !important; }

    /* Alertas informativas */
    .alert-info {
      background:#0f172a !important; color:#e5e7eb !important; border-color:#1f2937 !important;
    }
    .info-alert { border-left:4px solid #0ea5e9 !important; }
    .alert-info h6 { color:#f9fafb !important; }

    /* Empty state */
    .empty-state { color:#d1d5db !important; }
    .empty-state i { color:#64748b !important; }

    /* Estadísticas rápidas (cards con contextual bg) */
    .card.bg-primary.text-white {
      background:#1d4ed8 !important; color:#f8fafc !important; border:none !important;
    }
    .card.bg-success.text-white {
      background:#16a34a !important; color:#f8fafc !important; border:none !important;
    }
    .card.bg-info.text-white {
      background:#0ea5e9 !important; color:#f8fafc !important; border:none !important;
    }
    .card.bg-warning.text-white {
      background:#f59e0b !important; color:#111827 !important; border:none !important;
    }
    .card.bg-primary .card-body small,
    .card.bg-success .card-body small,
    .card.bg-info .card-body small { color:#f3f4f6 !important; }
    .card.bg-warning .card-body small { color:#111827 !important; }

    /* Historial título */
    h5.text-muted { color:#e5e7eb !important; }

    /* Bloques de totales y cliente en cada pedido */
    .col-md-2 small.text-muted { color:#d1d5db !important; }
    .col-md-2 strong.text-success { color:#34d399 !important; }
    .col-md-4.text-end small.text-muted { color:#d1d5db !important; }

    /* Estado del pedido */
    .estado-badge {
      background:linear-gradient(135deg,#22c55e,#16a34a) !important; color:#0b1220 !important;
      box-shadow:0 2px 10px rgba(22,163,74,0.25) !important;
    }

    /* Botones */
    .btn-primary {
      background:linear-gradient(135deg,#22c55e,#16a34a) !important; color:#0b1220 !important; border:none !important;
      box-shadow:0 6px 20px rgba(34,197,94,0.35) !important;
    }
    .btn-primary:hover {
      background:linear-gradient(135deg,#16a34a,#22c55e) !important;
    }
    .btn-outline-primary {
      border-color:#22c55e !important; color:#22c55e !important;
    }
    .btn-outline-primary:hover {
      background:#22c55e !important; color:#0b1220 !important;
    }
    .btn-outline-secondary {
      border-color:#9ca3af !important; color:#e5e7eb !important;
    }
    .btn-outline-secondary:hover {
      background:#9ca3af !important; color:#0b1220 !important;
    }

    /* Badge clara */
    .badge.bg-light { background:#374151 !important; color:#f9fafb !important; }
  `;

  function isDarkEnabled() {
    return !!document.getElementById(DARK_STYLE_ID);
  }

  function enableDark() {
    if (isDarkEnabled()) return;
    const style = document.createElement("style");
    style.id = DARK_STYLE_ID;
    style.textContent = darkCSS;
    document.head.appendChild(style);
    if (btn) btn.innerHTML = '<i class="fas fa-sun me-1"></i>Light';
    localStorage.setItem("theme", "dark");
  }

  function disableDark() {
    const style = document.getElementById(DARK_STYLE_ID);
    if (style) style.remove();
    if (btn) btn.innerHTML = '<i class="fas fa-moon me-1"></i>Dark';
    localStorage.setItem("theme", "light");
  }

  const pref = localStorage.getItem("theme") || "light";
  if (pref === "dark") enableDark(); else disableDark();

  if (btn) {
    btn.addEventListener("click", () => {
      if (isDarkEnabled()) disableDark(); else enableDark();
    });
  }
});
</script>

</body>
</html>