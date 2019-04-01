<?php

//print_r($_GET);
if(Core::$user->kind==1){
$operation = OperationData::getById($_GET["opid"]);
$operation->del();
}else{
	Core::alert("No tienes permisos para realizar esta operacion!");
}

print "<script>window.location='index.php?view=$_GET[ref]&product_id=$_GET[pid]&stock=$_GET[stock]';</script>";

?>