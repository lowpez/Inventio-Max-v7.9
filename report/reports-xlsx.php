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
//$products = ProductData::getAll();

$operations = array();

			if($_GET["product_id"]==""){
			$operations = OperationData::getAllByDateOfficial($_GET["stock_id"],$_GET["sd"],$_GET["ed"]);
			}
			else{
			$operations = OperationData::getAllByDateOfficialBP($_GET["stock_id"],$_GET["product_id"],$_GET["sd"],$_GET["ed"]);
			} 


// Set document properties
$objPHPExcel->getProperties()->setCreator("Inventio Max v3.1")
							 ->setLastModifiedBy("Inventio Max v3.1")
							 ->setTitle("Report - Inventio Max v3.1")
							 ->setSubject("Inventio Max Report")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'Reporte de Inventario - Inventio Max')
->setCellValue('A2', 'Id')
->setCellValue('B2', 'Producto')
->setCellValue('C2', 'Cantidad')
->setCellValue('D2', 'Operacion')
->setCellValue('E2', 'Fecha');

$start = 3;
foreach($operations as $operation){
$sheet->setCellValue('A'.$start, $operation->id)
->setCellValue('B'.$start, $operation->getProduct()->name)
->setCellValue('C'.$start, $operation->q)
->setCellValue('D'.$start, $operation->getOperationType()->name)
->setCellValue('E'.$start, $operation->created_at);

$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report1-'.time().'.xlsx"');
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
