<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/OperationTypeData.php";

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

$sheet->setCellValue('A1', 'Reporte de Inventario - Inventio Max')
->setCellValue('A2', 'Id')
->setCellValue('B2', 'Producto')
->setCellValue('C2', 'Por Recibir')
->setCellValue('D2', 'Producto')
->setCellValue('E2', 'Por Entregar');

$start = 3;
foreach($products as $product){
$r=OperationData::getRByStock($product->id,$_GET["stock_id"]);
$q=OperationData::getQByStock($product->id,$_GET["stock_id"]);
$d=OperationData::getDByStock($product->id,$_GET["stock_id"]);

$sheet->setCellValue('A'.$start, $product->barcode)
->setCellValue('B'.$start, $product->name)
->setCellValue('C'.$start, $r)
->setCellValue('D'.$start, $q)
->setCellValue('E'.$start, $d);

$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="inventary-'.time().'.xlsx"');
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
