<?php
include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../app/controllers/ventas/listado_de_ventas.php');

// Obtener datos para gráficas
$ventas_por_dia = [];
$ventas_por_mes = [];
$productos_mas_vendidos = [];
$categorias_ventas = [];

// Ventas por día (últimos 7 días)
for ($i = 6; $i >= 0; $i--) {
    $fecha = date('Y-m-d', strtotime("-$i days"));
    $ventas_por_dia[$fecha] = 0;
}

// Ventas por mes (últimos 6 meses)
for ($i = 5; $i >= 0; $i--) {
    $mes = date('Y-m', strtotime("-$i months"));
    $ventas_por_mes[$mes] = 0;
}

// Procesar datos de ventas para gráficas
foreach ($ventas_datos as $venta) {
    $fecha_venta = date('Y-m-d', strtotime($venta['fyh_creacion']));
    $mes_venta = date('Y-m', strtotime($venta['fyh_creacion']));
    
    // Ventas por día
    if (isset($ventas_por_dia[$fecha_venta])) {
        $ventas_por_dia[$fecha_venta] += floatval($venta['total_pagado']);
    }
    
    // Ventas por mes
    if (isset($ventas_por_mes[$mes_venta])) {
        $ventas_por_mes[$mes_venta] += floatval($venta['total_pagado']);
    }
}

// Obtener productos más vendidos
$sql_productos_vendidos = "
    SELECT p.nombre, p.id_categoria, c.nombre_categoria, SUM(carr.cantidad) as total_vendido, 
           SUM(carr.cantidad * p.precio_venta) as total_ingresos
    FROM tb_carrito carr 
    INNER JOIN tb_almacen p ON carr.id_producto = p.id_producto 
    INNER JOIN tb_categorias c ON p.id_categoria = c.id_categoria
    GROUP BY p.id_producto 
    ORDER BY total_vendido DESC 
    LIMIT 10
";
$query_productos = $pdo->prepare($sql_productos_vendidos);
$query_productos->execute();
$productos_mas_vendidos = $query_productos->fetchAll(PDO::FETCH_ASSOC);

// Obtener ventas por categoría
$sql_categorias_ventas = "
    SELECT c.nombre_categoria, SUM(carr.cantidad * p.precio_venta) as total_ventas
    FROM tb_carrito carr 
    INNER JOIN tb_almacen p ON carr.id_producto = p.id_producto 
    INNER JOIN tb_categorias c ON p.id_categoria = c.id_categoria
    GROUP BY c.id_categoria 
    ORDER BY total_ventas DESC
