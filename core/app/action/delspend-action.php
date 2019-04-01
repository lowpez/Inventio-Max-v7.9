<?php

$category = SpendData::getById($_GET["id"]);

$category->del();
Core::redir("./index.php?view=spends");


?>