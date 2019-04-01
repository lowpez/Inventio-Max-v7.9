<?php
include "../core/autoload.php";
include "../core/modules/blog/model/ServiceData.php";
include "../core/modules/blog/model/StatusData.php";
include "../core/modules/blog/model/KindData.php";
include "../core/modules/blog/model/VehicleData.php";
include "../core/modules/blog/model/OperatorData.php";
include "../core/modules/blog/model/AsignationData.php";
include "../core/modules/blog/model/BrandData.php";

require_once '../PhpWord/Autoloader.php';


use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;


Autoloader::register();
Settings::loadConfig();

\PhpOffice\PhpWord\Settings::setPdfRendererPath('dompdf/');
\PhpOffice\PhpWord\Settings::setPdfRendererName('DOMPDF');

//Settings::getPdfRendererPath();



$word = new  PhpOffice\PhpWord\PhpWord();

$tow =  OperatorData::getById($_GET["id"]);
$tows = AsignationData::getAllByOperatorId($_GET["id"]);

$kinds = KindData::getAll(); 
$statuses = StatusData::getAll(); 

$section1 = $word->AddSection();
$section1->addText("<table border=0><tr><td><img src='../logo.png' style='width:120px;'></td><td><h1 style='font-size:30px;'>HISTORIAL DEL OPERADOR</h1><h2>OPERADOR: ".strtoupper($tow->name." ".$tow->lastname)."</h2></td></tr></table><br><br>");
//$section1->addText("OPERADOR: ". strtoupper($tow->name." ".$tow->lastname),array("size"=>14,"align"=>"right"));


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("Placa");
$table1->addCell()->addText("Quien habla");
$table1->addCell()->addText("Cliente");
$table1->addCell()->addText("Vehiculo");
$table1->addCell()->addText("Direccion");
$table1->addCell()->addText("Destino");
$table1->addCell()->addText("Tipo");
$table1->addCell()->addText("Estado");
$table1->addCell()->addText("Fecha");
$total = 0;
foreach($tows as $tow){
$service = $tow->getService();
$vehicle = $service->getVehicle();

$table1->addRow();
$table1->addCell(15000)->addText($vehicle->plate);
$table1->addCell(15000)->addText($service->whocall);
$table1->addCell(15000)->addText($service->client_name);

$table1->addCell(15000)->addText($vehicle->plate." - ".$vehicle->getBrand()->name." - ".$vehicle->name." - ".$vehicle->model." - ".$vehicle->color);
$table1->addCell(15000)->addText($service->vehicle_address);

$table1->addCell(15000)->addText($service->vehicle_destination);
$table1->addCell(15000)->addText($service->getKind()->name);
$table1->addCell(15000)->addText($service->getStatus()->name);
$table1->addCell(15000)->addText($service->created_at);
$total += $service->price;
}

$word->addTableStyle('table1', $styleTable,$styleFirstRow);
/// datos bancarios

$section1->addText("<br>Villahermosa Cárdenas km 161 + 200, Col. Cárdenas, Centro");

$filebase = "operatorhistory-".time();
$filename = $filebase.".docx";
$filepdf = $filebase.".pdf";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");

$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($word , 'PDF');
$xmlWriter->save($filebase.'.pdf'); 


//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filepdf'");
readfile($filebase.".pdf"); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file
unlink($filebase.".pdf");  // remove temp file



?>