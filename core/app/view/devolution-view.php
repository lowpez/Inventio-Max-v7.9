<?php if(isset($_GET["id"]) && $_GET["id"]!=""):
$sell = SellData::getById($_GET["id"]);
if($sell->operation_type_id==2){}
else{
	Core::alert("Error, el folio no corresponde a una venta!");
	Core::redir("./?view=dev");
}
?>
<section class="content">

<h1>Devolucion</h1>
<p>Capture las unidades de los productos a devolver.</p>
<?php
if($sell==null){
	Core::alert("No se encontro la venta!");
	Core::redir("./?view=dev");
}
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;
?>
<div class="box box-primary">
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
<tr>
	<td style="width:150px;">Cliente</td>
	<td><?php echo $client->name." ".$client->lastname;?></td>
</tr>

<?php endif; ?>
<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
	<td>Atendido por</td>
	<td><?php echo $user->name." ".$user->lastname;?></td>
</tr>
<?php endif; ?>
</table>
</div>
<br>
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Productos</h3>
                </div><!-- /.box-header -->
       <form method="post" action="./?action=processdevolution">
                <input type="hidden" name="sell_id" value="<?php echo $sell->id; ?>" >
<table class="table table-bordered table-hover">
	<thead>
		<th>Codigo</th>
		<th>Devolucion</th>
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
	<td>
	<input type="number" name="op_<?php echo $operation->id?>" value="" class="form-control" min="1" max="<?php echo $operation->q ;?>">
	</td>
	<td><?php echo $operation->q ;?></td>
	<td><?php echo $product->name ;?></td>
	<td><?php echo Core::$symbol; ?> <?php echo number_format($product->price_out,2,".",",") ;?></td>
	<td><b><?php echo Core::$symbol; ?> <?php echo number_format($operation->q*$product->price_out,2,".",",");$total+=$operation->q*$product->price_out;?></b></td>
</tr>
<?php
	}
	?>
</table>
<div class="box-body">
<br><input type="submit" value="Realizar Devolucion" class="btn btn-primary">
<a href="./?view=dev" class="btn btn-danger" >Cancelar</a>
</div>
</form>

</div>
<br><br>
<!--
<div class="row">
<div class="col-md-4">
              <div class="box box-primary">
<table class="table table-bordered">
	<tr>
		<td><h4>Subtotal:</h4></td>
		<td><h4>$ <?php echo number_format($total,2,'.',','); ?></h4></td>
	</tr>
	<tr>
		<td><h4>Descuento:</h4></td>
		<td><h4>$ <?php echo number_format($total*($sell->discount/100),2,'.',','); ?></h4></td>
	</tr>
	<tr>
		<td><h4>Total:</h4></td>
		<td><h4>$ <?php echo number_format($total-$total*($sell->discount/100),2,'.',','); ?></h4></td>
	</tr>
</table>
</div>
</div>
</div>
-->
</section>
<?php else:?>
	501 Internal Error
<?php endif; ?>
