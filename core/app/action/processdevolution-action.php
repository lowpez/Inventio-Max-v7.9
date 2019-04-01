<?php

if(!empty($_POST)){
	// print_r($_POST);
	$sell = SellData::getById($_POST["sell_id"]);
	$operations = OperationData::getAllProductsBySellId($sell->id);

	$dev = new SellData();
	$dev->stock_to_id = $sell->stock_to_id;
	$dev->sell_from_id = $_POST["sell_id"];
	$dev->user_id = $_SESSION["user_id"];
	$dev->operation_type_id = 5; // devolution
	$dev->status=0;
	$d = $dev->add_de();


	foreach ($operations as $op) {
		if(isset($_POST["op_".$op->id])){
			if($_POST["op_".$op->id]>0 || $_POST["op_".$op->id]<=$op->q){
//				print_r($op);



  			 	 $product = ProductData::getById($op->product_id);
				 $dev_op = new OperationData();
				 $dev_op->product_id = $op->product_id ;
				 $dev_op->price_in = $product->price_in;
				 $dev_op->price_out= $product->price_out;
//				 $dev_op->stock_id = StockData::getPrincipal()->id;
				 $dev_op->stock_id = $sell->stock_to_id;
				 $dev_op->operation_type_id=OperationTypeData::getByName("devolucion")->id;
				 $dev_op->sell_id=$d[1];
				 $dev_op->status=0;
				 $dev_op->q= $_POST["op_".$op->id];
				 $add = $dev_op->add();

//				 $op->q -= $_POST["op_".$op->id];
//				 $op->update_q();

				 
				 /// agregamos la devolucion como un gasto
//				 	$product = ProductData::getById($op->product_id);
//				 	$user = new SpendData();
//					$user->name = "Devolucion - Venta - ".$sell->id."  - ".$product->name;
//					$user->price = $product->price_out*$_POST["op_".$op->id];
//					$user->add();

			}
		}

	}
	Core::alert("Devolucion agregada!");
	Core::redir("./?view=dev");

}

?>