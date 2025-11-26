<?php
// Include the main TCPDF library (search for installation path).



require_once('../app/TCPDF-main/tcpdf.php');
include('../app/config.php');
include ('../app/controllers/ventas/literal.php');



session_start();
if(isset($_SESSION['sesion_email'])){
    // echo "si existe sesion de ".$_SESSION['sesion_email'];
    $email_sesion = $_SESSION['sesion_email'];
    $sql = "SELECT us.id_usuario as id_usuario, us.nombres as nombres, us.email as email, rol.rol as rol 
                  FROM tb_usuarios as us INNER JOIN tb_roles as rol ON us.id_rol = rol.id_rol WHERE email='$email_sesion'";
    $query = $pdo->prepare($sql);
    $query->execute();
    $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($usuarios as $usuario){
        $id_usuario_sesion = $usuario['id_usuario'];
        $nombres_sesion = $usuario['nombres'];
        $rol_sesion = $usuario['rol'];
    }
}else{
    echo "no existe sesion";
    header('Location: '.$URL.'/login');
}





$id_venta_get = $_GET['id_venta'];
$nro_venta_get = $_GET['nro_venta'];

$sql_ventas = "SELECT *, cli.nombre_cliente as nombre_cliente, cli.nit_ci_cliente as nit_ci_cliente 
               FROM tb_ventas as ve INNER JOIN tb_clientes as cli ON cli.id_cliente = ve.id_cliente where ve.id_venta = '$id_venta_get' ";
$query_ventas = $pdo->prepare($sql_ventas);
$query_ventas->execute();
$ventas_datos = $query_ventas->fetchAll(PDO::FETCH_ASSOC);


foreach ($ventas_datos as $ventas_dato)
{
    $fyh_creacion = $ventas_dato['fyh_creacion'];
    $nit_ci_cliente = $ventas_dato['nit_ci_cliente'];
    $nombre_cliente = $ventas_dato['nombre_cliente'];
    $total_pagado = $ventas_dato['total_pagado'];
}

//convierte precio total a literal
$monto_literal = numtoletras($total_pagado);

$fecha = date("d/m/Y", strtotime($fyh_creacion));


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(215,279), true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Sistema de parqueo');
$pdf->setTitle('Sistema de parqueo');
$pdf->setSubject('Sistema de parqueo');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(15, 15, 15);

// set auto page breaks
$pdf->setAutoPageBreak(true, 5);


// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->setFont('Helvetica', '', 12);

// add a page
$pdf->AddPage();




// create some HTML content
$html ='
<table border="0" style="font-size: 10px">
<tr>
    <td style="text-align: center;width: 230px">
    <img src="../public/images/logo.jpg" width="80px" alt=""> <br><br>
        <b>SISTEMA DE VENTAS HILARI WEB</b> <br>
    Zona Alto Lima 1ra Sección Av. Litoral #2345 <br>
    23884774 - 75657007 <br>
    LA PAZ - BOLIVIA
    </td>
    <td style="width: 150px"></td>
    <td style="font-size: 16px;width: 290px"><br><br><br>
        <b>NIT: </b>10001099920 <br>
        <b>Nro factura:</b> '.$id_venta_get.' <br>
        <b>Nro de autorización: </b>100020029930
        <p style="text-align: center"><B>ORIGINAL</B></p>
    </td>
</tr>
</table>

<p style="text-align: center;font-size: 25px"><b>FACTURA</b></p>

<div style="border: 1px solid #000000">
<table border="0" cellpadding="6px">
<tr>
    <td><b>Fecha:</b> '.$fecha.'</td>
    <td></td>
    <td><b>Nit/CI: </b>'.$nit_ci_cliente.'</td>
</tr>
<tr>
    <td colspan="3"><b>Señor(es): </b>'.$nombre_cliente.' </td>
</tr>
</table>
</div>

<br>

<table border="1" cellpadding="5" style="font-size: 12px">
<tr style="text-align: center;background-color: #d6d6d6">
    <th style="width: 40px"><b>Nro</b></th>
    <th style="width: 150px"><b>Producto</b></th>
    <th style="width: 235px"><b>Descripción</b></th>
    <th style="width: 65px"><b>Cantidad</b></th>
    <th style="width: 98px"><b>Precio Unitario</b></th>
    <th style="width: 69px"><b>Sub total</b></th>
</tr>
';
$contador_de_carrito = 0;
$cantidad_total = 0;
$precio_unitario_total = 0;
$precio_total = 0;

$sql_carrito = "SELECT *,pro.nombre as nombre_producto, pro.descripcion as descripcion, pro.precio_venta as precio_venta, 
pro.stock as stock, pro.id_producto as id_producto 
FROM tb_carrito AS carr INNER JOIN tb_almacen as pro ON carr.id_producto = pro.id_producto 
WHERE nro_venta = '$nro_venta_get' ORDER BY id_carrito ASC ";

$query_carrito = $pdo->prepare($sql_carrito);
$query_carrito->execute();
$carrito_datos = $query_carrito->fetchAll(PDO::FETCH_ASSOC);
foreach ($carrito_datos as $carrito_dato){
    $id_carrito = $carrito_dato['id_carrito'];
    $contador_de_carrito = $contador_de_carrito + 1;
    $cantidad_total = $cantidad_total + $carrito_dato['cantidad'];
    $precio_unitario_total = $precio_unitario_total + floatval($carrito_dato['precio_venta']);
    $subtotal = $carrito_dato['cantidad'] * $carrito_dato['precio_venta'];
    $precio_total = $precio_total + $subtotal;

    $html .='
    <tr>
        <td style="text-align: center">'.$contador_de_carrito.'</td>
        <td>'.$carrito_dato['nombre_producto'].'</td>
        <td>'.$carrito_dato['descripcion'].'</td>
        <td style="text-align: center">'.$carrito_dato['cantidad'].'</td>
        <td style="text-align: center">Bs. '.$carrito_dato['precio_venta'].'</td>
        <td style="text-align: center">Bs. '.$subtotal.'</td>
    </tr>
    ';

}

$html .='

<tr>
    <td colspan="3" style="text-align: right;background-color: #d6d6d6"><b>Total</b></td>
    <td style="text-align: center;background-color: #d6d6d6">'.$cantidad_total.'</td>
    <td style="text-align: center;background-color: #d6d6d6">Bs. '.$precio_unitario_total.'</td>
    <td style="text-align: center;background-color: #d6d6d6">Bs. '.$precio_total.'</td>
</tr>
</table>

<p style="text-align: right">
         <b>Monto Total: </b> Bs. '.$precio_total.'
        </p>
        <p>
            <b>Son: </b>'.$monto_literal.'
        </p>
        <br>
         -------------------------------------------------------------------------------- <br>
         <b>USUARIO:</b> '.$email_sesion.' <br>
         
        <p style="text-align: center">"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LA LEY"
        </p>
        <p style="text-align: center"><b>GRACIAS POR SU PREFERENCIA</b></p>
';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');



$style = array(
    'border' => 0,
    'vpadding' => '3',
    'hpadding' => '3',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);

$QR = 'Factura realizada por el sistema de ventas HILARI WEB, al cliente '.$nombre_cliente.' con nit/ci: '.$nit_ci_cliente.' 
en fecha: '.$fecha.' con el monto total de '.$precio_total.' ';
$pdf->write2DBarcode($QR,'QRCODE,L',  170,240,40,40, $style);




//Close and output PDF document
$pdf->Output('example_002.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+