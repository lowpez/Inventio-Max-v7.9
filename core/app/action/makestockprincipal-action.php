<?php

$user = UserData::getById($_SESSION["user_id"]);
if($user->kind==1){
	StockData::unset_principal();
	StockData::set_principal($_GET["id"]);
	Core::redir("./?view=stocks");
}


?>