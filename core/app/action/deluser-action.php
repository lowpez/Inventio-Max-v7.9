<?php
$user = UserData::getById($_SESSION["user_id"]);

if($user->kind==1){
	$userx  = UserData::getById($_GET["id"]);

	if($user->id!=$userx->id){
		$userx->del();
		Core::alert("Eliminado exitosamente!");
	}else{
		Core::alert("Error. No te puedes eliminar a ti mismo!");
	}

}else{
	Core::alert("Error. No tienes permisos!");
}

Core::redir("./?view=users");

?>