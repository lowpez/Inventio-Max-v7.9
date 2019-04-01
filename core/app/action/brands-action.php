<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
	$user = new BrandData();
	$user->name = $_POST["name"];
	$user->add();
Core::redir("./index.php?view=brands&opt=all");
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="upd"){
	$user = BrandData::getById($_POST["user_id"]);
	$user->name = $_POST["name"];
	$user->update();
	Core::redir("./index.php?view=brands&opt=all");

}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
$category = BrandData::getById($_GET["id"]);
$products = ProductData::getAllByCategoryId($category->id);
foreach ($products as $product) {
	$product->del_brand();
}

$category->del();
Core::redir("./index.php?view=brands&opt=all");
}


?>