<?php

if(isset($_POST["q"]) && !is_numeric($_POST["q"])){
Core::alert("Valor invalido!");
Core::redir("./?view=sell");
}

if(!isset($_SESSION["cart"])){


	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"]);
	$_SESSION["cart"] = array($product);


	$cart = $_SESSION["cart"];

///////////////////////////////////////////////////////////////////
		$num_succ = 0;
		$process=false;
		$errors = array();
		foreach($cart as $c){

			///
			$product = ProductData::getById($c["product_id"]);
			$q = OperationData::getQByStock($c["product_id"],StockData::getPrincipal()->id);
//			echo ">>".$q;
			if($product->kind==2||$c["q"]<=$q){
				$num_succ++;


			}else{
				$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
				$errors[count($errors)] = $error;
			}

		}
///////////////////////////////////////////////////////////////////

//echo $num_succ;
if($num_succ==count($cart)){
	$process = true;
}
if($process==false){
	unset($_SESSION["cart"]);
$_SESSION["errors"] = $errors;
	?>	
<script>
	window.location="index.php?view=sell";
</script>
<?php
}




}else {

$found = false;
$cart = $_SESSION["cart"];
$index=0;

			$product = ProductData::getById($_POST["product_id"]);
			$q = OperationData::getQByStock($_POST["product_id"],StockData::getPrincipal()->id);





$can = true;
if($product->kind==2||$_POST["q"]<=$q){
}else{
	$error = array("product_id"=>$_POST["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
	$errors[count($errors)] = $error;
	$can=false;
}

if($can==false){
$_SESSION["errors"] = $errors;
	?>	
<script>
	window.location="index.php?view=sell";
</script>
<?php
}
?>

<?php
if($can==true){
foreach($cart as $c){
	if($c["product_id"]==$_POST["product_id"]){
		echo "found";
		$found=true;
		break;
	}
	$index++;
//	print_r($c);
//	print "<br>";
}

if($found==true){
	$q1 = $cart[$index]["q"];
	$q2 = $_POST["q"];
	$cart[$index]["q"]=$q1+$q2;
	$_SESSION["cart"] = $cart;
}

if($found==false){
    $nc = count($cart);
	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"]);
	$cart[$nc] = $product;
//	print_r($cart);
	$_SESSION["cart"] = $cart;
}

}
}
 print "<script>window.location='index.php?view=sell';</script>";
// unset($_SESSION["cart"]);

?>