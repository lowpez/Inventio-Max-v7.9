<?php

if(isset($_SESSION["user_id"])){
	$payment = PaymentData::getById($_GET["id"]);
	$payment->del();
}

Core::redir("./?view=credit");

?>