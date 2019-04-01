<?php
include "../core/autoload.php";
include "../core/modules/index/model/SellData.php";
session_start();

require_once '../PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();
$operations = $_SESSION["operations"]; // SellData::getClients();


$section1 = $word->AddSection();
$section1->addText("REPORTE DE VENTAS",array("size"=>22,"bold"=>true,"align"=>"right"));


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("Id");
$table1->addCell()->addText("Subtotal");
$table1->addCell()->addText("(%)");
$table1->addCell()->addText("Descuento");
$table1->addCell()->addText("Total");
$table1->addCell()->addText("Fecha");
$total=0;
foreach($operations as $client){
$table1->addRow();
$table1->addCell(5000)->addText($client->id);
$table1->addCell(2500)->addText("$".number_format($client->total,2,".",","));
$table1->addCell(2500)->addText($client->discount."%");
$table1->addCell(2000)->addText("$".number_format($client->total*($client->discount/100),2,".",","));
$table1->addCell(2000)->addText("$".number_format($client->total-($client->total*($client->discount/100) ),2,".",",") );
$table1->addCell(2500)->addText($client->created_at);
$total+=$client->total-($client->total*($client->discount/100) );
}

$word->addTableStyle('table1', $styleTable,$styleFirstRow);
$section1->addText("Total: $".number_format($total,2,".",","),array("size"=>22,"bold"=>true,"align"=>"right"));
/// datos bancarios

$filename = "sellreports-".time().".docx";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>