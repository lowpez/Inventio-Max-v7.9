<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/CategoryData.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$products = ProductData::getAll();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Inventio Max v3.1")
							 ->setLastModifiedBy("Inventio Max v3.1")
							 ->setTitle("Products - Inventio Max v3.1")
							 ->setSubject("Inventio Max Products Report")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'Reporte de Productos - Inventio Max')
->setCellValue('A2', 'Codigo de barra')
->setCellValue('B2', 'Nombre')
->setCellValue('C2', 'Descripcion')
->setCellValue('D2', 'Minima en Inventario')
->setCellValue('E2', 'Precio de Entrada')
->setCellValue('F2', 'Precio de Salida')
->setCellValue('G2', 'Unidad')
->setCellValue('H2', 'Presentacion')
->setCellValue('I2', 'Categoria')
->setCellValue('J2', 'Activo');

$start = 3;
foreach($products as $product){
$sheet->setCellValue('A'.$start, $product->barcode)
->setCellValue('B'.$start, $product->name)
->setCellValue('C'.$start, $product->description)
->setCellValue('D'.$start, $product->inventary_min)
->setCellValue('E'.$start, $product->price_in)
->setCellValue('F'.$start, $product->price_out)
->setCellValue('G'.$start, $product->unit)
->setCellValue('H'.$start, $product->presentation)
->setCellValue('I'.$start, $product->category_id)
->setCellValue('J'.$start, $product->is_active);
$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="products-'.time().'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
