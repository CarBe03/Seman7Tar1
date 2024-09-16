<?php
require('fpdf/fpdf.php');
require_once("../models/productos.model.php");

$pdf = new FPDF();
$pdf->AddPage();
$productos = new Producto();

// Encabezado del reporte
$pdf->SetFont('Arial', 'B', 16);
$pdf->Text(80, 10, 'Reporte de Factura');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Text(10, 20, 'Fecha: ' . date('d-m-Y'));
$pdf->Ln(20);

// Pie de página (número de página)
$pdf->AliasNbPages();
$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, 'Pagina ' . $pdf->PageNo() . '/{nb}', 0, 0, 'C');

// Detalle del reporte (ejemplo de contenido)
$pdf->SetFont('Arial', '', 12);
$texto = "Factura generada con los siguientes productos:";
$pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252', $texto), 0, 'J');
$pdf->Ln();

// Obtener la lista de productos
$listaproductos = $productos->todos();

// Definir el encabezado de la tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, "#", 1);
$pdf->Cell(40, 10, "Codigo de Barras", 1);
$pdf->Cell(55, 10, "Nombre", 1);
$pdf->Cell(30, 10, "Cantidad", 1);
$pdf->Cell(30, 10, "Precio Unitario", 1);
$pdf->Cell(30, 10, "Total", 1);
$pdf->Ln();

// Insertar los datos de los productos
$index = 1;
$totalFactura = 0; // Variable para calcular el total de la factura
$pdf->SetFont('Arial', '', 10);
while ($prod = mysqli_fetch_assoc($listaproductos)) {
    $cantidad = $prod["Cantidad"];
    $precioUnitario = $prod["Precio_Unitario"];
    $totalProducto = $cantidad * $precioUnitario;

    $pdf->Cell(10, 10, $index, 1);
    $pdf->Cell(40, 10, $prod["Codigo_Barras"], 1);
    $pdf->Cell(55, 10, $prod["Nombre_Producto"], 1);
    $pdf->Cell(30, 10, $cantidad, 1);
    $pdf->Cell(30, 10, number_format($precioUnitario, 2) . " $", 1);
    $pdf->Cell(30, 10, number_format($totalProducto, 2) . " $", 1);
    $pdf->Ln();

    // Calcular el total de la factura
    $totalFactura += $totalProducto;
    $index++;
}

// Total de la factura
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(165, 10, "Total Factura:", 1, 0, 'R');
$pdf->Cell(30, 10, number_format($totalFactura, 2) . " $", 1, 0, 'R');

// Imagenes opcionales (logos, etc.)
$pdf->Image('../public/images/sri.png', 10, 270, 30, 0, "PNG");
$pdf->Image('https://www.uniandes.edu.ec/wp-content/uploads/2024/07/2-headerweb-home-2.png', 160, 270, 40, 0, 'PNG');

// Mostrar el PDF generado
$pdf->Output();
?>
