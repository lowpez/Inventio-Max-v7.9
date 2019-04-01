<?php

if(count($_POST)>0){

	$payment2 = new PaymentData();
 	$payment2->val = -1*$_POST["val"];
 	$payment2->sell_id = $_POST["sell_id"];
 	$payment2->person_id = $_POST["client_id"];
 	$payment2->add_payment();
	print "<script>window.location='index.php?view=credit';</script>";


}


?>