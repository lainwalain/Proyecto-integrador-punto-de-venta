<?php
include ('app/config.php');
include ('layout/sesion.php');

include ('layout/parte1.php');
include ('app/controllers/usuarios/listado_de_usuarios.php');
include ('app/controllers/roles/listado_de_roles.php');
include ('app/controllers/categorias/listado_de_categoria.php');
include ('app/controllers/almacen/listado_de_productos.php');
include ('app/controllers/proveedores/listado_de_proveedores.php');
include ('app/controllers/compras/listado_de_compras.php');
include ('app/controllers/ventas/listado_de_ventas.php');
include ('app/controllers/clientes/listado_de_clientes.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Market Go te da la bienvenida - <?php echo $rol_sesion; ?> </h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            Contenido del sistema
            <br><br>

            <div class="row">


                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <?php
                            $contador_de_usuarios = 0;
                            foreach ($usuarios_datos as $usuarios_dato){
                                $contador_de_usuarios = $contador_de_usuarios + 1;
                            }
                            ?>
                            <h3><?php echo $contador_de_usuarios;?></h3>
                            <p>Usuarios Registrados</p>
                        </div>
                        <a href="<?php echo $URL;?>/usuarios/create.php">
                            <div class="icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </a>
                        <a href="<?php echo $URL;?>/usuarios" class="small-box-footer">
                            Más detalle <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>


                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <?php
                            $contador_de_roles = 0;
                            foreach ($roles_datos as $roles_dato){
                                $contador_de_roles = $contador_de_roles + 1;
                            }
                            ?>
                            <h3><?php echo $contador_de_roles;?></h3>
                            <p>Roles Registrados</p>
                        </div>
                        <a href="<?php echo $URL;?>/roles/create.php">
                            <div class="icon">
                                <i class="fas fa-address-card"></i>
                            </div>
                        </a>
                        <a href="<?php echo $URL;?>/roles" class="small-box-footer">
                            Más detalle <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>


                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <?php
                            $contador_de_categorias = 0;
                            foreach ($categorias_datos as $categorias_dato){
                                $contador_de_categorias = $contador_de_categorias + 1;
                            }
                            ?>
                            <h3><?php echo $contador_de_categorias;?></h3>
                            <p>Categorías Registrados</p>
                        </div>
                        <a href="<?php echo $URL;?>/categorias">
                            <div class="icon">
                                <i class="fas fa-tags"></i>
                            </div>
                        </a>
                        <a href="<?php echo $URL;?>/categorias" class="small-box-footer">
                            Más detalle <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>


                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <?php
                            $contador_de_productos = 0;
                            foreach ($productos_datos as $productos_dato){
                                $contador_de_productos = $contador_de_productos + 1;
                            }
                            ?>
                            <h3><?php echo $contador_de_productos;?></h3>
                            <p>Productos Registrados</p>
                        </div>
                        <a href="<?php echo $URL;?>/almacen/create.php">
                            <div class="icon">
                                <i class="fas fa-list"></i>
                            </div>
                        </a>
                        <a href="<?php echo $URL;?>/almacen" class="small-box-footer">
                            Más detalle <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>





                <div class="col-lg-3 col-6">
                    <div class="small-box bg-dark">
                        <div class="inner">
                            <?php
                            $contador_de_proveedores = 0;
                            foreach ($proveedores_datos as $proveedores_dato){
                                $contador_de_proveedores = $contador_de_proveedores + 1;
                            }
                            ?>
                            <h3><?php echo $contador_de_proveedores;?></h3>
                            <p>Proveedores Registrados</p>
                        </div>
                        <a href="<?php echo $URL;?>/proveedores">
                            <div class="icon">
                                <i class="fas fa-car"></i>
                            </div>
                        </a>
                        <a href="<?php echo $URL;?>/proveedores" class="small-box-footer">
                            Más detalle <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>




                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <?php
                            $contador_de_compras = 0;
                            foreach ($compras_datos as $compras_dato){
                                $contador_de_compras = $contador_de_compras + 1;
                            }
                            ?>
                            <h3><?php echo $contador_de_compras;?></h3>
                            <p>Compras Registradas</p>
                        </div>
                        <a href="<?php echo $URL;?>/compras">
                            <div class="icon">
                                <i class="fas fa-cart-plus"></i>
                            </div>
                        </a>
                        <a href="<?php echo $URL;?>/compras" class="small-box-footer">
                            Más detalle <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>




                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <?php
                            $contador_de_ventas = 0;
                            foreach ($ventas_datos as $ventas_dato){
                                $contador_de_ventas = $contador_de_ventas + 1;
                            }
                            ?>
                            <h3><?php echo $contador_de_ventas;?></h3>
                            <p>Ventas Registradas</p>
                        </div>
                        <a href="<?php echo $URL;?>/ventas">
                            <div class="icon">
                                <i class="fas fa-shopping-basket"></i>
                            </div>
                        </a>
                        <a href="<?php echo $URL;?>/ventas" class="small-box-footer">
                            Más detalle <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>



                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <?php
                            $contador_de_clientes = 0;
                            foreach ($clientes_datos as $clientes_dato){
                                $contador_de_clientes = $contador_de_clientes + 1;
                            }
                            ?>
                            <h3><?php echo $contador_de_clientes;?></h3>
                            <p>Clientes Registradas</p>
                        </div>
                        <a href="<?php echo $URL;?>/clientes">
                            <div class="icon">
                                <i class="fas fa-user-friends"></i>
                            </div>
                        </a>
                        <a href="<?php echo $URL;?>/clientes" class="small-box-footer">
                            Más detalle <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>


            </div>

            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->


<!-- /.content-wrapper -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  const translations = {
    es: {
      bienvenida: "Market Go te da la bienvenida",
      usuarios: "Usuarios Registrados",
      roles: "Roles Registrados",
      categorias: "Categorías Registradas",
      productos: "Productos Registrados",
      proveedores: "Proveedores Registrados",
      compras: "Compras Registradas",
      ventas: "Ventas Registradas",
      clientes: "Clientes Registrados",
      detalle: "Más detalle"
    },
    en: {
      bienvenida: "Market Go welcomes you",
      usuarios: "Registered Users",
      roles: "Registered Roles",
      categorias: "Registered Categories",
      productos: "Registered Products",
      proveedores: "Registered Suppliers",
      compras: "Registered Purchases",
      ventas: "Registered Sales",
      clientes: "Registered Customers",
      detalle: "More details"
    }
  };

  let currentLang = localStorage.getItem("lang") || "es";
  const btnLanguage = document.getElementById("btnLanguage");

  function applyLanguage(lang) {
    const t = translations[lang];

    // Encabezado
    const header = document.querySelector(".content-header h1");
    if (header) {
      const rol = header.innerHTML.split("-")[1] || "";
      header.innerHTML = `${t.bienvenida} - ${rol.trim()}`;
    }

    // Cajas
    const boxes = document.querySelectorAll(".small-box");
    boxes.forEach(box => {
      const p = box.querySelector(".inner p");
      if (p) {
        if (p.textContent.includes("Usuarios")) p.textContent = t.usuarios;
        if (p.textContent.includes("Roles")) p.textContent = t.roles;
        if (p.textContent.includes("Categorías")) p.textContent = t.categorias;
        if (p.textContent.includes("Productos")) p.textContent = t.productos;
        if (p.textContent.includes("Proveedores")) p.textContent = t.proveedores;
        if (p.textContent.includes("Compras")) p.textContent = t.compras;
        if (p.textContent.includes("Ventas")) p.textContent = t.ventas;
        if (p.textContent.includes("Clientes")) p.textContent = t.clientes;
      }
      const footer = box.querySelector(".small-box-footer");
      if (footer) footer.innerHTML = `${t.detalle} <i class="fas fa-arrow-circle-right"></i>`;
    });

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
    html, body {
      height:100% !important;
      background-color:#0b1220 !important;
      color:#f9fafb !important;
    }
    .content-wrapper, .content, .container-fluid {
      min-height:100% !important;
      background-color:#0b1220 !important;
      color:#f9fafb !important;
    }
    h1,h2,h3,h4,h5,h6,strong { color:#f9fafb !important; }
    .text-muted { color:#d1d5db !important; }

    .navbar, .content-header {
      background:#0f172a !important;
      color:#f9fafb !important;
    }

    .card, .small-box {
      background:#111827 !important;
      color:#f9fafb !important;
      box-shadow:0 8px 30px rgba(0,0,0,0.6) !important;
      border-color:#1f2937 !important;
    }

    .small-box-footer {
      background:#1f2937 !important;
      color:#93c5fd !important;
    }
    .small-box-footer:hover { color:#ffffff !important; }

    /* Colores contextuales */
    .small-box.bg-warning { background:#f59e0b !important; color:#111827 !important; }
    .small-box.bg-info    { background:#0ea5e9 !important; color:#f8fafc !important; }
    .small-box.bg-success { background:#16a34a !important; color:#f8fafc !important; }
    .small-box.bg-primary { background:#1d4ed8 !important; color:#f8fafc !important; }
    .small-box.bg-dark    { background:#374151 !important; color:#f9fafb !important; }
    .small-box.bg-danger  { background:#dc2626 !important; color:#f8fafc !important; }

    footer, .main-footer {
      background-color:#0b1220 !important;
      color:#f9fafb !important;
      border-top:1px solid #1f2937 !important;
    }
  `;

  function isDarkEnabled() { return !!document.getElementById(DARK_STYLE_ID); }
  function enableDark() {
    if (isDarkEnabled()) return;
    const style = document.createElement("style");
    style.id = DARK_STYLE_ID;
    style.textContent = darkCSS;
    document.head.appendChild(style);
    if (btn) btn.innerHTML = '<i class="fas fa-sun me-1"></i>Light';
    localStorage.setItem("theme","dark");
  }
  function disableDark() {
    const style = document.getElementById(DARK_STYLE_ID);
    if (style) style.remove();
    if (btn) btn.innerHTML = '<i class="fas fa-moon me-1"></i>Dark';
    localStorage.setItem("theme","light");
  }

  const pref = localStorage.getItem("theme") || "light";
  if (pref==="dark") enableDark(); else disableDark();

  if (btn) {
    btn.addEventListener("click", () => {
      if (isDarkEnabled()) disableDark(); else enableDark();
    });
  }
});
</script>

<?php include   ('layout/parte2.php'); ?>







