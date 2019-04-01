<section class="content"> 
<div class="row">
	<div class="col-md-12">
	<a href="./?view=newcotization" class="btn btn-default pull-right"><i class="fa fa-asterisk"></i> Nueva cotizacion</a>
		<h1><i class='fa fa-square-o'></i> Cotizaciones</h1>
		<div class="clearfix"></div>


<?php
$products=null;
if(isset($_SESSION["client_id"])){
	$products = SellData::getCotizationsByClientId($_SESSION["client_id"]);
}else if(isset($_SESSION["user_id"])){
		$products = SellData::getCotizations();

}

if(count($products)>0){

	?>
<br>
<div class="box box-primary">
<div class="box-header">
<h3 class="box-title">Cotizaciones</h3></div>
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>Folio</th>
		<th>Producto</th>
		<th>Pago</th>
		<th>Entrega</th>
		<th>Total</th>
		<th>Fecha</th>
		<th></th>
	</thead>
	<?php foreach($products as $sell):?>

	<tr>
		<td style="width:30px;">
		<a href="index.php?view=onecotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>
		<td>#<?php echo $sell->id; ?></td>

		<td>


<?php
$operations = OperationData::getAllProductsBySellId($sell->id);
echo count($operations);
?>
</td>
<td><?php echo $sell->getP()->name; ?></td>
<td><?php echo $sell->getD()->name; ?></td>
		<td>

<?php
//$total= $sell->total-$sell->discount;
	$total=0;
	foreach($operations as $operation){
		$product  = $operation->getProduct();
		$total += $operation->q*$product->price_out;
	}
		echo "<b>". Core::$symbol." ".number_format($total,2,".",",")."</b>";

?>			

		</td>
		<td><?php echo $sell->created_at; ?></td>
		<td style="width:120px;">
		<?php if(isset($_SESSION["user_id"])):?>
<a href="index.php?view=processcotization&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Procesar</a>
				<?php endif;?>
		<a href="index.php?view=delsell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
		</td>
	</tr>

<?php endforeach; ?>

</table>
</div>

<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay cotizaciones</h2>
		<p>No se ha realizado ninguna cotizacion.</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section>