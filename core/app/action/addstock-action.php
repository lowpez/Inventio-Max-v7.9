<?php

if(count($_POST)>0){
	$user = new StockData();
	$user->name = $_POST["name"];
	$user->address = $_POST["address"];
	$user->phone = $_POST["phone"];
	$user->email = $_POST["email"];
	$user->add();
	print "<script>window.location='index.php?view=stocks';</script>";


}


?>