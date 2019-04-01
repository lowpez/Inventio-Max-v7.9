<?php

if(isset($_GET["id"])){
$sell = SellData::getById($_GET["id"]);
$sell->d_id=1;

$operations = OperationData::getAllProductsBySellId($_GET["id"]);
foreach ($operations as $op) {
	$op->operation_type_id=2;
	$op->update_type();
}

$sell->update_d();
Core::redir("./?view=bydeliver");
}
?>