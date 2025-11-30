<?php
include ('../app/config.php');
include ('../layout/sesion.php');

include ('../layout/parte1.php');


include ('../app/controllers/proveedores/listado_de_proveedores.php');


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="m-0">Listado de Proveedores</h1>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-create">
                            <i class="fas fa-user-plus mr-1"></i> Agregar Nuevo Proveedor
                        </button>
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
                            <h3 class="card-title"><i class="fas fa-truck mr-2"></i>Proveedores Registrados</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body" style="display: block;">
                            <table id="example1" class="table table-bordered table-striped table-sm table-hover">
                                <thead class="bg-gradient-primary text-white">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre del Proveedor</th>
                                    <th class="text-center">Contacto</th>
                                    <th class="text-center">Teléfono</th>
                                    <th class="text-center">Empresa</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Dirección</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $contador = 0;
                                foreach ($proveedores_datos as $proveedores_dato){
                                    $id_proveedor = $proveedores_dato['id_proveedor'];
                                    $nombre_proveedor = $proveedores_dato['nombre_proveedor']; ?>
                                    <tr>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-primary badge-pill"><?php echo $contador = $contador + 1;?></span>
                                        </td>
                                        <td class="align-middle">
                                            <strong><?php echo $nombre_proveedor;?></strong>
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="https://wa.me/52<?php echo $proveedores_dato['celular'];?>" target="_blank" class="btn btn-success btn-sm">
                                                <i class="fab fa-whatsapp mr-1"></i>
                                                <?php echo $proveedores_dato['celular'];?>
                                            </a>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if($proveedores_dato['telefono']): ?>
                                                <span class="badge badge-info"><?php echo $proveedores_dato['telefono'];?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php echo $proveedores_dato['empresa'];?>
                                        </td>
                                        <td class="align-middle">
                                            <?php if($proveedores_dato['email']): ?>
                                                <a href="mailto:<?php echo $proveedores_dato['email'];?>" class="text-primary">
                                                    <i class="fas fa-envelope mr-1"></i>
                                                    <?php echo $proveedores_dato['email'];?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <small class="text-muted"><?php echo $proveedores_dato['direccion'];?></small>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                                        data-target="#modal-update<?php echo $id_proveedor;?>">
                                                    <i class="fas fa-edit mr-1"></i> Editar
                                                </button>
                                                
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#modal-delete<?php echo $id_proveedor;?>">
                                                    <i class="fas fa-trash mr-1"></i> Eliminar
                                                </button>
                                            </div>

                                            <!-- Modal para actualizar proveedor -->
                                            <div class="modal fade" id="modal-update<?php echo $id_proveedor;?>">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning text-dark">
                                                            <h4 class="modal-title"><i class="fas fa-edit mr-2"></i>Actualizar Proveedor</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""><i class="fas fa-user mr-1 text-primary"></i>Nombre del proveedor <span class="text-danger">*</span></label>
                                                                        <input type="text" id="nombre_proveedor<?php echo $id_proveedor;?>" value="<?php echo $nombre_proveedor;?>" class="form-control" placeholder="Ingrese el nombre completo">
                                                                        <small style="color: red;display: none" id="lbl_nombre<?php echo $id_proveedor;?>">* Este campo es requerido</small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""><i class="fas fa-mobile-alt mr-1 text-success"></i>Celular <span class="text-danger">*</span></label>
                                                                        <input type="number" id="celular<?php echo $id_proveedor;?>" value="<?php echo $proveedores_dato['celular'];?>" class="form-control" placeholder="Ej: 3141665887">
                                                                        <small style="color: red;display: none" id="lbl_celular<?php echo $id_proveedor;?>">* Este campo es requerido</small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""><i class="fas fa-phone mr-1 text-info"></i>Teléfono fijo</label>
                                                                        <input type="number" id="telefono<?php echo $id_proveedor;?>" value="<?php echo $proveedores_dato['telefono'];?>" class="form-control" placeholder="Opcional">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""><i class="fas fa-building mr-1 text-secondary"></i>Empresa <span class="text-danger">*</span></label>
                                                                        <input type="text" id="empresa<?php echo $id_proveedor;?>" value="<?php echo $proveedores_dato['empresa'];?>" class="form-control" placeholder="Nombre de la empresa">
                                                                        <small style="color: red;display: none" id="lbl_empresa<?php echo $id_proveedor;?>">* Este campo es requerido</small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""><i class="fas fa-envelope mr-1 text-primary"></i>Email <span class="text-danger">*</span></label>
                                                                        <input type="email" id="email<?php echo $id_proveedor;?>" value="<?php echo $proveedores_dato['email'];?>" class="form-control" placeholder="correo@empresa.com">
                                                                        <small style="color: red;display: none" id="lbl_email<?php echo $id_proveedor;?>">* Ingrese un correo válido</small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""><i class="fas fa-map-marker-alt mr-1 text-danger"></i>Dirección <span class="text-danger">*</span></label>
                                                                        <textarea name="" id="direccion<?php echo $id_proveedor;?>" cols="30" rows="3" class="form-control" placeholder="Dirección completa"><?php echo $proveedores_dato['direccion'];?></textarea>
                                                                        <small style="color: red;display: none" id="lbl_direccion<?php echo $id_proveedor;?>">* Este campo es requerido</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                <i class="fas fa-times mr-1"></i> Cancelar
                                                            </button>
                                                            <button type="button" class="btn btn-warning" id="btn_update<?php echo $id_proveedor;?>">
                                                                <i class="fas fa-save mr-1"></i> Actualizar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <script>
                                                $('#btn_update<?php echo $id_proveedor;?>').click(function () {
                                                    var id_proveedor = '<?php echo $id_proveedor;?>';
                                                    var nombre_proveedor = $('#nombre_proveedor<?php echo $id_proveedor;?>').val();
                                                    var celular = $('#celular<?php echo $id_proveedor;?>').val();
                                                    var telefono = $('#telefono<?php echo $id_proveedor;?>').val();
                                                    var empresa = $('#empresa<?php echo $id_proveedor;?>').val();
                                                    var email = $('#email<?php echo $id_proveedor;?>').val();
                                                    var direccion = $('#direccion<?php echo $id_proveedor;?>').val();

                                                    // Resetear mensajes de error
                                                    $('[id^="lbl_"]').css('display','none');

                                                    var hasError = false;

                                                    if(nombre_proveedor == ""){
                                                        $('#nombre_proveedor<?php echo $id_proveedor;?>').focus();
                                                        $('#lbl_nombre<?php echo $id_proveedor;?>').css('display','block');
                                                        hasError = true;
                                                    }
                                                    if(celular == ""){
                                                        $('#celular<?php echo $id_proveedor;?>').focus();
                                                        $('#lbl_celular<?php echo $id_proveedor;?>').css('display','block');
                                                        hasError = true;
                                                    }
                                                    if(empresa == ""){
                                                        $('#empresa<?php echo $id_proveedor;?>').focus();
                                                        $('#lbl_empresa<?php echo $id_proveedor;?>').css('display','block');
                                                        hasError = true;
                                                    }
                                                    if(email == "" || !isValidEmail(email)){
                                                        $('#email<?php echo $id_proveedor;?>').focus();
                                                        $('#lbl_email<?php echo $id_proveedor;?>').css('display','block');
                                                        hasError = true;
                                                    }
                                                    if(direccion == ""){
                                                        $('#direccion<?php echo $id_proveedor;?>').focus();
                                                        $('#lbl_direccion<?php echo $id_proveedor;?>').css('display','block');
                                                        hasError = true;
                                                    }

                                                    if(!hasError) {
                                                        var url = "../app/controllers/proveedores/update.php";
                                                        $.get(url,{
                                                            id_proveedor:id_proveedor,
                                                            nombre_proveedor:nombre_proveedor,
                                                            celular:celular,
                                                            telefono:telefono,
                                                            empresa:empresa,
                                                            email:email,
                                                            direccion:direccion
                                                        },function (datos) {
                                                            $('#respuesta').html(datos);
                                                        });
                                                    }
                                                });

                                                function isValidEmail(email) {
                                                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                                    return emailRegex.test(email);
                                                }
                                            </script>

                                            <!-- Modal para eliminar proveedor -->
                                            <div class="modal fade" id="modal-delete<?php echo $id_proveedor;?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h4 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Confirmar Eliminación</h4>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="alert alert-warning">
                                                                <h5><i class="fas fa-exclamation-circle mr-2"></i>¿Está seguro de eliminar al proveedor?</h5>
                                                                <p class="mb-0"><strong><?php echo $nombre_proveedor; ?></strong> - <?php echo $proveedores_dato['empresa']; ?></p>
                                                            </div>
                                                            <div class="text-center">
                                                                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                                                                <p>Esta acción no se puede deshacer.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                <i class="fas fa-times mr-1"></i> Cancelar
                                                            </button>
                                                            <button type="button" class="btn btn-danger" id="btn_delete<?php echo $id_proveedor;?>">
                                                                <i class="fas fa-trash mr-1"></i> Eliminar
                                                            </button>
                                                        </div>
                                                        <div id="respuesta_delete<?php echo $id_proveedor;?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <script>
                                                $('#btn_delete<?php echo $id_proveedor;?>').click(function () {
                                                    var id_proveedor = '<?php echo $id_proveedor;?>';
                                                    var url2 = "../app/controllers/proveedores/delete.php";
                                                    $.get(url2,{id_proveedor:id_proveedor},function (datos) {
                                                        $('#respuesta_delete<?php echo $id_proveedor;?>').html(datos);
                                                    });
                                                });
                                            </script>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
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
    .btn-group .btn {
        margin-right: 5px;
    }
    .modal-header {
        border-bottom: 2px solid #dee2e6;
    }
    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
    }
    .required-field::after {
        content: " *";
        color: red;
    }
