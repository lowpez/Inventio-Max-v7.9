<?php


if($_POST["q"]!="" || $_POST["q"]!="0"){
	$q=OperationData::getQByStock($_POST["product_id"],$_POST["stock"]);
	if($_POST["q"]<=$q){


			$product = ProductData::getById($_POST["product_id"]);
			$op = new OperationData();
			$op->price_in = $product->price_in;
			$op->price_out = $product->price_out;
			$op->product_id = $_POST["product_id"] ;

			$op->operation_type_id=2;//OperationTypeData::getByName($operation_type)->id;
			$op->stock_id = $_POST["stock"];//StockData::getPrincipal()->id;
			$op->sell_id="NULL";
			$op->q= $_POST["q"];

			$add = $op->add();		

/*
 $op = new OperationData();
 $op->product_id = $_POST["product_id"] ;
 $op->operation_type_id=OperationTypeData::getByName("salida")->id;
 $op->q= $_POST["q"];
 $op->sell_id="NULL";
$op->is_oficial=1;
$op->add();
*/




Core::redir("./?view=inventary&stock=".$_POST["stock"]);
}else{
	Core::alert("Error!");
Core::redir("./?view=inventarysub&product_id=$_POST[product_id]&stock=".$_POST["stock"]);

}

}


?>