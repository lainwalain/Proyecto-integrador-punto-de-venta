<?php
include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../app/controllers/ventas/listado_de_ventas.php');
include ('../app/controllers/almacen/listado_de_productos.php');
include ('../app/controllers/clientes/listado_de_clientes.php');

// Calcular número de venta
$numero_venta = count($ventas_datos) + 1;
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0"><i class="fas fa-cash-register mr-2"></i>Nueva Venta</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <!-- Información de la Venta -->
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle mr-2"></i>Venta #<?php echo $numero_venta; ?></h5>
                <p class="mb-0">Complete los siguientes pasos para registrar la venta</p>
            </div>

            <div class="row">
                <!-- Paso 1: Productos -->
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-shopping-cart mr-2"></i>Paso 1: Agregar Productos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Productos en el carrito</h5>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-buscar_producto">
                                    <i class="fas fa-search mr-1"></i> Buscar Producto
                                </button>
                            </div>

                            <!-- Carrito de productos -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th width="30%">Producto</th>
                                            <th width="20%">Descripción</th>
                                            <th width="10%" class="text-center">Cantidad</th>
                                            <th width="15%" class="text-center">Precio Unitario</th>
                                            <th width="15%" class="text-center">Subtotal</th>
                                            <th width="5%" class="text-center">Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador_de_carrito = 0;
                                        $cantidad_total = 0;
                                        $precio_unitario_total = 0;
                                        $precio_total = 0;

                                        $nro_venta = $numero_venta;
                                        $sql_carrito = "SELECT *,pro.nombre as nombre_producto, pro.descripcion as descripcion, 
                                                       pro.precio_venta as precio_venta, pro.stock as stock, pro.id_producto as id_producto 
                                                       FROM tb_carrito AS carr 
                                                       INNER JOIN tb_almacen as pro ON carr.id_producto = pro.id_producto 
                                                       WHERE nro_venta = '$nro_venta' ORDER BY id_carrito ASC ";
                                        $query_carrito = $pdo->prepare($sql_carrito);
                                        $query_carrito->execute();
                                        $carrito_datos = $query_carrito->fetchAll(PDO::FETCH_ASSOC);
                                        
                                        foreach ($carrito_datos as $carrito_dato){
                                            $id_carrito = $carrito_dato['id_carrito'];
                                            $contador_de_carrito = $contador_de_carrito + 1;
                                            $cantidad_total = $cantidad_total + $carrito_dato['cantidad'];
                                            $precio_unitario_total = $precio_unitario_total + floatval($carrito_dato['precio_venta']);
                                            $subtotal = floatval($carrito_dato['cantidad']) * floatval($carrito_dato['precio_venta']);
                                            $precio_total = $precio_total + $subtotal;
                                            ?>
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <span class="badge badge-primary"><?php echo $contador_de_carrito; ?></span>
                                                </td>
                                                <td class="align-middle">
                                                    <strong><?php echo $carrito_dato['nombre_producto']; ?></strong>
                                                </td>
                                                <td class="align-middle">
                                                    <small class="text-muted"><?php echo $carrito_dato['descripcion']; ?></small>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <span class="badge badge-info"><?php echo $carrito_dato['cantidad'];?></span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    $ <?php echo number_format($carrito_dato['precio_venta'], 2);?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <strong>$ <?php echo number_format($subtotal, 2); ?></strong>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <form action="../app/controllers/ventas/borrar_carrito.php" method="post" class="d-inline">
                                                        <input type="hidden" name="id_carrito" value="<?php echo $id_carrito; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Quitar producto">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        
                                        if($contador_de_carrito == 0): ?>
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">
                                                    <i class="fas fa-shopping-cart fa-2x mb-3"></i>
                                                    <p>No hay productos en el carrito</p>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-buscar_producto">
                                                        <i class="fas fa-plus mr-1"></i> Agregar Primer Producto
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <?php if($contador_de_carrito > 0): ?>
                                    <tfoot class="bg-light">
                                        <tr>
                                            <th colspan="3" class="text-right">Total:</th>
                                            <th class="text-center"><?php echo $cantidad_total; ?></th>
                                            <th class="text-center">$ <?php echo number_format($precio_unitario_total, 2); ?></th>
                                            <th class="text-center bg-warning">$ <?php echo number_format($precio_total, 2); ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Paso 2: Datos del Cliente -->
                <div class="col-md-8">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user mr-2"></i>Paso 2: Datos del Cliente</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Información del cliente</h5>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-buscar_cliente">
                                    <i class="fas fa-search mr-1"></i> Buscar Cliente
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Nombre del cliente</label>
                                        <input type="text" class="form-control" id="nombre_cliente" placeholder="Seleccione un cliente">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Teléfono/Celular</label>
                                        <input type="text" class="form-control" id="celular_cliente" placeholder="Número de contacto">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">RFC o Identificación</label>
                                        <input type="text" class="form-control" id="nit_ci_cliente" placeholder="Opcional">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Correo electrónico</label>
                                        <input type="text" class="form-control" id="email_cliente" placeholder="Opcional">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="id_cliente">
                        </div>
                    </div>
                </div>

                <!-- Paso 3: Pago -->
                <div class="col-md-4">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-credit-card mr-2"></i>Paso 3: Pago</h3>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label class="font-weight-bold">Total a Pagar</label>
                                <input type="text" class="form-control form-control-lg text-center font-weight-bold bg-light" 
                                       value="$ <?php echo number_format($precio_total, 2); ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Efectivo Recibido</label>
                                <input type="number" class="form-control" id="total_pagado" placeholder="0.00" min="0" step="0.01">
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Cambio</label>
                                <input type="text" class="form-control bg-light font-weight-bold text-success" id="cambio" value="$ 0.00" disabled>
                            </div>

                            <hr>

                            <button id="btn_guardar_venta" class="btn btn-success btn-lg btn-block" 
                                    <?php echo ($contador_de_carrito == 0) ? 'disabled' : ''; ?>>
                                <i class="fas fa-check-circle mr-2"></i>
                                FINALIZAR VENTA
                            </button>
                            <div id="respuesta_registro_venta" class="mt-2 text-center"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include ('../layout/mensajes.php'); ?>
<?php include ('../layout/parte2.php'); ?>

<!-- Modal Buscar Producto -->
<div class="modal fade" id="modal-buscar_producto">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title"><i class="fas fa-search mr-2"></i>Buscar Producto</h4>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tabla-productos" class="table table-hover table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Acción</th>
                                <th width="10%">Código</th>
                                <th width="15%">Categoría</th>
                                <th width="10%">Imagen</th>
                                <th width="20%">Nombre</th>
                                <th width="15%">Descripción</th>
                                <th width="8%" class="text-center">Stock</th>
                                <th width="12%" class="text-center">Precio Venta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 0;
                            foreach ($productos_datos as $productos_dato){
                                $id_producto = $productos_dato['id_producto']; 
                                $contador++;
                                ?>
                                <tr>
                                    <td class="align-middle"><?php echo $contador; ?></td>
                                    <td class="align-middle">
                                        <button class="btn btn-primary btn-sm btn-seleccionar-producto" 
                                                data-id="<?php echo $id_producto; ?>"
                                                data-nombre="<?php echo $productos_dato['nombre']; ?>"
                                                data-descripcion="<?php echo $productos_dato['descripcion']; ?>"
                                                data-precio="<?php echo $productos_dato['precio_venta']; ?>">
                                            <i class="fas fa-cart-plus mr-1"></i> Seleccionar
                                        </button>
                                    </td>
                                    <td class="align-middle">
                                        <code><?php echo $productos_dato['codigo'];?></code>
                                    </td>
                                    <td class="align-middle"><?php echo $productos_dato['categoria'];?></td>
                                    <td class="align-middle">
                                        <img src="<?php echo $URL."/almacen/img_productos/".$productos_dato['imagen'];?>" 
                                             width="50" class="img-thumbnail" alt="<?php echo $productos_dato['nombre'];?>">
                                    </td>
                                    <td class="align-middle">
                                        <strong><?php echo $productos_dato['nombre'];?></strong>
                                    </td>
                                    <td class="align-middle">
                                        <small class="text-muted"><?php echo $productos_dato['descripcion'];?></small>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge <?php echo ($productos_dato['stock'] > 0) ? 'badge-success' : 'badge-danger'; ?>">
                                            <?php echo $productos_dato['stock'];?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <strong>$ <?php echo number_format($productos_dato['precio_venta'], 2);?></strong>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Formulario para agregar producto -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-cart-plus mr-2"></i>Agregar al Carrito</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Producto</label>
                                    <input type="text" id="producto" class="form-control" readonly placeholder="Seleccione un producto">
                                    <input type="hidden" id="id_producto">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Descripción</label>
                                    <input type="text" id="descripcion" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="font-weight-bold">Cantidad</label>
                                    <input type="number" id="cantidad" class="form-control" min="1" value="1">
                                    <div id="respuesta_validad_cantidad_stock" class="small text-danger mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Precio Unitario</label>
                                    <input type="text" id="precio_venta" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button id="btn_registrar_carrito" class="btn btn-success">
                                <i class="fas fa-plus-circle mr-1"></i> Agregar al Carrito
                            </button>
                            <div id="respuesta_carrito" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buscar Cliente -->
<div class="modal fade" id="modal-buscar_cliente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h4 class="modal-title"><i class="fas fa-users mr-2"></i>Buscar Cliente</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-right mb-3">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-agregar_cliente">
                        <i class="fas fa-user-plus mr-1"></i> Nuevo Cliente
                    </button>
                </div>
                <div class="table-responsive">
                    <table id="tabla-clientes" class="table table-hover table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th width="10%">#</th>
                                <th width="20%">Acción</th>
                                <th width="30%">Nombre del Cliente</th>
                                <th width="15%" class="text-center">Identificación</th>
                                <th width="15%" class="text-center">Teléfono</th>
                                <th width="20%" class="text-center">Correo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador_de_clientes = 0;
                            foreach ($clientes_datos as $clientes_dato){
                                $id_cliente = $clientes_dato['id_cliente'];
                                $contador_de_clientes++;
                                ?>
                                <tr>
                                    <td class="align-middle"><?php echo $contador_de_clientes; ?></td>
                                    <td class="align-middle">
                                        <button class="btn btn-warning btn-sm btn-seleccionar-cliente"
                                                data-id="<?php echo $id_cliente; ?>"
                                                data-nombre="<?php echo $clientes_dato['nombre_cliente']; ?>"
                                                data-celular="<?php echo $clientes_dato['celular_cliente']; ?>"
                                                data-nit="<?php echo $clientes_dato['nit_ci_cliente']; ?>"
                                                data-email="<?php echo $clientes_dato['email_cliente']; ?>">
                                            <i class="fas fa-check mr-1"></i> Seleccionar
                                        </button>
                                    </td>
                                    <td class="align-middle">
                                        <strong><?php echo $clientes_dato['nombre_cliente']; ?></strong>
                                    </td>
                                    <td class="text-center align-middle">
                                        <small><?php echo $clientes_dato['nit_ci_cliente']; ?></small>
                                    </td>
                                    <td class="text-center align-middle"><?php echo $clientes_dato['celular_cliente']; ?></td>
                                    <td class="text-center align-middle">
                                        <small><?php echo $clientes_dato['email_cliente']; ?></small>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Cliente -->
<div class="modal fade" id="modal-agregar_cliente">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h4 class="modal-title"><i class="fas fa-user-plus mr-2"></i>Nuevo Cliente</h4>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../app/controllers/clientes/guardar_clientes.php" method="post">
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre del Cliente</label>
                        <input type="text" name="nombre_cliente" class="form-control" required placeholder="Nombre completo">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Identificación (RFC/CURP)</label>
                        <input type="text" name="nit_ci_cliente" class="form-control" placeholder="Opcional">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Teléfono/Celular</label>
                        <input type="text" name="celular_cliente" class="form-control" placeholder="Número de contacto">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Correo Electrónico</label>
                        <input type="email" name="email_cliente" class="form-control" placeholder="Opcional">
                    </div>
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-success btn-block btn-lg">
                            <i class="fas fa-save mr-2"></i> Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.card-header {
    border-bottom: 2px solid #e3e6f0;
}
.btn {
    border-radius: 6px;
}
.table th {
    border-top: none;
    font-weight: 600;
}
</style>

<script>
$(function () {
    // Inicializar DataTables
    $('#tabla-productos').DataTable({
        "pageLength": 5,
        "responsive": true,
        "autoWidth": false,
        "language": {
            "emptyTable": "No hay productos disponibles",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ productos",
            "infoEmpty": "Mostrando 0 a 0 de 0 productos",
            "infoFiltered": "(filtrado de _MAX_ productos totales)",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron productos",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });

    $('#tabla-clientes').DataTable({
        "pageLength": 5,
        "responsive": true,
        "autoWidth": false,
        "language": {
            "emptyTable": "No hay clientes registrados",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ clientes",
            "infoEmpty": "Mostrando 0 a 0 de 0 clientes",
            "infoFiltered": "(filtrado de _MAX_ clientes totales)",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron clientes",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });

    // Seleccionar producto
    $('.btn-seleccionar-producto').click(function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var descripcion = $(this).data('descripcion');
        var precio = $(this).data('precio');

        $('#id_producto').val(id);
        $('#producto').val(nombre);
        $('#descripcion').val(descripcion);
        $('#precio_venta').val('$ ' + parseFloat(precio).toFixed(2));
        $('#cantidad').val(1).focus();
        $('#respuesta_validad_cantidad_stock').html('');
    });

    // Seleccionar cliente
    $('.btn-seleccionar-cliente').click(function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var celular = $(this).data('celular');
        var nit = $(this).data('nit');
        var email = $(this).data('email');

        $('#id_cliente').val(id);
        $('#nombre_cliente').val(nombre);
        $('#celular_cliente').val(celular);
        $('#nit_ci_cliente').val(nit);
        $('#email_cliente').val(email);

        $('#modal-buscar_cliente').modal('hide');
    });

    // Validar cantidad en stock
    $('#cantidad').keyup(function () {
        var cantidad = $(this).val();
        var id_producto = $('#id_producto').val();
        
        if(cantidad && id_producto) {
            var url = "../app/controllers/ventas/validad_cantidad_stock.php";
            $.get(url, {cantidad: cantidad, id_producto: id_producto}, function(datos) {
                $('#respuesta_validad_cantidad_stock').html(datos);
            });
        }
    });

    // Registrar producto en carrito
    $('#btn_registrar_carrito').click(function () {
        var nro_venta = '<?php echo $numero_venta; ?>';
        var id_producto = $('#id_producto').val();
        var cantidad = $('#cantidad').val();

        if(!id_producto){
            alert("Por favor, seleccione un producto primero");
        } else if(!cantidad) {
            alert("Por favor, ingrese la cantidad del producto");
        } else {
            var url = "../app/controllers/ventas/registrar_carrito.php";
            $.get(url, {nro_venta: nro_venta, id_producto: id_producto, cantidad: cantidad}, function(datos) {
                $('#respuesta_carrito').html(datos);
                if(datos.includes('éxito') || datos.includes('correctamente')) {
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            });
        }
    });

    // Calcular cambio
    $('#total_pagado').keyup(function () {
        var total_a_cancelar = parseFloat('<?php echo $precio_total; ?>');
        var total_pagado = parseFloat($(this).val()) || 0;
        var cambio = total_pagado - total_a_cancelar;
        
        $('#cambio').val('$ ' + cambio.toFixed(2));
        
        if(cambio >= 0) {
            $('#cambio').removeClass('text-danger').addClass('text-success');
        } else {
            $('#cambio').removeClass('text-success').addClass('text-danger');
        }
    });

    // Procesar venta
    $('#btn_guardar_venta').click(function () {
        var nro_venta = '<?php echo $numero_venta; ?>';
        var id_cliente = $('#id_cliente').val();
        var total_a_cancelar = parseFloat('<?php echo $precio_total; ?>');

        if(!id_cliente){
            alert("Por favor, seleccione un cliente antes de continuar");
            return;
        }

        if(confirm('¿Está seguro de finalizar esta venta?\n\nTotal: $ ' + total_a_cancelar.toFixed(2))) {
            // Actualizar stock de productos
            <?php 
            $contador_actualizar = 0;
            foreach ($carrito_datos as $item): 
                $contador_actualizar++;
                ?>
                var stock_inventario = <?php echo $item['stock']; ?>;
                var cantidad_carrito = <?php echo $item['cantidad']; ?>;
                var id_producto = <?php echo $item['id_producto']; ?>;
                var stock_calculado = stock_inventario - cantidad_carrito;

                $.get("../app/controllers/ventas/actualizar_stock.php", {
                    id_producto: id_producto, 
                    stock_calculado: stock_calculado
                });
            <?php endforeach; ?>

            // Registrar la venta
            $.get("../app/controllers/ventas/registro_de_ventas.php", {
                nro_venta: nro_venta,
                id_cliente: id_cliente,
                total_a_cancelar: total_a_cancelar
            }, function(datos) {
                $('#respuesta_registro_venta').html(datos);
                if(datos.includes('éxito') || datos.includes('correctamente')) {
                    setTimeout(function() {
                        window.location.href = 'listado.php';
                    }, 2000);
                }
            });
        }
    });
});
</script>