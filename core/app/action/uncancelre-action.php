<?php

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);

foreach ($operations as $op) {
	$op->uncancel();
}

$sell->uncancel();
Core::redir("./index.php?view=res");

?>