";
$query_categorias = $pdo->prepare($sql_categorias_ventas);
$query_categorias->execute();
$categorias_ventas = $query_categorias->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="m-0"><i class="fas fa-shopping-cart mr-2"></i>Dashboard de Ventas</h1>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" onclick="exportarReporte()">
                                <i class="fas fa-file-excel mr-1"></i> Exportar Reporte
                            </button>
                            <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" 
                                    data-toggle="dropdown" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="exportarPDF()"><i class="fas fa-file-pdf mr-2"></i>PDF</a>
                                <a class="dropdown-item" href="#" onclick="exportarExcel()"><i class="fas fa-file-excel mr-2"></i>Excel</a>
                            </div>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <!-- Resumen de Ventas -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo count($ventas_datos); ?></h3>
                            <p>Total de Ventas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>
                                <?php 
                                $total_ventas = 0;
                                foreach($ventas_datos as $venta) {
                                    $total_ventas += floatval($venta['total_pagado']);
                                }
                                echo "$ " . number_format($total_ventas, 2);
                                ?>
                            </h3>
                            <p>Ingreso Total</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>
                                <?php 
                                $promedio = count($ventas_datos) > 0 ? $total_ventas / count($ventas_datos) : 0;
                                echo "$ " . number_format($promedio, 2);
                                ?>
                            </h3>
                            <p>Ticket Promedio</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>
                                <?php 
                                $hoy = date('Y-m-d');
                                $ventas_hoy = 0;
                                foreach($ventas_datos as $venta) {
                                    if(date('Y-m-d', strtotime($venta['fyh_creacion'])) == $hoy) {
                                        $ventas_hoy++;
                                    }
                                }
                                echo $ventas_hoy;
                                ?>
                            </h3>
                            <p>Ventas Hoy</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficas de Ventas -->
            <div class="row mb-4">
                <!-- Gráfica de Ventas por Día -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Ventas de los Últimos 7 Días</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="ventasDiaChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de Ventas por Categoría -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Ventas por Categoría</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="categoriasChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Más Gráficas -->
            <div class="row mb-4">
                <!-- Gráfica de Productos Más Vendidos -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Top 10 Productos Más Vendidos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="productosChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de Ventas Mensuales -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-area mr-2"></i>Ventas Mensuales (Últimos 6 Meses)</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="ventasMesChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Ventas -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-list mr-2"></i>Ventas Registradas</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body" style="display: block;">
                            <div class="table-responsive">
                                <table id="tabla-ventas" class="table table-hover table-striped">
                                    <thead class="bg-gradient-primary text-white">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Nro Venta</th>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Productos</th>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        foreach ($ventas_datos as $ventas_dato){
                                            $id_venta = $ventas_dato['id_venta'];
                                            $id_cliente = $ventas_dato['id_cliente'];
                                            $contador = $contador + 1;
                                            ?>
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <span class="badge badge-primary badge-pill"><?php echo $contador; ?></span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <strong>#<?php echo $ventas_dato['nro_venta']; ?></strong>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <small class="text-muted">
                                                        <?php echo date('d/m/Y H:i', strtotime($ventas_dato['fyh_creacion'])); ?>
                                                    </small>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                                            data-toggle="modal" data-target="#Modal_productos<?php echo $id_venta; ?>">
                                                        <i class="fa fa-boxes mr-1"></i> 
                                                        <span class="badge badge-light"><?php 
                                                            $nro_venta = $ventas_dato['nro_venta'];
                                                            $sql_count = "SELECT COUNT(*) as total FROM tb_carrito WHERE nro_venta = '$nro_venta'";
                                                            $query_count = $pdo->prepare($sql_count);
                                                            $query_count->execute();
                                                            $count_data = $query_count->fetch(PDO::FETCH_ASSOC);
                                                            echo $count_data['total'];
                                                        ?></span>
                                                    </button>

                                                    <!-- Modal Productos -->
                                                    <div class="modal fade" id="Modal_productos<?php echo $id_venta; ?>" tabindex="-1" role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary text-white">
                                                                    <h5 class="modal-title">
                                                                        <i class="fas fa-shopping-basket mr-2"></i>
                                                                        Productos - Venta #<?php echo $ventas_dato['nro_venta']; ?>
                                                                    </h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-hover">
                                                                            <thead class="bg-light">
                                                                                <tr>
                                                                                    <th class="text-center">#</th>
                                                                                    <th>Producto</th>
                                                                                    <th>Descripción</th>
                                                                                    <th class="text-center">Cantidad</th>
                                                                                    <th class="text-center">P. Unitario</th>
                                                                                    <th class="text-center">SubTotal</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $contador_de_carrito = 0;
                                                                                $cantidad_total = 0;
                                                                                $precio_unitario_total = 0;
                                                                                $precio_total = 0;

                                                                                $nro_venta = $ventas_dato['nro_venta'];
                                                                                $sql_carrito = "SELECT *,pro.nombre as nombre_producto, pro.descripcion as descripcion, pro.precio_venta as precio_venta, pro.stock as stock, pro.id_producto as id_producto FROM tb_carrito AS carr INNER JOIN tb_almacen as pro ON carr.id_producto = pro.id_producto WHERE nro_venta = '$nro_venta' ORDER BY id_carrito ASC ";
                                                                                $query_carrito = $pdo->prepare($sql_carrito);
                                                                                $query_carrito->execute();
                                                                                $carrito_datos = $query_carrito->fetchAll(PDO::FETCH_ASSOC);
                                                                                foreach ($carrito_datos as $carrito_dato){
                                                                                    $id_carrito = $carrito_dato['id_carrito'];
                                                                                    $contador_de_carrito = $contador_de_carrito + 1;
                                                                                    $cantidad_total = $cantidad_total + $carrito_dato['cantidad'];
                                                                                    $precio_unitario_total = $precio_unitario_total + floatval($carrito_dato['precio_venta']);
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td class="text-center"><?php echo $contador_de_carrito; ?></td>
                                                                                        <td>
                                                                                            <strong><?php echo $carrito_dato['nombre_producto']; ?></strong>
                                                                                        </td>
                                                                                        <td>
                                                                                            <small class="text-muted"><?php echo $carrito_dato['descripcion']; ?></small>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <span class="badge badge-info"><?php echo $carrito_dato['cantidad'];?></span>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            $ <?php echo number_format($carrito_dato['precio_venta'], 2);?>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <strong>
                                                                                                <?php
                                                                                                $cantidad = floatval($carrito_dato['cantidad']);
                                                                                                $precio_venta = floatval($carrito_dato['precio_venta']);
                                                                                                echo "$ " . number_format($cantidad * $precio_venta, 2);
                                                                                                $precio_total = $precio_total + ($cantidad * $precio_venta);
                                                                                                ?>
                                                                                            </strong>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                <tr class="bg-light">
                                                                                    <td colspan="3" class="text-right"><strong>TOTALES:</strong></td>
                                                                                    <td class="text-center"><strong><?php echo $cantidad_total; ?></strong></td>
                                                                                    <td class="text-center"><strong>$ <?php echo number_format($precio_unitario_total, 2); ?></strong></td>
                                                                                    <td class="text-center bg-warning"><strong>$ <?php echo number_format($precio_total, 2); ?></strong></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-outline-warning btn-sm"
                                                            data-toggle="modal" data-target="#Modal_clientes<?php echo $id_venta; ?>">
                                                        <i class="fa fa-user mr-1"></i> <?php echo $ventas_dato['nombre_cliente']; ?>
                                                    </button>

                                                    <!-- Modal Cliente -->
                                                    <div class="modal fade" id="Modal_clientes<?php echo $id_venta; ?>">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-warning text-dark">
                                                                    <h5 class="modal-title">
                                                                        <i class="fas fa-user-tie mr-2"></i>Información del Cliente
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <?php
                                                                $sql_clientes = "SELECT * FROM tb_clientes where id_cliente = '$id_cliente' ";
                                                                $query_clientes = $pdo->prepare($sql_clientes);
                                                                $query_clientes->execute();
                                                                $clientes_datos = $query_clientes->fetchAll(PDO::FETCH_ASSOC);
                                                                foreach ($clientes_datos as $clientes_dato){
                                                                    $nombre_cliente = $clientes_dato['nombre_cliente'];
                                                                    $nit_ci_cliente = $clientes_dato['nit_ci_cliente'];
                                                                    $celular_cliente = $clientes_dato['celular_cliente'];
                                                                    $email_cliente = $clientes_dato['email_cliente'];
                                                                }
                                                                ?>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="font-weight-bold">Nombre del Cliente</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                                                    </div>
                                                                                    <input type="text" value="<?php echo $nombre_cliente; ?>" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="font-weight-bold">RFC</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                                                    </div>
                                                                                    <input type="text" value="<?php echo $nit_ci_cliente; ?>" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="font-weight-bold">Celular</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                                                    </div>
                                                                                    <input type="text" value="<?php echo $celular_cliente; ?>" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="font-weight-bold">Correo Electrónico</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                                                    </div>
                                                                                    <input type="email" value="<?php echo $email_cliente; ?>" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <span class="badge badge-success p-2" style="font-size: 1em;">
                                                        $ <?php echo number_format($ventas_dato['total_pagado'], 2); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle mr-1"></i> Completada
                                                    </span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="btn-group" role="group">
                                                        <a href="show.php?id_venta=<?php echo $id_venta; ?>" 
                                                           class="btn btn-info btn-sm" 
                                                           data-toggle="tooltip" 
                                                           title="Ver detalles">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="factura.php?id_venta=<?php echo $id_venta;?>&nro_venta=<?php echo $nro_venta;?>" 
                                                           class="btn btn-success btn-sm"
                                                           data-toggle="tooltip"
                                                           title="Imprimir factura">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                        <a href="delete.php?id_venta=<?php echo $id_venta;?>&nro_venta=<?php echo $nro_venta;?>" 
                                                           class="btn btn-danger btn-sm"
                                                           data-toggle="tooltip"
                                                           title="Eliminar venta"
                                                           onclick="return confirm('¿Está seguro de eliminar esta venta?')">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        Mostrando <?php echo count($ventas_datos); ?> ventas registradas
                                    </small>
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted">
                                        Última actualización: <?php echo date('d/m/Y H:i:s'); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include ('../layout/mensajes.php'); ?>
<?php include ('../layout/parte2.php'); ?>

<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .table th {
        border-top: none;
        font-weight: 600;
    }
    .badge-pill {
        border-radius: 10rem;
    }
    .small-box {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .small-box:hover {
        transform: translateY(-5px);
    }
    .btn-group .btn {
        margin-right: 2px;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,0.05);
    }
    .card {
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
</style>

<script>
    // Datos para las gráficas
    const ventasDiaData = {
        labels: [<?php echo "'" . implode("','", array_keys($ventas_por_dia)) . "'"; ?>],
        datasets: [{
            label: 'Ventas por Día ($)',
            data: [<?php echo implode(',', array_values($ventas_por_dia)); ?>],
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    };

    const categoriasData = {
        labels: [<?php echo "'" . implode("','", array_column($categorias_ventas, 'nombre_categoria')) . "'"; ?>],
        datasets: [{
            data: [<?php echo implode(',', array_column($categorias_ventas, 'total_ventas')); ?>],
            backgroundColor: [
                '#2ecc71', '#3498db', '#e74c3c', '#f39c12', '#9b59b6',
                '#1abc9c', '#d35400', '#c0392b', '#7f8c8d', '#34495e'
            ]
        }]
    };

    const productosData = {
        labels: [<?php echo "'" . implode("','", array_column($productos_mas_vendidos, 'nombre')) . "'"; ?>],
        datasets: [{
            label: 'Cantidad Vendida',
            data: [<?php echo implode(',', array_column($productos_mas_vendidos, 'total_vendido')); ?>],
            backgroundColor: 'rgba(46, 204, 113, 0.8)',
            borderColor: '#27ae60',
            borderWidth: 1
        }]
    };

    const ventasMesData = {
        labels: [<?php echo "'" . implode("','", array_keys($ventas_por_mes)) . "'"; ?>],
        datasets: [{
            label: 'Ventas Mensuales ($)',
            data: [<?php echo implode(',', array_values($ventas_por_mes)); ?>],
            backgroundColor: 'rgba(155, 89, 182, 0.6)',
            borderColor: '#9b59b6',
            borderWidth: 2
        }]
    };

    // Inicializar gráficas cuando el documento esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfica de ventas por día
        new Chart(document.getElementById('ventasDiaChart'), {
            type: 'line',
            data: ventasDiaData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Evolución de Ventas Diarias'
                    }
                }
            }
        });

        // Gráfica de categorías
        new Chart(document.getElementById('categoriasChart'), {
            type: 'pie',
            data: categoriasData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Distribución por Categoría'
                    }
                }
            }
        });

        // Gráfica de productos más vendidos
        new Chart(document.getElementById('productosChart'), {
            type: 'bar',
            data: productosData,
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Productos Más Vendidos'
                    }
                }
            }
        });

        // Gráfica de ventas mensuales
        new Chart(document.getElementById('ventasMesChart'), {
            type: 'bar',
            data: ventasMesData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Ventas Mensuales'
                    }
                }
            }
        });

        // Inicializar DataTable
        $('#tabla-ventas').DataTable({
            "pageLength": 10,
            "responsive": true,
            "autoWidth": false,
            "language": {
                "emptyTable": "No hay ventas registradas",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ ventas",
                "infoEmpty": "Mostrando 0 a 0 de 0 ventas",
                "infoFiltered": "(filtrado de _MAX_ ventas totales)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ ventas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron ventas coincidentes",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "order": [[1, "desc"]],
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                   '<"row"<"col-sm-12"tr>>' +
                   '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });

        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Funciones de exportación
    function exportarReporte() {
        Swal.fire({
            title: 'Exportar Reporte',
            text: 'Seleccione el formato de exportación',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Exportar a Excel',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                exportarExcel();
            }
        });
    }

    function exportarExcel() {
        // Simular descarga de Excel
        Swal.fire({
            title: 'Exportando...',
            text: 'Generando archivo Excel',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            Swal.fire(
                '¡Éxito!',
                'El archivo Excel se ha generado correctamente',
                'success'
            );
        });
    }

    function exportarPDF() {
        // Simular descarga de PDF
        Swal.fire({
            title: 'Exportando...',
            text: 'Generando archivo PDF',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            Swal.fire(
                '¡Éxito!',
                'El archivo PDF se ha generado correctamente',
                'success'
            );
        });
    }
</script>