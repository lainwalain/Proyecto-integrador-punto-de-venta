<?php
// Include the main TCPDF library
require_once('../app/TCPDF-main/tcpdf.php');
include('../app/config.php');
include ('../app/controllers/ventas/literal.php');

session_start();
if(isset($_SESSION['sesion_email'])){
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

// Convierte precio total a literal
$monto_literal = numtoletras($total_pagado);
$fecha = date("d/m/Y", strtotime($fyh_creacion));
$hora = date("H:i:s", strtotime($fyh_creacion));

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(215,279), true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Market Go');
$pdf->setTitle('Factura Market Go');
$pdf->setSubject('Factura de Venta');
$pdf->setKeywords('Market Go, Factura, México');

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

// set font
$pdf->setFont('Helvetica', '', 12);

// add a page
$pdf->AddPage();

// create some HTML content
$html = '
<style>
    .header-title {
        font-size: 16px;
        font-weight: bold;
        color: #2ecc71;
    }
    .empresa-info {
        font-size: 10px;
    }
    .factura-title {
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        color: #2c3e50;
    }
    .datos-cliente {
        border: 1px solid #000000;
        padding: 8px;
        font-size: 11px;
    }
    .tabla-productos {
        font-size: 10px;
    }
    .total-section {
        font-size: 12px;
        font-weight: bold;
    }
    .footer-text {
        font-size: 9px;
        text-align: center;
        color: #666;
    }
</style>

<table border="0" style="font-size: 10px">
<tr>
    <td style="text-align: center; width: 250px">
        <div class="header-title">MARKET GO</div>
        <div class="empresa-info">
            <b>Tu Tienda de Abarrotes</b> <br>
            Manzanillo, Colima, México <br>
            Tel: 3141665887 <br>
            RFC: MGO250129ABC <br>
            Régimen: Régimen Simplificado de Confianza
        </div>
    </td>
    <td style="width: 120px"></td>
    <td style="font-size: 12px; width: 250px">
        <br><br>
        <b>FOLIO:</b> '.$id_venta_get.' <br>
        <b>FECHA:</b> '.$fecha.' <br>
        <b>HORA:</b> '.$hora.' <br>
        <b>NO. DE VENTA:</b> '.$nro_venta_get.' <br>
        <p style="text-align: center; background-color: #2ecc71; color: white; padding: 5px; border-radius: 5px"><b>ORIGINAL</b></p>
    </td>
</tr>
</table>

<p class="factura-title">FACTURA</p>

<div class="datos-cliente">
<table border="0" cellpadding="4px">
<tr>
    <td><b>Cliente:</b> '.$nombre_cliente.'</td>
</tr>
<tr>
    <td><b>RFC/CURP:</b> '.$nit_ci_cliente.'</td>
</tr>
</table>
</div>

<br>

<table border="1" cellpadding="4" class="tabla-productos">
<tr style="text-align: center; background-color: #2ecc71; color: white;">
    <th style="width: 30px"><b>#</b></th>
    <th style="width: 120px"><b>Producto</b></th>
    <th style="width: 50px"><b>Cantidad</b></th>
    <th style="width: 70px"><b>Precio Unitario</b></th>
    <th style="width: 70px"><b>Subtotal</b></th>
</tr>';

$contador_de_carrito = 0;
$cantidad_total = 0;
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
    $subtotal = $carrito_dato['cantidad'] * floatval($carrito_dato['precio_venta']);
    $precio_total = $precio_total + $subtotal;

    $html .= '
    <tr>
        <td style="text-align: center">'.$contador_de_carrito.'</td>
        <td>'.$carrito_dato['nombre_producto'].'</td>
        <td style="text-align: center">'.$carrito_dato['cantidad'].'</td>
        <td style="text-align: right">$ '.number_format($carrito_dato['precio_venta'], 2).'</td>
        <td style="text-align: right">$ '.number_format($subtotal, 2).'</td>
    </tr>';
}

// Calcular IVA (16%) y subtotal
$iva = $precio_total * 0.16;
$subtotal_sin_iva = $precio_total - $iva;

$html .= '
<tr style="background-color: #f8f9fa;">
    <td colspan="2" style="text-align: right; font-weight: bold">SUBTOTAL</td>
    <td style="text-align: center; font-weight: bold">'.$cantidad_total.'</td>
    <td></td>
    <td style="text-align: right; font-weight: bold">$ '.number_format($subtotal_sin_iva, 2).'</td>
</tr>
<tr style="background-color: #f8f9fa;">
    <td colspan="4" style="text-align: right; font-weight: bold">IVA (16%)</td>
    <td style="text-align: right; font-weight: bold">$ '.number_format($iva, 2).'</td>
</tr>
<tr style="background-color: #2ecc71; color: white;">
    <td colspan="4" style="text-align: right; font-weight: bold">TOTAL</td>
    <td style="text-align: right; font-weight: bold">$ '.number_format($precio_total, 2).'</td>
</tr>
</table>

<div class="total-section">
    <p style="text-align: right">
        <b>Monto Total: </b> $ '.number_format($precio_total, 2).'
    </p>
    <p>
        <b>Son: </b>'.$monto_literal.' PESOS MEXICANOS
    </p>
</div>

<br>

<div style="border-top: 1px solid #ccc; padding-top: 10px;">
    <p><b>Atendido por:</b> '.$nombres_sesion.' ('.$email_sesion.')</p>
</div>

<div class="footer-text">
    <p>"Este documento es una representación impresa de un CFDI"</p>
    <p><b>MARKET GO - SISTEMA DE VENTAS</b></p>
    <p>Manzanillo, Colima, México • Tel: 3141665887</p>
    <p>¡Gracias por su preferencia! Vuelva pronto</p>
</div>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// QR Code con información de la factura
$style = array(
    'border' => 0,
    'vpadding' => '3',
    'hpadding' => '3',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false,
    'module_width' => 1,
    'module_height' => 1
);

$QR_info = 'MARKET GO - Factura Digital
Folio: '.$id_venta_get.'
Cliente: '.$nombre_cliente.'
RFC: '.$nit_ci_cliente.'
Fecha: '.$fecha.'
Hora: '.$hora.'
Total: $ '.number_format($precio_total, 2).'
Atendido por: '.$nombres_sesion;

$pdf->write2DBarcode($QR_info, 'QRCODE,L', 160, 240, 40, 40, $style);

// Close and output PDF document
$pdf->Output('Factura_MarketGo_'.$id_venta_get.'.pdf', 'I');
?>