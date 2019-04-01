<?php
$operations = OperationData::getAllByProductId($_GET["id"]);

/*
$operations = OperationData::getAllByProductId($_GET["id"]);

foreach ($operations as $op) {
	$op->del();
}
*/
if(count($operations)==0){
$product = ProductData::getById($_GET["id"]);
$product->del();
}else{
	Core::alert("No se puede eliminar el producto por que tiene datos asociados.");
}

Core::redir("./index.php?view=products");
?>