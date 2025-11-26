<?php
// ==========================================
//  TICKET.PHP - Generador de Ticket en PDF
// ==========================================

require('fpdf/fpdf.php');  // Asegúrate que la carpeta /fpdf/ existe

// Leer JSON enviado desde fetch
$data = json_decode(file_get_contents("php://input"), true);

$carrito = $data["carrito"];
$total = $data["total"];
$efectivo = $data["efectivo"];
$cambio = floatval($efectivo) - floatval($total);

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Encabezado
$pdf->Cell(0, 10, utf8_decode("Market.GO - Ticket de Compra"), 0, 1, "C");
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, date("d/m/Y H:i:s"), 0, 1, "C");
$pdf->Ln(5);

// Línea divisoria
$pdf->Cell(0, 0, str_repeat("-", 80), 0, 1);
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 8, "Producto", 0, 0);
$pdf->Cell(40, 8, "Precio", 0, 1);

// Contenido del carrito
$pdf->SetFont('Arial', '', 12);
foreach ($carrito as $p) {
    $nombre = utf8_decode($p["nombre"]);
    $precio = number_format($p["precio"], 2);

    $pdf->Cell(100, 8, $nombre, 0, 0);
    $pdf->Cell(40, 8, "$" . $precio, 0, 1);
}

$pdf->Ln(3);
$pdf->Cell(0, 0, str_repeat("-", 80), 0, 1);
$pdf->Ln(5);

// Totales
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(100, 8, "TOTAL:", 0, 0);
$pdf->Cell(40, 8, "$" . number_format($total, 2), 0, 1);

$pdf->Cell(100, 8, "EFECTIVO:", 0, 0);
$pdf->Cell(40, 8, "$" . number_format($efectivo, 2), 0, 1);

$pdf->Cell(100, 8, "CAMBIO:", 0, 0);
$pdf->Cell(40, 8, "$" . number_format($cambio, 2), 0, 1);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, utf8_decode("¡Gracias por su compra!"), 0, 1, "C");

// Mostrar PDF en el navegador
$pdf->Output("ticket.pdf", "I");
