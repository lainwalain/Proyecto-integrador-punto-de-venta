<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de ventas</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo $URL;?>/public/templeates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $URL;?>/public/templeates/AdminLTE-3.2.0/dist/css/adminlte.min.css">

    <!-- Libreria Sweetallert2-->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo $URL;?>/public/templeates/AdminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $URL;?>/public/templeates/AdminLTE-3.2.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $URL;?>/public/templeates/AdminLTE-3.2.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- jQuery -->
    <script src="<?php echo $URL;?>/public/templeates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>

    <style>
        /* Estilos para el logo más pequeño */
        .brand-logo-container {
            display: flex;
            justify-content: center;
            padding: 15px 0 10px 0;
            border-bottom: 1px solid #4b545c;
            margin-bottom: 5px;
        }
        .brand-logo {
            max-width: 120px;
            height: auto;
            transition: all 0.3s ease;
        }
        
        /* Ajustes para mejorar la proporción con el logo más pequeño */
        .sidebar {
            padding-top: 0;
        }
        
        .user-panel {
            margin-top: 15px !important;
            padding-bottom: 15px;
            border-bottom: 1px solid #4b545c;
        }
        
        .nav-sidebar > .nav-item {
            margin-bottom: 3px;
        }
        
        .nav-sidebar .nav-link {
            padding: 10px 15px;
            border-radius: 0;
            transition: all 0.2s ease;
        }
        
        .nav-sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .nav-sidebar .nav-link.active {
            background-color: #007bff;
            border-left: 4px solid #fff;
        }
        
        .nav-sidebar .nav-treeview .nav-link {
            padding: 8px 15px 8px 35px;
            background-color: rgba(0,0,0,0.2);
        }
        
        .nav-sidebar .nav-treeview .nav-link:hover {
            background-color: rgba(255,255,255,0.05);
        }
        
        .nav-sidebar .nav-treeview .nav-link.active {
            background-color: rgba(0,123,255,0.7);
        }
        
        /* Ajustar espaciado del botón de cerrar sesión */
        .nav-sidebar .nav-item:last-child {
            margin-top: 15px;
            border-top: 1px solid #4b545c;
            padding-top: 12px;
        }
        
        /* Mejorar la legibilidad del texto */
        .nav-sidebar .nav-link p {
            margin: 0;
            font-size: 14px;
        }
        
        .nav-sidebar .nav-link i.nav-icon {
            font-size: 15px;
            min-width: 25px;
        }
        
        /* Ajustes para pantallas más pequeñas */
        @media (max-width: 767.98px) {
            .brand-logo {
                max-width: 100px;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">

<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">SISTEMA DE VENTAS </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <div class="brand-logo-container">
            <a href="<?php echo $URL;?>" class="brand-link">
                <img src="<?php echo $URL;?>/public/images/Logo2MarketGo.png" alt="Market Go Logo" class="brand-logo">
            </a>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
           <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <div class="img-circle elevation-2 bg-info d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <span class="text-white font-weight-bold"><?php echo substr($nombres_sesion, 0, 1); ?></span>
                    </div>
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?php echo $nombres_sesion;?></a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->

                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Usuarios
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/usuarios" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listado de usuarios</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/usuarios/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Creación de usuario</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-address-card"></i>
                            <p>
                                Roles
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/roles" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listado de roles</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/roles/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Creación de rol</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>
                                Categorías
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/categorias" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listado de categorías</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Almacen
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/almacen" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listado de productos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/almacen/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Creación de productos</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-cart-plus"></i>
                            <p>
                                Compras
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/compras" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listado de compras</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/compras/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Creación de compra</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-car"></i>
                            <p>
                                Proveedores
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/proveedores" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listado de proveedores</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-shopping-basket"></i>
                            <p>
                                Ventas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/ventas" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listado de ventas</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>
                                Clientes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo $URL;?>/clientes" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listado de clientes</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo $URL;?>/app/controllers/login/cerrar_sesion.php" class="nav-link" style="background-color: #ca0a0b">
                            <i class="nav-icon fas fa-door-closed"></i>
                            <p>
                                Cerrar Sesión
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
 <div style="position: fixed; bottom: 20px; left: 20px; z-index: 9999;">
  <button id="btnDarkMode" class="dark-toggle-btn">
    <i class="fas fa-moon me-1"></i>Dark
  </button>
</div>
<style>
.dark-toggle-btn {
  background: linear-gradient(135deg, #1e3a8a, #3b82f6); /* gradiente azul */
  color: #fff;
  border: none;
  border-radius: 30px;
  padding: 10px 18px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  transition: all 0.3s ease;
}

.dark-toggle-btn:hover {
  background: linear-gradient(135deg, #3b82f6, #1e3a8a);
  transform: scale(1.05);
  box-shadow: 0 6px 16px rgba(0,0,0,0.4);
}

.dark-toggle-btn i {
  font-size: 16px;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const btnDark = document.getElementById("btnDarkMode");

  const darkCSS = `
    /* Transición suave */
    html, body, .wrapper, .content-wrapper, .content, .container-fluid,
    .navbar, .main-header, .main-sidebar, .sidebar, .card, .small-box,
    .form-control, .form-select, input, textarea, select,
    footer, .main-footer {
      transition: background-color 0.35s ease, color 0.35s ease, border-color 0.35s ease;
    }

    /* Fondo y texto */
    html.dark-mode, html.dark-mode body {
      background-color:#0b1220 !important; color:#f9fafb !important;
    }
    html.dark-mode h1, html.dark-mode h2, html.dark-mode h3,
    html.dark-mode h4, html.dark-mode h5, html.dark-mode h6, html.dark-mode strong {
      color:#f9fafb !important;
    }
    html.dark-mode p, html.dark-mode label, html.dark-mode .text-muted {
      color:#d1d5db !important;
    }

    /* Navbar y header */
    html.dark-mode .navbar, html.dark-mode .main-header, html.dark-mode .content-header {
      background:#0f172a !important; color:#f9fafb !important;
    }

    /* Sidebar */
    html.dark-mode .main-sidebar, html.dark-mode .sidebar {
      background:#0f172a !important; color:#e5e7eb !important;
    }

    /* Cards y cajas */
    html.dark-mode .card, html.dark-mode .small-box {
      background:#111827 !important; color:#f9fafb !important; border-color:#1f2937 !important;
    }

    /* Formularios */
    html.dark-mode .form-control, html.dark-mode input, html.dark-mode textarea, html.dark-mode select {
      background:#1f2937 !important; color:#f9fafb !important; border:1px solid #374151 !important;
    }

    /* Footer */
    html.dark-mode footer, html.dark-mode .main-footer {
      background:#0b1220 !important; color:#f9fafb !important;
    }

    /* 🔹 Íconos azules en modo oscuro */
    html.dark-mode i, html.dark-mode .fas, html.dark-mode .far, html.dark-mode .fab {
      color:#3b82f6 !important; /* Azul vivo */
    }
  `;

  // Inyectar estilos una sola vez
  let styleTag = document.getElementById("dark-mode-styles");
  if (!styleTag) {
    styleTag = document.createElement("style");
    styleTag.id = "dark-mode-styles";
    styleTag.textContent = darkCSS;
    document.head.appendChild(styleTag);
  }

  // Estado inicial
  const pref = localStorage.getItem("theme");
  if (pref === "dark") {
    document.documentElement.classList.add("dark-mode");
    btnDark.innerHTML = '<i class="fas fa-sun me-1"></i>Light';
  } else {
    document.documentElement.classList.remove("dark-mode");
    btnDark.innerHTML = '<i class="fas fa-moon me-1"></i>Dark';
  }

  // Toggle
  btnDark.addEventListener("click", () => {
    const isDark = document.documentElement.classList.toggle("dark-mode");
    if (isDark) {
      btnDark.innerHTML = '<i class="fas fa-sun me-1"></i>Light';
      localStorage.setItem("theme","dark");
    } else {
      btnDark.innerHTML = '<i class="fas fa-moon me-1"></i>Dark';
      localStorage.setItem("theme","light");
    }
  });
});
</script>
