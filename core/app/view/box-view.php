<section class="content">
<div class="row">
	<div class="col-md-12">
<div class="btn-group pull-right">
<a href="./index.php?view=boxhistory" class="btn btn-primary "><i class="fa fa-clock-o"></i> Historial</a>
<a href="./index.php?view=processbox" class="btn btn-primary ">Procesar Ventas <i class="fa fa-arrow-right"></i></a>
</div>
		<h1><i class='fa fa-archive'></i> Caja</h1>
		<p>Al procesar ventas se generara un corte de caja para todas las ventas del almacen: <b><?php echo StockData::getPrincipal()->name;?></b></p>
		<div class="clearfix"></div>


<?php
$products = SellData::getSellsUnBoxed();
if(count($products)>0){
$total_total = 0;
?>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>Producto</th>
		<th>Total</th>
		<th>Almacen</th>
		<th>Fecha</th>
	</thead>
	<?php foreach($products as $sell):?>

	<tr>
		<td style="width:30px;">
</td>
		<td>

<?php
$operations = OperationData::getAllProductsBySellId($sell->id);
echo count($operations);
?>
</td>
		<td>

<?php
		$total_total += $sell->total-$sell->discount;
		echo "<b>".Core::$symbol." ".number_format($sell->total-$sell->discount,2,".",",")."</b>";

?>			

		</td>
		<td><?php echo $sell->getStockTo()->name; ?></td>
		<td><?php echo $sell->created_at; ?></td>
	</tr>

<?php endforeach; ?>

</table>
</div>
<h1>Total: <?php echo Core::$symbol." ".number_format($total_total,2,".",","); ?></h1>
	<?php
}else {

?>
	<div class="jumbotron">
		<h2>No hay ventas</h2>
		<p>No se ha realizado ninguna venta.</p>
	</div>

<?php } ?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section>