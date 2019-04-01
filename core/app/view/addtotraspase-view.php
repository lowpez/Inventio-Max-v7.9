<?php

if(!isset($_SESSION["traspase"])){


	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"]);
	$_SESSION["traspase"] = array($product);


	$cart = $_SESSION["traspase"];

///////////////////////////////////////////////////////////////////
		$num_succ = 0;
		$process=false;
		$errors = array();
		foreach($cart as $c){

			///
			$q = OperationData::getQByStock($c["product_id"],$_SESSION["stock_id"]);
//			echo ">>".$q;
			if($c["q"]<=$q){
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
	unset($_SESSION["traspase"]);
$_SESSION["errors"] = $errors;
	?>	
<script>
	window.location="index.php?view=traspase";
</script>
<?php
}




}else {

$found = false;
$cart = $_SESSION["traspase"];
$index=0;

			$q = OperationData::getQByStock($_POST["product_id"],$_SESSION["stock_id"]);





$can = true;
if($_POST["q"]<=$q){
}else{
	$error = array("product_id"=>$_POST["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
	$errors[count($errors)] = $error;
	$can=false;
}

if($can==false){
$_SESSION["errors"] = $errors;
	?>	
<script>
	window.location="index.php?view=traspase";
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
	$_SESSION["traspase"] = $cart;
}

if($found==false){
    $nc = count($cart);
	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"]);
	$cart[$nc] = $product;
//	print_r($cart);
	$_SESSION["traspase"] = $cart;
}

}
}
 print "<script>window.location='index.php?view=traspase';</script>";
// unset($_SESSION["traspase"]);

?>