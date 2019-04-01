<?php

$product = StockData::getById($_GET["id"]);
$product->del();


Core::redir("./index.php?view=stocks");
?>