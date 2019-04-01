<?php



?>

<?php if((isset($_GET["product_name"]) && $_GET["product_name"]!="") || (isset($_GET["product_code"]) && $_GET["product_code"]!="") ):?>
<?php
$go = $_GET["go"];
$search  ="";
if($go=="code"){ $search=$_GET["product_code"]; }
else if($go=="name"){ $search=$_GET["product_name"]; }
$products = ProductData::getLike($search);
if(count($products)>0){
	?>
<h3>Resultados de la Busqueda</h3>
<div class="box box-primary">
<table class="table table-bordered table-hover">
	<thead>
		<th>Codigo</th>
		<th>Nombre</th>
		<th>Unidad</th>
		<th>Precio unitario</th>
		<th>En inventario</th>
		<th>Cantidad</th>
	</thead>
	<?php
$products_in_cero=0;
	 foreach($products as $product):
$q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
	?>
	<?php 
	if($q==0):?>
		
	<tr>
		<td style="width:80px;"><?php echo $product->id; ?></td>
		<td><?php echo $product->name; ?></td>
		<td><?php echo $product->unit; ?></td>
		<td><b>$<?php echo $product->price_out; ?></b></td>
		<td>
			<?php echo $q; ?>
		</td>
		<td style="width:250px;"><form method="post" action="index.php?action=addtocart2">
		<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">

<div class="input-group">
		<input type="" class="form-control" required name="q" placeholder="Cantidad ...">
      <span class="input-group-btn">
		<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Agregar</button>
      </span>
    </div>


		</form></td>
	</tr>

<?php else:$products_in_cero++;
?>
<?php  endif; ?>
	<?php endforeach;?>
</table>
</div>
<?php if($products_in_cero>0){ 
if(Core::$user->kind==1){
	echo "<p class='alert alert-warning'>Solo se muestran productos que tengan 0 existencias en el inventario.</p>"; }
}
?>

	<?php
}else{
	echo "<br><p class='alert alert-danger'>No se encontro el producto</p>";
}
?>
<hr><br>
<?php else:
?>
<?php endif; ?>