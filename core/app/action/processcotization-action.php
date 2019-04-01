<?php

if(!empty($_POST)){
	$sell = SellData::getById($_POST["cotization_id"]);
	$operations = OperationData::getAllProductsBySellId($sell->id);

			$iva_val = ConfigurationData::getByPreffix("imp-val")->val;



	$sell->p_id = $_POST["p_id"];
	$sell->d_id = $_POST["d_id"];
	$sell->iva=  $iva_val;
	$sell->total = $_POST["total"];
	$sell->discount = $_POST["discount"];
	$sell->cash = $_POST["money"];
	$sell->stock_to_id = StockData::getPrincipal()->id;

	$sell->process_cotization();

	foreach($operations as $op){
		$op->set_draft(0);
	}


	Core::alert("Cotizacion Procesada Exitosamente!");
	Core::redir("./?view=sells");

}


?>