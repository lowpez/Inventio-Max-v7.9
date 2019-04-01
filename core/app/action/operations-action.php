<?php

if(isset($_GET["opt"]) && $_GET["opt"]!=""){
	$opt = $_GET["opt"];
	if($opt=="add"){

$ins = SavingData::SumByKind(1);
$outs = SavingData::SumByKind(2);
$avaiable = $ins->s-$outs->s;
if($_POST["kind"]==2&& $avaiable<$_POST["amount"]){
Core::alert("Error, no se cuenta con el monto solicitado!");
}else{

		$op = new SavingData();
		$op->concept = $_POST["concept"];
		$op->date_at = $_POST["date_at"];
		$op->description = $_POST["description"];
		$op->amount = $_POST["amount"];
		$op->kind = $_POST["kind"];
		$op->add();
		Core::redir("./?view=smallbox&opt=all");
	}
		Core::redir("./?view=smallbox&opt=all");
	}
	else if($opt=="update"){
		$op = SavingData::getById($_POST["id"]);
$ins = SavingData::SumByKind(1);
$outs = SavingData::SumByKind(2);
$avaiable = $ins->s-$outs->s;
$diff = $_POST["amount"]-$op->amount;
//$avaiable-($diff);
if($diff>0&&($avaiable-$diff)>0&&$op->kind==2){
		$op->concept = $_POST["concept"];
		$op->date_at = $_POST["date_at"];
		$op->description = $_POST["description"];
		$op->amount = $_POST["amount"];
		$op->update();
	}else{
		Core::alert("No se puede efectuar la actualizacion!");
	}

		Core::redir("./?view=smallbox&opt=all");
	}
	else if($opt=="del"){
		$op = SavingData::getById($_GET["id"]);

$ins = SavingData::SumByKind(1);
$outs = SavingData::SumByKind(2);
$avaiable = $ins->s-$outs->s;
if($op->kind==2||($op->kind==1&&$avaiable-$op->amount>0)){		
		$op->del();
}else{
		Core::alert("No se puede efectuar la actualizacion!");

}
		Core::redir("./?view=smallbox&opt=all");

	}
}




?>