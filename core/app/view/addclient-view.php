<?php

if(count($_POST)>0){
	$user = new PersonData();
	$user->no = $_POST["no"];
	$user->name = $_POST["name"];
	$user->lastname = $_POST["lastname"];
	$user->address1 = $_POST["address1"];
	$user->email1 = $_POST["email1"];
	$user->phone1 = $_POST["phone1"];
	$user->credit_limit = $_POST["credit_limit"];


	$user->is_active_access = isset($_POST["is_active_access"])?1:0;
	$user->has_credit = isset($_POST["has_credit"])?1:0;
	$user->password = sha1(md5($_POST["password"]));

	$user->add_client();

print "<script>window.location='index.php?view=clients';</script>";


}


?>