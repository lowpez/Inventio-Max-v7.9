<?php

if($_POST["stock_id"]!=""){
	$_SESSION["stock_id"]=$_POST["stock_id"];
	Core::redir("./?view=traspase");
}

?>