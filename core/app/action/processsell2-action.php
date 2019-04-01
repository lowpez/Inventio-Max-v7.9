<?php
date_default_timezone_set("America/Mexico_City");
if(isset($_SESSION["cart2"])){
	$cart = $_SESSION["cart2"];
	if(count($cart)>0){

	$process = true;







//////////////////////////////////
		if($process==true){
			$iva_val = ConfigurationData::getByPreffix("imp-val")->val;


/////////// abastecimiento
		    $y = new YYData();
			$yy = $y->add();
			$sell = new SellData();
			$sell->ref_id= $yy[1];
			$sell->user_id = $_SESSION["user_id"];
			$sell->p_id = $_POST["p_id"];
			$sell->d_id = $_POST["d_id"];
			$sell->total = $_POST["total"];
			$sell->stock_to_id = StockData::getPrincipal()->id;;
			$sell->person_id=$_POST["client_id"]!=""?$_POST["client_id"]:"NULL";

			$s = $sell->add_re();


		foreach($cart as  $c){

			$operation_type = 1;
			if($_POST["d_id"]==2){ $operation_type= 3; // 3.- entrada-pendiente 
			}

			$product = ProductData::getById($c["product_id"]);

			$op = new OperationData();
			$op->price_in = $product->price_in;
			$op->price_out = $product->price_out;
			$op->stock_id = StockData::getPrincipal()->id;;
			 $op->product_id = $c["product_id"] ;
			 $op->operation_type_id=$operation_type; // 1 - entrada
			 $op->sell_id=$s[1];
			 $op->q= $c["q"];

			$add = $op->add();			 		

		}
//////////////////// venta


			$x = new XXData();
			$xx = $x->add();
			$sell = new SellData();
			$sell->ref_id=$xx[1];
			$sell->user_id = $_SESSION["user_id"];

			$sell->p_id = $_POST["p_id"];
			$sell->d_id = $_POST["d_id"];
			$sell->iva=  $iva_val;
			$sell->total = $_POST["total"];
			$sell->discount = $_POST["discount"];
			$sell->stock_to_id = StockData::getPrincipal()->id;
			$sell->person_id=$_POST["client_id"]!=""?$_POST["client_id"]:"NULL";

			$s = $sell->add();

			 /// si es credito....
			 if($_POST["p_id"]==4){
			 	$payment = new PaymentData();
			 	$payment->sell_id = $s[1];
			 	$payment->val = ($_POST["total"]-$_POST["discount"]);
			 	$payment->person_id = $_POST["client_id"];
			 	$payment->add();
			 	if($_POST["money"]>0){
					$payment2 = new PaymentData();
				 	$payment2->val = -1*$_POST["money"];
				 	$payment2->person_id = $_POST["client_id"];
				 	$payment2->add_payment();
			 	}
			 }

		foreach($cart as  $c){
			$operation_type = "salida";
			if($_POST["d_id"]==2){ $operation_type="salida-pendiente"; }

			$product = ProductData::getById($c["product_id"]);
			$op = new OperationData();
			$op->price_in = $product->price_in;
			$op->price_out = $product->price_out;
			$op->product_id = $c["product_id"] ;

			$op->operation_type_id=OperationTypeData::getByName($operation_type)->id;
			$op->stock_id = StockData::getPrincipal()->id;
			$op->sell_id=$s[1];
			$op->q= $c["q"];
			if(isset($_POST["is_oficial"])){
				$op->is_oficial = 1;
			}

			$add = $op->add();			 		



////////////////// generando el mensaje
		$subject = "[".$s[1]."] Nueva venta en el inventario";
		$message = "<p>Se ha realizado una venta con Id = ".$s[1]."</p>";
$person_th="";
$person_td="";
$person = null;
if($_POST["client_id"]!=""){
	$person = PersonData::getById($_POST["client_id"]);
	$person_th="<td>Cliente</td>";
	$person_td="<td>".$person->name." ".$person->lastname."</td>";
}


		$message .= "<table border='1'><tr>
		<td>Id</td>
		$person_th
		<td>Almacen</td>
		<td>Estado de pago</td>
		<td>Estado de entrega</td>
		<td>Total</td>
		</tr>
<tr>
		<td>".$s[1]."</td>
		$person_td
		<td>".StockData::getById($sell->stock_to_id)->name."</td>
		<td>".PData::getById($sell->p_id)->name."</td>
		<td>".DData::getById($sell->d_id)->name."</td>
		<td> $".number_format($sell->total,2,".",",")."</td>
		</tr>
		</table>";
		$message.="<h3 style='color:#333;'>Resumen</h3>";
		$message.="<table border='1'><thead><th>Id</th><th>Codigo</th><th>Cantidad</th><th>Unidad</th><th>Producto</th><th>P.U</th><th>P. Total</th></thead>";
		foreach($cart as  $c){
			$message.="<tr>";
		$product = ProductData::getById($c["product_id"]);
		$message.="<td>".$product->id."</td>";
		$message.="<td>".$product->barcode."</td>";
		$message.="<td>".$c["q"]."</td>";
		$message.="<td>".$product->unit."</td>";
		$message.="<td>".$product->name."</td>";
		$message.="<td>$ ".number_format($product->price_out,2,".",",")."</td>";
		$message.="<td>$ ".number_format($c["q"]*$product->price_out,2,".",",")."</td>";
		$message.="</tr>";
		}
		$message.="</table>";
//////////////////
		if($subject!=""&&$message!=""){
				$m = new MailData();
				$m->open();
				// enviamos una copia del correo para el cliente
				if($person!=null){ $m->mail->AddAddress($person->email1); }
			    $m->mail->Subject = $subject;
			    $m->message = "<p>$message</p>";
			    $m->mail->IsHTML(true);
			    $m->send();
			}
//////////////////




$qx = OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
$subject="";
$message="";
$last = true;
if($qx==0){
			$subject = "[$product->name]".' No hay existencias';
			$message = "Hola, el producto <b>$product->name</b> no tiene existencias en el inventario";
			$last=false;
		}

if($qx<=$product->inventary_min/2 && $last){
	$subject = "[$product->name]".' Muy pocas existencias';
	$message = "Hola, el producto <b>$product->name</b> tiene muy pocas existencias en el inventario";
			$last=false;

}
if($qx<=$product->inventary_min && $last){
	$subject = "[$product->name]".' Pocas existencias';
	$message = "Hola, el producto <b>$product->name</b> tiene pocas existencias en el inventario";
			$last=false;
}
//////////////////
		if($subject!=""&&$message!=""){
				$m = new MailData();
				$m->open();
			    $m->mail->Subject = $subject;
			    $m->message = "<p>$message</p>";
			    $m->mail->IsHTML(true);
			    $m->send();
			}
//////////////////







////////////

		}
			unset($_SESSION["cart2"]);
			setcookie("selled","selled");////////////////////
//print "<script>window.location='index.php?view=onesell&id=$s[1]';</script>";
		}
	}
}



?>