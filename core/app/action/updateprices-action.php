<?php

if(count($_POST)>0){
	$product_id = $_POST["product_id"];
	$stocks = StockData::getAll();
	foreach($stocks as $stock){
		$px = PriceData::getByPS($product_id,$stock->id);
		if($px!=null){
			$px->del();
			$px = new PriceData();
			$px->price_out=  $_POST["price_".$stock->id."_".$product_id];
			$px->product_id= $product_id;
			$px->stock_id = $stock->id;
			$px->add();
		}else{
			$px = new PriceData();
			$px->price_out=  $_POST["price_".$stock->id."_".$product_id];
			$px->product_id= $product_id;
			$px->stock_id = $stock->id;
			$px->add();			
		}

	}
	
	print "<script>window.location='index.php?view=prices';</script>";


}


?>