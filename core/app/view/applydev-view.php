<?php

$sell = SellData::getById($_GET["id"]);
//$sell_from = SellData::getById($sell->sell_from_id);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$operations_from = OperationData::getAllProductsBySellId($sell->sell_from_id);

$sell->status=1;
foreach ($operations as $op) {
	$op->status=1;
	$op->update_status();
	foreach($operations_from as $opf){
		if($opf->product_id==$op->product_id){
				 $opf->q -= $op->q;
				 $opf->update_q();
				 break;
		}
	}


				 
				 /// agregamos la devolucion como un gasto
				 	$product = ProductData::getById($op->product_id);
				 	$user = new SpendData();
					$user->name = "Devolucion - Venta - ".$sell->id."  - ".$product->name;
					$user->price = $product->price_out*$op->q;
					$user->add();


}


$sell->update_status();
Core::redir("./index.php?view=devs");

?>