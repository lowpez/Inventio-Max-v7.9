<?php

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);

foreach ($operations as $op) {
	foreach (OperationData::getAllBySQL(" where operation_from_id=$op->id") as $opx) {
		$opx->del();
	}
	$op->del();

}

$sell->del();
Core::redir("./index.php?view=trasps");

?>