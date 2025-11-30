<?php
require_once('../app/TCPDF-main/tcpdf.php');
include('../app/config.php');

// Obtener datos para el reporte
$fecha_reporte = date('d/m/Y H:i:s');

// Consulta para obtener estadísticas de ventas
$sql_ventas_totales = "SELECT COUNT(*) as total_ventas, SUM(total_pagado) as ingreso_total FROM tb_ventas";
$query_ventas = $pdo->prepare($sql_ventas_totales);
$query_ventas->execute();
$estadisticas_ventas = $query_ventas->fetch(PDO::FETCH_ASSOC);

// Consulta para productos más vendidos
$sql_productos_vendidos = "
    SELECT p.nombre, SUM(carr.cantidad) as total_vendido, 
           SUM(carr.cantidad * p.precio_venta) as total_ingresos
    FROM tb_carrito carr 
    INNER JOIN tb_almacen p ON carr.id_producto = p.id_producto 
    GROUP BY p.id_producto 
    ORDER BY total_vendido DESC 
    LIMIT 10
";
$query_productos = $pdo->prepare($sql_productos_vendidos);
$query_productos->execute();
$productos_mas_vendidos = $query_productos->fetchAll(PDO::FETCH_ASSOC);

// Consulta para ventas recientes
$sql_ventas_recientes = "
    SELECT v.nro_venta, v.total_pagado, v.fyh_creacion, c.nombre_cliente 
    FROM tb_ventas v 
    INNER JOIN tb_clientes c ON v.id_cliente = c.id_cliente 
    ORDER BY v.fyh_creacion DESC 
    LIMIT 15
";
$query_recientes = $pdo->prepare($sql_ventas_recientes);
$query_recientes->execute();
$ventas_recientes = $query_recientes->fetchAll(PDO::FETCH_ASSOC);

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Market Go');
$pdf->setTitle('Reporte de Ventas - Market Go');
$pdf->setSubject('Reporte de Sistema de Ventas');
$pdf->setKeywords('Market Go, Ventas, Reporte, PDF');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(15, 15, 15);

// set auto page breaks
$pdf->setAutoPageBreak(true, 25);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
$pdf->setFont('Helvetica', '', 10);

// add a page
$pdf->AddPage();

