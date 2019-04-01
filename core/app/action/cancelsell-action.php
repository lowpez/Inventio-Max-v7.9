<?php

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);

foreach ($operations as $op) {
	$op->cancel();
}

$sell->cancel();
Core::redir("./index.php?view=sells");

?>