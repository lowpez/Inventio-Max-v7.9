<?php

if(count($_POST)>0){
	$user = new SpendData();
	$user->name = $_POST["name"];
	$user->price = $_POST["price"];
	$user->add();

print "<script>window.location='index.php?view=spends';</script>";


}


?>