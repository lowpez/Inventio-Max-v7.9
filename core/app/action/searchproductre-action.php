<?php if(isset($_GET["product"])):?>
	<?php
$products = ProductData::getLike2($_GET["product"]);
if(count($products)>0){
	?>
<h3>Resultados de la Busqueda</h3>
<div class="box box-primary">
<table class="table table-bordered table-hover">
	<thead>
		<th>Codigo</th>
		<th>Nombre</th>
		<th>Unidad</th>
		<th>En inventario</th>
		<th>Entrada, Salidad, Cantidad</th>
	</thead>
	<?php
$products_in_cero=0;
	 foreach($products as $product):
$q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
	?>
	<tr class="<?php if($q<=$product->inventary_min){ echo "danger"; }?>">
		<td style="width:80px;"><?php echo $product->code; ?></td>
		<td><?php echo $product->name; ?></td>
		<td><?php echo $product->unit; ?></td>
		<td>
			<?php echo $q; ?>
		</td>
		<td>
		<form method="post"  id="addtore<?php echo $product->id; ?>">
		<div class="row">
		<div class="col-md-3">
<div class="input-group">
  <span class="input-group-addon"><?php echo Core::$symbol; ?></span>
  <input type="text" class="form-control" name="price_in" placeholder="$ Entrada" value="<?php echo $product->price_in; ?>">
</div>
</div>
		<div class="col-md-3">

<div class="input-group">
  <span class="input-group-addon"><?php echo Core::$symbol; ?></span>
  <input type="text" class="form-control" name="price_out" placeholder="$ Salida" value="<?php echo $product->price_out; ?>">
</div>
</div>
		<div class="col-md-3">


		<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
		<input type="text" class="form-control" required name="q" id="re_q<?php echo $product->id; ?>" placeholder="Cantidad de producto ...">
</div>
		<div class="col-md-3">
		<button type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Agregar</button>
</div>
</div>
	</form>
		</td>
	</tr>
<script>
		$("#addtore<?php echo $product->id; ?>").on("submit",function(e){
		e.preventDefault();
			$.post("./?view=addtore",$("#addtore<?php echo $product->id; ?>").serialize(),function(data){
				$.get("./?action=cartofre",null,function(data2){
					$("#cartofre").html(data2);
				});
			});
		$("#re_q<?php echo $product->id; ?>").val("");

	});
</script>
	<?php endforeach;?>
</table>
</div>
	<?php
}
?>
<?php else:
?>

<?php endif; ?>
