<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
	$user = new CategoryData();
	$user->name = $_POST["name"];
	$user->add();
Core::redir("./index.php?view=categories&opt=all");
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="upd"){
	$user = CategoryData::getById($_POST["user_id"]);
	$user->name = $_POST["name"];
	$user->update();
	Core::redir("./index.php?view=categories&opt=all");

}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
$category = CategoryData::getById($_GET["id"]);

$products = ProductData::getAllByCategoryId($category->id);
foreach ($products as $product) {
	$product->del_category();
}

$category->del();
Core::redir("./index.php?view=categories&opt=all");
}


?>