<?php
if(isset($_SESSION["user_id"])){
$product = ProductData::getById($_GET["id"]);
if($product!=null){
QRcode::png("product-$product->id");
}else{
	echo "404!";
}
}


?>