</style>

<script>
    $(function () {
        $("#example1").DataTable({
            "pageLength": 10,
            "language": {
                "emptyTable": "No hay proveedores registrados",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ proveedores",
                "infoEmpty": "Mostrando 0 a 0 de 0 proveedores",
                "infoFiltered": "(filtrado de _MAX_ proveedores totales)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ proveedores",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron proveedores coincidentes",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "order": [[1, "asc"]],
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                   '<"row"<"col-sm-12"tr>>' +
                   '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });
    });
</script>

<!-- Modal para registrar proveedores -->
<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title"><i class="fas fa-user-plus mr-2"></i>Registrar Nuevo Proveedor</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre_proveedor" class="required-field">
                                <i class="fas fa-user mr-1"></i>Nombre del proveedor
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
                                </div>
                                <input type="text" id="nombre_proveedor" class="form-control" placeholder="Ingrese el nombre completo">
                            </div>
                            <small style="color: red;display: none" id="lbl_nombre">* Este campo es requerido</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="celular" class="required-field">
                                <i class="fas fa-mobile-alt mr-1"></i>Celular
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-mobile-alt text-success"></i></span>
                                </div>
                                <input type="number" id="celular" class="form-control" placeholder="Ej: 3141665887">
                            </div>
                            <small style="color: red;display: none" id="lbl_celular">* Este campo es requerido</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono">
                                <i class="fas fa-phone mr-1"></i>Teléfono fijo
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone text-info"></i></span>
                                </div>
                                <input type="number" id="telefono" class="form-control" placeholder="Opcional">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="empresa" class="required-field">
                                <i class="fas fa-building mr-1"></i>Empresa
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-building text-secondary"></i></span>
                                </div>
                                <input type="text" id="empresa" class="form-control" placeholder="Nombre de la empresa">
                            </div>
                            <small style="color: red;display: none" id="lbl_empresa">* Este campo es requerido</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="required-field">
                                <i class="fas fa-envelope mr-1"></i>Email
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
                                </div>
                                <input type="email" id="email" class="form-control" placeholder="correo@empresa.com">
                            </div>
                            <small style="color: red;display: none" id="lbl_email">* Ingrese un correo válido</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="direccion" class="required-field">
                                <i class="fas fa-map-marker-alt mr-1"></i>Dirección
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt text-danger"></i></span>
                                </div>
                                <textarea name="" id="direccion" cols="30" rows="3" class="form-control" placeholder="Dirección completa"></textarea>
                            </div>
                            <small style="color: red;display: none" id="lbl_direccion">* Este campo es requerido</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btn_create">
                    <i class="fas fa-save mr-1"></i> Guardar Proveedor
                </button>
            </div>
            <div id="respuesta"></div>
        </div>
    </div>
</div>

<script>
    $('#btn_create').click(function () {
        var nombre_proveedor = $('#nombre_proveedor').val();
        var celular = $('#celular').val();
        var telefono = $('#telefono').val();
        var empresa = $('#empresa').val();
        var email = $('#email').val();
        var direccion = $('#direccion').val();

        // Resetear mensajes de error
        $('[id^="lbl_"]').css('display','none');

        var hasError = false;

        if(nombre_proveedor == ""){
            $('#nombre_proveedor').focus();
            $('#lbl_nombre').css('display','block');
            hasError = true;
        }
        if(celular == ""){
            $('#celular').focus();
            $('#lbl_celular').css('display','block');
            hasError = true;
        }
        if(empresa == ""){
            $('#empresa').focus();
            $('#lbl_empresa').css('display','block');
            hasError = true;
        }
        if(email == "" || !isValidEmail(email)){
            $('#email').focus();
            $('#lbl_email').css('display','block');
            hasError = true;
        }
        if(direccion == ""){
            $('#direccion').focus();
            $('#lbl_direccion').css('display','block');
            hasError = true;
        }

        if(!hasError) {
            var url = "../app/controllers/proveedores/create.php";
            $.get(url,{
                nombre_proveedor:nombre_proveedor,
                celular:celular,
                telefono:telefono,
                empresa:empresa,
                email:email,
                direccion:direccion
            },function (datos) {
                $('#respuesta').html(datos);
            });
        }
    });

    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Efecto de focus en los campos
    $('.form-control').focus(function() {
        $(this).parent().parent().css('border-left', '3px solid #007bff');
    }).blur(function() {
        $(this).parent().parent().css('border-left', '');
    });
</script>