// create some HTML content
$html = '
<style>
    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #2ecc71;
        padding-bottom: 10px;
    }
    .company-name {
        font-size: 24px;
        font-weight: bold;
        color: #2ecc71;
        margin-bottom: 5px;
    }
    .report-title {
        font-size: 18px;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    .section {
        margin-bottom: 20px;
    }
    .section-title {
        background-color: #2ecc71;
        color: white;
        padding: 8px;
        font-weight: bold;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .stats-grid {
        display: table;
        width: 100%;
        margin-bottom: 15px;
    }
    .stat-item {
        display: table-cell;
        text-align: center;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 5px;
    }
    .stat-value {
        font-size: 16px;
        font-weight: bold;
        color: #2ecc71;
    }
    .stat-label {
        font-size: 12px;
        color: #7f8c8d;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    .table th {
        background-color: #34495e;
        color: white;
        padding: 8px;
        text-align: left;
        font-size: 11px;
    }
    .table td {
        padding: 6px;
        border: 1px solid #ddd;
        font-size: 10px;
    }
    .table tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    .footer {
        text-align: center;
        margin-top: 20px;
        padding-top: 10px;
        border-top: 1px solid #ddd;
        font-size: 9px;
        color: #7f8c8d;
    }
    .badge {
        background-color: #2ecc71;
        color: white;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 9px;
    }
</style>

<div class="header">
    <div class="company-name">MARKET GO</div>
    <div class="report-title">REPORTE GENERAL DE VENTAS</div>
    <div style="font-size: 12px; color: #7f8c8d;">
        Generado el: ' . $fecha_reporte . '<br>
        Manzanillo, Colima, México
    </div>
</div>

<div class="section">
    <div class="section-title">📊 ESTADÍSTICAS GENERALES</div>
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-value">' . number_format($estadisticas_ventas['total_ventas']) . '</div>
            <div class="stat-label">Total de Ventas</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">$ ' . number_format($estadisticas_ventas['ingreso_total'], 2) . '</div>
            <div class="stat-label">Ingresos Totales</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">' . count($productos_mas_vendidos) . '</div>
            <div class="stat-label">Productos Activos</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">' . count($ventas_recientes) . '</div>
            <div class="stat-label">Ventas Recientes</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">🏆 TOP 10 PRODUCTOS MÁS VENDIDOS</div>
    <table class="table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="45%">Producto</th>
                <th width="20%" style="text-align: center;">Cantidad Vendida</th>
                <th width="30%" style="text-align: right;">Ingresos Generados</th>
            </tr>
        </thead>
        <tbody>';

$contador_productos = 0;
foreach ($productos_mas_vendidos as $producto) {
    $contador_productos++;
    $html .= '
            <tr>
                <td>' . $contador_productos . '</td>
                <td>' . htmlspecialchars($producto['nombre']) . '</td>
                <td style="text-align: center;"><span class="badge">' . $producto['total_vendido'] . '</span></td>
                <td style="text-align: right;">$ ' . number_format($producto['total_ingresos'], 2) . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">🛒 VENTAS RECIENTES</div>
    <table class="table">
        <thead>
            <tr>
                <th width="15%">Nro Venta</th>
                <th width="35%">Cliente</th>
                <th width="25%">Fecha</th>
                <th width="25%" style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>';

foreach ($ventas_recientes as $venta) {
    $fecha_venta = date('d/m/Y H:i', strtotime($venta['fyh_creacion']));
    $html .= '
            <tr>
                <td>#' . $venta['nro_venta'] . '</td>
                <td>' . htmlspecialchars($venta['nombre_cliente']) . '</td>
                <td>' . $fecha_venta . '</td>
                <td style="text-align: right;"><strong>$ ' . number_format($venta['total_pagado'], 2) . '</strong></td>
            </tr>';
}

$html .= '
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">📈 RESUMEN EJECUTIVO</div>
    <div style="font-size: 11px; line-height: 1.6;">
        <p><strong>• Total de Ventas Registradas:</strong> ' . number_format($estadisticas_ventas['total_ventas']) . ' transacciones</p>
        <p><strong>• Ingresos Totales Generados:</strong> $ ' . number_format($estadisticas_ventas['ingreso_total'], 2) . ' MXN</p>
        <p><strong>• Ticket Promedio:</strong> $ ' . number_format($estadisticas_ventas['total_ventas'] > 0 ? $estadisticas_ventas['ingreso_total'] / $estadisticas_ventas['total_ventas'] : 0, 2) . ' MXN</p>
        <p><strong>• Productos Más Populares:</strong> ' . ($productos_mas_vendidos[0]['nombre'] ?? 'N/A') . ' (Top 1)</p>
        <p><strong>• Período del Reporte:</strong> Histórico completo hasta ' . $fecha_reporte . '</p>
    </div>
</div>

<div class="footer">
    <strong>MARKET GO - Sistema de Gestión de Ventas</strong><br>
    Manzanillo, Colima, México • Tel: 3141665887<br>
    Este reporte fue generado automáticamente por el sistema.<br>
    © ' . date('Y') . ' Market Go - Todos los derechos reservados.
</div>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// QR Code con información del reporte
$style = array(
    'border' => 0,
    'vpadding' => '3',
    'hpadding' => '3',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false,
    'module_width' => 1,
    'module_height' => 1
);

$QR_info = "MARKET GO - Reporte de Ventas
Fecha: " . $fecha_reporte . "
Total Ventas: " . $estadisticas_ventas['total_ventas'] . "
Ingresos: $" . number_format($estadisticas_ventas['ingreso_total'], 2) . "
Productos Analizados: " . count($productos_mas_vendidos) . "
Generado automáticamente por el sistema";

$pdf->write2DBarcode($QR_info, 'QRCODE,L', 160, 240, 35, 35, $style);

// Close and output PDF document
$pdf->Output('Reporte_Ventas_MarketGo_' . date('Y-m-d') . '.pdf', 'I');
?>