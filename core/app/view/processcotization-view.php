<section class="content">

<h1>Procesar Cotizacion</h1>
<p class="btn btn-info">Convertir una cotizacion en un venta real.</p>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;

foreach($operations as $operation){
	$product  = $operation->getProduct();
	$total+=$operation->q*$product->price_out;
}
//$total;
?>


<form method="post" class="form-horizontal" id="processcotization" action="index.php?action=processcotization">
<input type="hidden" name="cotization_id" value="<?php echo $_GET["id"]; ?>">
<input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">

<div class="row">
<div class="col-md-3">
    <label class="control-label">Almacen</label>
    <div class="col-lg-12">
    <h4 class=""><?php 
    echo StockData::getPrincipal()->name;
    ?></h4>
    </div>
  </div>

<div class="col-md-3">
    <label class="control-label">Cliente</label>
    <div class="col-lg-12">
    <?php 
$clients = PersonData::getClients();
    ?>
    <select name="client_id" class="form-control">
    <option value="">-- NINGUNO --</option>
    <?php foreach($clients as $client):?>
    	<option value="<?php echo $client->id;?>"><?php echo $client->name." ".$client->lastname;?></option>
    <?php endforeach;?>
    	</select>
    </div>
  </div>
<div class="col-md-3">
    <label class="control-label">Descuento</label>
    <div class="col-lg-12">
      <input type="text" name="discount" class="form-control" required value="0" id="discount" placeholder="Descuento">
    </div>
  </div>
 <div class="col-md-3">
    <label class="control-label">Efectivo</label>
    <div class="col-lg-12">
      <input type="text" name="money" required class="form-control" id="money" placeholder="Efectivo">
    </div>
  </div>
  </div>
<div class="row">

<div class="col-md-4">
    <label class="control-label">Pago</label>
    <div class="col-lg-12">
    <?php 
$clients = PData::getAll();
    ?>
    <select name="p_id" class="form-control">
    <?php foreach($clients as $client):?>
    	<option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
    	</select>
    </div>
  </div>
<div class="col-md-4">
    <label class="control-label">Entrega</label>

    <div class="col-lg-12">
    <?php 
$clients = DData::getAll();
    ?>
    <select name="d_id" class="form-control">
    <?php foreach($clients as $client):?>
    	<option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
    	</select>
    </div>
  </div>
<div class="col-md-4">
    <label class="control-label">&nbsp;</label>
<div class="col-lg-12">
        <button class="btn btn-primary btn-block"><i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-usd"></i> Procesar</button>
        </div>
</div>
</div>
</form>
<br>




<div class="box box-primary">
<br><table class="table table-bordered table-hover">
	<thead>
		<th>Codigo</th>
		<th>Cantidad</th>
		<th>Nombre del Producto</th>
		<th>Precio Unitario</th>
		<th>Total</th>

	</thead>
<?php
	foreach($operations as $operation){
		$product  = $operation->getProduct();
?>
<tr>
	<td><?php echo $product->id ;?></td>
	<td><?php echo $operation->q ;?></td>
	<td><?php echo $product->name ;?></td>
	<td>$ <?php echo number_format($product->price_out,2,".",",") ;?></td>
	<td><b>$ <?php echo number_format($operation->q*$product->price_out,2,".",",");
	//$total+=$operation->q*$product->price_out;?></b></td>
</tr>
<?php
	}
	?>
</table>
</div>
<br><br><h1>Total: $ <?php echo number_format($total,2,'.',','); ?></h1>
	<?php

?>	
<?php else:?>
	501 Internal Error
<?php endif; ?>
</section>
<script>
	$("#processcotization").submit(function(e){
		discount = $("#discount").val();
		money = $("#money").val();
		if(money<(<?php echo $total;?>-discount)){
			alert("Efectivo insificiente!");
			e.preventDefault();
		}else{
			if(discount==""){ discount=0;}
			go = confirm("Cambio: $"+(money-(<?php echo $total;?>-discount ) ) );
			if(go){}
				else{e.preventDefault();}
		}
	});
</script>