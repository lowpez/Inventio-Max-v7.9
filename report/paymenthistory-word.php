<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/PaymentData.php";
include "../core/app/model/PaymentTypeData.php";

require_once '../core/controller/PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();


$client = PersonData::getById($_GET["id"]);
$total = PaymentData::sumByClientId($client->id)->total;


// $clients = PersonData::getClients();
$clients = PaymentData::getAllByClientId($client->id);

$section1 = $word->AddSection();
$section1->addText("HISTORIAL DE PAGOS",array("size"=>22,"bold"=>true,"align"=>"right"));
$section1->addText("Cliente: ".$client->name." ".$client->lastname,array("size"=>18,"align"=>"right"));


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("Tipo");
$table1->addCell()->addText("Valor");
$table1->addCell()->addText("Saldo");
$table1->addCell()->addText("Fecha");
foreach($clients as $client){
$table1->addRow();
$table1->addCell(5000)->addText($client->getPaymentType()->name);
$table1->addCell(2000)->addText("$". number_format(PaymentData::sumByClientId($client->val)->total,2,".",","));
$table1->addCell(2000)->addText("$". number_format(PaymentData::sumByClientId($total)->total,2,".",","));
$table1->addCell(2000)->addText($client->created_at);
$total-=$client->val;
}

$word->addTableStyle('table1', $styleTable,$styleFirstRow);
/// datos bancarios

$filename = "paymenthistory-".time().".docx";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>