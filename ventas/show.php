<?php
$id_venta_get = $_GET['id_venta'];
include ('../app/config.php');
include ('../layout/sesion.php');

include ('../layout/parte1.php');
include ('../app/controllers/ventas/cargar_venta.php');
include ('../app/controllers/clientes/cargar_cliente.php');

// Verificar y asignar valor por defecto a $fyh_creacion si no está definida
if (!isset($fyh_creacion)) {
    $fyh_creacion = date('Y-m-d H:i:s');
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="m-0">
                            <i class="fas fa-receipt mr-2"></i>Detalle de la Venta #<?= $nro_venta; ?>
                        </h1>
                        <div class="btn-group">
                            <a href="factura.php?id_venta=<?php echo $id_venta_get;?>&nro_venta=<?php echo $nro_venta;?>" 
                               class="btn btn-success">
                                <i class="fas fa-print mr-1"></i> Imprimir Factura
                            </a>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-shopping-bag mr-2"></i> Detalles de los Productos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-hover table-striped">
                                    <thead class="bg-gradient-primary text-white">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Producto</th>
                                            <th class="text-center">Descripción</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center">Precio Unitario (MXN)</th>
                                            <th class="text-center">SubTotal (MXN)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $contador_de_carrito = 0;
                                    $cantidad_total = 0;
                                    $precio_unitario_total = 0;
                                    $preio_total = 0;

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
                                            <td class="text-center align-middle">
                                                <span class="badge badge-primary badge-pill"><?php echo $contador_de_carrito; ?></span>
                                                <input type="text" value="<?php echo $carrito_dato['id_producto']; ?>" id="id_producto<?php echo $contador_de_carrito; ?>" hidden>
                                            </td>
                                            <td class="align-middle">
                                                <strong><?php echo $carrito_dato['nombre_producto']; ?></strong>
                                            </td>
                                            <td class="align-middle">
                                                <small class="text-muted"><?php echo $carrito_dato['descripcion'] ?: 'Sin descripción'; ?></small>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-info" style="font-size: 1em;"><?php echo $carrito_dato['cantidad'];?></span>
                                                <input type="text" value="<?php echo $carrito_dato['stock'];?>" id="stock_de_inventario<?php echo $contador_de_carrito; ?>" hidden>
                                            </td>
                                            <td class="text-center align-middle">
                                                <strong>$ <?php echo number_format($carrito_dato['precio_venta'], 2);?></strong>
                                            </td>
                                            <td class="text-center align-middle">
                                                <strong>
                                                    <?php
                                                    $cantidad = floatval($carrito_dato['cantidad']);
                                                    $precio_venta = floatval($carrito_dato['precio_venta']);
                                                    $subtotal = $cantidad * $precio_venta;
                                                    echo '$ ' . number_format($subtotal, 2);
                                                    $preio_total = $preio_total + $subtotal;
                                                    ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr class="bg-light">
                                        <th colspan="3" class="text-right">TOTALES</th>
                                        <th class="text-center"><span class="badge badge-primary" style="font-size: 1em;"><?php echo $cantidad_total; ?></span></th>
                                        <th class="text-center"><strong>$ <?php echo number_format($precio_unitario_total, 2); ?></strong></th>
                                        <th class="text-center bg-warning"><strong>$ <?php echo number_format($preio_total, 2); ?></strong></th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-9">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-check mr-2"></i> Información del Cliente</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <?php
                        foreach ($clientes_datos as $clientes_dato)
                        {
                            $nombre_cliente = $clientes_dato['nombre_cliente'];
                            $nit_ci_cliente = $clientes_dato['nit_ci_cliente'];
                            $celular_cliente = $clientes_dato['celular_cliente'];
                            $email_cliente = $clientes_dato['email_cliente'];
                        }
                        ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" id="id_cliente" hidden>
                                        <label for="nombre_cliente" class="font-weight-bold">Nombre del Cliente</label>
                                        <input type="text" value="<?php echo $nombre_cliente; ?>" class="form-control" id="nombre_cliente" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nit_ci_cliente" class="font-weight-bold">RFC/CURP</label>
                                        <input type="text" value="<?php echo $nit_ci_cliente; ?>" class="form-control" id="nit_ci_cliente" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="celular_cliente" class="font-weight-bold">Teléfono/Celular</label>
                                        <input type="text" value="<?php echo $celular_cliente; ?>" class="form-control" id="celular_cliente" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="email_cliente" class="font-weight-bold">Correo Electrónico</label>
                                        <input type="text" value="<?php echo $email_cliente; ?>" class="form-control" id="email_cliente" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cash-register mr-2"></i> Resumen de Pago</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="total_a_cancelar" class="font-weight-bold">Subtotal</label>
                                <input type="text" class="form-control text-right" 
                                       value="$ <?php echo number_format($preio_total / 1.16, 2); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="iva" class="font-weight-bold">IVA (16%)</label>
                                <input type="text" class="form-control text-right" 
                                       value="$ <?php echo number_format($preio_total - ($preio_total / 1.16), 2); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="total_a_cancelar" class="font-weight-bold">Total a Pagar (MXN)</label>
                                <input type="text" class="form-control text-right bg-warning font-weight-bold" 
                                       value="$ <?php echo number_format($preio_total, 2); ?>" disabled>
                            </div>
                            <div class="text-center mt-3">
                                <span class="badge badge-success p-2">
                                    <i class="fas fa-check-circle mr-1"></i> Venta Completada
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Información Adicional</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Número de Venta:</strong> #<?php echo $nro_venta; ?></p>
                                    <p><strong>Fecha y Hora:</strong> <?php echo date('d/m/Y H:i:s', strtotime($fyh_creacion)); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Atendido por:</strong> <?php echo $nombres_sesion ?? 'Sistema'; ?></p>
                                    <p><strong>Estado:</strong> <span class="badge badge-success">Completada</span></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Ubicación:</strong> Manzanillo, Colima, México</p>
                                    <p><strong>Sistema:</strong> Market Go</p>
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

<style>
    .badge-pill {
        border-radius: 10rem;
    }
    .table th {
        border-top: none;
        font-weight: 600;
    }
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .form-control:disabled {
        background-color: #f8f9fa;
        color: #495057;
    }
</style>

<script>
    $(function () {
        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<!-- Modal para agregar cliente -->
<div class="modal fade" id="modal-agregar_cliente">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #b6900c;color: white">
                <h4 class="modal-title"><i class="fas fa-user-plus mr-2"></i>Nuevo Cliente</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../app/controllers/clientes/guardar_clientes.php" method="post">
                    <div class="form-group">
                        <label for="">Nombre del Cliente</label>
                        <input type="text" name="nombre_cliente" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">RFC/CURP</label>
                        <input type="text" name="nit_ci_cliente" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Teléfono/Celular</label>
                        <input type="text" name="celular_cliente" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Correo Electrónico</label>
                        <input type="email" name="email_cliente" class="form-control">
                    </div>
                    <hr>
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning btn-block">
                            <i class="fas fa-save mr-1"></i> Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
