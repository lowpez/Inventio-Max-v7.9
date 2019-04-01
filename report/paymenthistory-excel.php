<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/PaymentData.php";
include "../core/app/model/PaymentTypeData.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
//$products = PersonData::getClients();

$client = PersonData::getById($_GET["id"]);
$total = PaymentData::sumByClientId($client->id)->total;


$products = PaymentData::getAllByClientId($client->id);


// Set document properties
$objPHPExcel->getProperties()->setCreator("Inventio Max v4.1")
							 ->setLastModifiedBy("Inventio Max v4.1")
							 ->setTitle("Products - Inventio Max v4.1")
							 ->setSubject("Inventio Max Products Report")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'Historial de pagos - Inventio Max')
->setCellValue('A2', 'Cliente: '.$client->name." ".$client->lastname)
->setCellValue('A4', 'Tipo')
->setCellValue('B4', 'Valor')
->setCellValue('C4', 'Saldo')
->setCellValue('D4', 'Fecha');

$start = 5;
foreach($products as $product){
$sheet->setCellValue('A'.$start, $product->getPaymentType()->name)
->setCellValue('B'.$start, "$". $product->val)
->setCellValue('C'.$start, "$". $total)
->setCellValue('D'.$start, $product->created_at);
 $total-=$product->val;

$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="paymenthistory-'.time().'.xlsx"');
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
