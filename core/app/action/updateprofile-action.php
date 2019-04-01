<?php

if(count($_POST)>0){
	$user = UserData::getById($_POST["user_id"]);

  if(isset($_FILES["image"])){
    $image = new Upload($_FILES["image"]);
    if($image->uploaded){
      $image->Process("storage/profiles/");
      if($image->processed){
        $user->image = $image->file_dst_name;
      }
    }
  }
	$user->name = $_POST["name"];
	$user->lastname = $_POST["lastname"];
	$user->username = $_POST["username"];
	$user->email = $_POST["email"];
	$user->status = Core::$user->status;
	$user->comision = Core::$user->comision!=""?Core::$user->comision:"NULL";
	$user->stock_id = Core::$user->stock_id!=""?Core::$user->stock_id:"NULL";
	$user->update();

	if($_POST["password"]!=""){
		$user->password = sha1(md5($_POST["password"]));
		$user->update_passwd();
print "<script>alert('Se ha actualizado el password');</script>";

	}

print "<script>window.location='index.php?view=profile';</script>";


}


?>