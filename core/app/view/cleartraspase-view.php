<?php
if(isset($_GET["product_id"])){
	$cart=$_SESSION["traspase"];
	if(count($cart)==1){
	 unset($_SESSION["traspase"]);
	}else{
		$ncart = null;
		$nx=0;
		foreach($cart as $c){
			if($c["product_id"]!=$_GET["product_id"]){
				$ncart[$nx]= $c;
			}
			$nx++;
		}
		$_SESSION["traspase"] = $ncart;
	}

}else{
 unset($_SESSION["traspase"]);
}

print "<script>window.location='index.php?view=traspase';</script>";

?>