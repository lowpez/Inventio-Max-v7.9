<?php

if(count($_POST)>0){
	$user = StockData::getById($_POST["id"]);
	$user->name = $_POST["name"];
	$user->address = $_POST["address"];
	$user->phone = $_POST["phone"];
	$user->email = $_POST["email"];
	$user->update();
print "<script>window.location='index.php?view=stocks';</script>";


}


?>