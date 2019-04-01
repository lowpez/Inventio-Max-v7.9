<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include "../core/autoload.php";
include "../core/app/model/SellData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/DData.php";
include "../core/app/model/PData.php";

require_once '../core/controller/PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();
$sells = SellData::getSellsToDeliver();

$section1 = $word->AddSection();
$section1->addText("VENTAS POR ENTREGAR",array("size"=>22,"bold"=>true,"align"=>"right"));


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("Id");
$table1->addCell()->addText("Pago");
$table1->addCell()->addText("Entrega");
$table1->addCell()->addText("Total");
$table1->addCell()->addText("Fecha");

foreach($sells as $sell){
//    $q=OperationData::getQYesF($sell->id);
//$q=OperationData::getQByStock($sell->id,$stock);

    $table1->addRow();
    $table1->addCell(300)->addText("#".$sell->id);
    $table1->addCell(2000)->addText($sell->getP()->name);
    $table1->addCell(2000)->addText($sell->getD()->name);
    $table1->addCell(11000)->addText($sell->total-$sell->discount);
    $table1->addCell(11000)->addText($sell->created_at);

}

$word->addTableStyle('table1', $styleTable,$styleFirstRow);
/// datos bancarios

$filename = "sells-".time().".docx";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>