<?php

if(isset($_POST["q"]) && !is_numeric($_POST["q"])){
Core::alert("Valor invalido!");
Core::redir("./?view=reandsell");
}

if(!isset($_SESSION["cart2"])){


	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"]);
	$_SESSION["cart2"] = array($product);


	$cart = $_SESSION["cart2"];
	$process= true;


}else {

$found = false;
$cart = $_SESSION["cart2"];
$index=0;





$can = true;

if($can==false){
$_SESSION["errors"] = $errors;
	?>	
<script>
	window.location="index.php?view=reandsell";
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
	$_SESSION["cart2"] = $cart;
}

if($found==false){
    $nc = count($cart);
	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"]);
	$cart[$nc] = $product;
//	print_r($cart);
	$_SESSION["cart2"] = $cart;
}

}
}
 print "<script>window.location='index.php?view=reandsell';</script>";
// unset($_SESSION["cart2"]);

?>