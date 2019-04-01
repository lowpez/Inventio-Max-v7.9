<?php
include "../core/autoload.php";
include "../core/modules/blog/model/RData.php";
include "../core/modules/blog/model/RetenData.php";
include "../core/modules/blog/model/RetenStatusData.php";
include "../core/modules/blog/model/RetenKindData.php";
include "../core/modules/blog/model/VehicleData.php";

require_once '../PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();
\PhpOffice\PhpWord\Settings::setPdfRendererPath('dompdf/');
\PhpOffice\PhpWord\Settings::setPdfRendererName('DOMPDF');

$word = new  PhpOffice\PhpWord\PhpWord();

$kinds = RetenKindData::getAll(); 
$statuses = RetenStatusData::getAll(); 
$services = null;
if((isset($_GET["q"]) && isset($_GET["kind_id"]) && isset($_GET["status_id"])) ){
  $last = false;
  $sql = "select * from reten ";

  if(isset($_GET["q"])&&$_GET["q"]!=""){
    $last = true;
    $sql.= "where  (whocall like '%$_GET[q]%' or vehicle_address like '%$_GET[q]%' or vehicle_destination like '%$_GET[q]%' or client_name like '%$_GET[q]%' or observation like '%$_GET[q]%') ";
  }

  if( isset($_GET["r_id"]) && $_GET["r_id"]!=""){
    if($last){
      $sql .= " and ";
    }else {
      $sql .= " where ";
      $last = true;
    }
    $sql.= " r_id=".$_GET["r_id"];
  }

  if( isset($_GET["kind_id"]) && $_GET["kind_id"]!=""){
    if($last){
      $sql .= " and ";
    }else {
      $sql .= " where ";
      $last = true;
    }
    $sql.= " reten_kind_id=".$_GET["kind_id"];
  }


  if( isset($_GET["status_id"]) && $_GET["status_id"]!=""){
    if($last){
      $sql .= " and ";
    }else {
      $sql .= " where ";
      $last = true;
    }
    $sql.= " reten_status_id=".$_GET["status_id"];
  }


if( isset($_GET["start"]) && $_GET["start"]!="" && isset($_GET["finish"]) && $_GET["finish"]!=""){
    if($last){
      $sql .= " and ";
    }else {
      $sql .= " where ";
      $last = true;
    }
    $sql.= " ( date(created_at)>=\"$_GET[start]\" and date(created_at)<=\"$_GET[finish]\" )";
  }
$services = RetenData::getBySQL($sql);
}else{
  $services = RetenData::getAll();
}
$section1 = $word->AddSection();
$section1->addText("<table border=0><tr><td><img src='../logo.png' style='width:120px;'></td><td><h1 style='font-size:30px;'>REPORTE DE RETEN</h1></td></tr></table><br><br>");


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("Reten");
$table1->addCell()->addText("Quien habla");
$table1->addCell()->addText("Cliente");
$table1->addCell()->addText("Tipo");
$table1->addCell()->addText("Estado");
$table1->addCell()->addText("Costo");
$table1->addCell()->addText("Fecha de Registro");
$total = 0;
foreach($services as $service){
$table1->addRow();
$table1->addCell(15000)->addText($service->getR()->name);
$table1->addCell(15000)->addText($service->whocall);
$table1->addCell(15000)->addText($service->client_name);
$table1->addCell(15000)->addText($service->getKind()->name);
$table1->addCell(15000)->addText($service->getStatus()->name);
$table1->addCell(15000)->addText(number_format($service->price,2,".",","));
$table1->addCell(15000)->addText($service->created_at);
$total += $service->price;
}

$word->addTableStyle('table1', $styleTable,$styleFirstRow);
/// datos bancarios
$section1->addText("");
$section1->addText("");
$section1->addText("COSTO TOTAL: $ ".number_format($total,2,".",","),array("size"=>18));
$section1->addText("<br><br>Villahermosa Cárdenas km 161 + 200, Col. Cárdenas, Centro");

$filebase = "reten-".time();
$filename = $filebase.".docx";
$filepdf = $filebase.".pdf";


#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");

$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($word , 'PDF');
$xmlWriter->save($filepdf); 


//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filepdf'");
readfile($filepdf); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file
unlink($filepdf);  // remove temp file



?>