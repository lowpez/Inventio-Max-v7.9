<?php

include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";

include "core/app/model/UserData.php";
include "core/app/model/SellData.php";
include "core/app/model/OperationData.php";
include "core/app/model/ProductData.php";
include "core/app/model/StockData.php";
include "core/app/model/ConfigurationData.php";

include "fpdf/fpdf.php";
session_start();
if(isset($_SESSION["user_id"])){ Core::$user = UserData::getById($_SESSION["user_id"]); }
$title = ConfigurationData::getByPreffix("ticket_title")->val;
$currency = ConfigurationData::getByPreffix("currency")->val;

$sell = SellData::getById($_GET["id"]);
$stock_to = $sell->getStockTo();
$stock_from = $sell->getStockFrom();

$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$user = $sell->getUser();


$pdf = new FPDF($orientation='P',$unit='mm', array(45,350));
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->setXY(5,0);
$pdf->setY(2);
$pdf->setX(2);
$pdf->Cell(5,5,strtoupper($title));
$pdf->SetFont('Arial','B',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setX(2);
$pdf->Cell(5,11,strtoupper($stock_from->address));
$pdf->setX(2);
$pdf->Cell(5,17,"TEL. ".strtoupper($stock_from->phone));
$pdf->setX(2);
$pdf->Cell(5,23,'-------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,29,'CANT.  ARTICULO       PRECIO               TOTAL');

$total =0;
$off = 35;
foreach($operations as $op){
	if($op->operation_type_id==2){
$product = $op->getProduct();
$pdf->setX(2);
$pdf->Cell(5,$off,"$op->q");
$pdf->setX(6);
$pdf->Cell(35,$off,  strtoupper(substr($product->name, 0,12)) );
$pdf->setX(20);
$pdf->Cell(11,$off,  $currency." ".number_format($product->price_out,2,".",",") ,0,0,"R");
$pdf->setX(32);
$pdf->Cell(11,$off,  $currency." ".number_format($op->q*$product->price_out,2,".",",") ,0,0,"R");

//    ".."  ".number_format($op->q*$product->price_out,2,".",","));
$total += $op->q*$product->price_out;
$off+=6;
}
}
$pdf->setX(2);
$pdf->Cell(5,$off+18,"TOTAL: " );
$pdf->setX(38);
$pdf->Cell(5,$off+18,$currency." ".number_format($total - ($total*$sell->discount/100),2,".",","),0,0,"R");


$pdf->setX(2);
$pdf->Cell(5,$off+36,'-------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,$off+42,"SUCURSAL ORIGEN: ".strtoupper($stock_from->name));
$pdf->setX(2);
$pdf->Cell(5,$off+48,"SUCURSAL DESTINO: ".strtoupper($stock_to->name));
$pdf->setX(2);
$pdf->Cell(5,$off+54,"FOLIO: ".$sell->id.' - FECHA: '.$sell->created_at);
$pdf->setX(2);
$pdf->Cell(5,$off+60,'ATENDIDO POR '.strtoupper($user->name." ".$user->lastname));
$pdf->setX(2);
$pdf->Cell(5,$off+66,'GRACIAS POR TU COMPRA ');


$pdf->output();
