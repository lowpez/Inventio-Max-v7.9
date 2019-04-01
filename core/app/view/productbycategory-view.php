        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Productos
          </h1>

        </section>

        <!-- Main content -->
        <section class="content">


<?php
$products = ProductData::getAllByCategoryId($_GET["id"]);
if(count($products)>0){


	?>



<div class="box">
  <div class="box-header">
    <h3 class="box-title">Productos</h3>

  </div><!-- /.box-header -->
  <div class="box-body no-padding">

<table class="table table-bordered table-hover">
	<thead>
		<th>Codigo</th>
		<th>Imagen</th>
		<th>Nombre</th>
		<th>Precio Entrada</th>
		<th>Precio Salida</th>
		<th>Categoria</th>
		<th>Minima</th>
		<th>Activo</th>
		<th></th>
	</thead>
	<?php foreach($products as $product):?>
	<tr>
		<td><?php echo $product->barcode; ?></td>
		<td>
			<?php if($product->image!=""):?>
				<img src="storage/products/<?php echo $product->image;?>" style="width:64px;">
			<?php endif;?>
		</td>
		<td><?php echo $product->name; ?></td>
		<td>$ <?php echo number_format($product->price_in,2,'.',','); ?></td>
		<td>$ <?php echo number_format($product->price_out,2,'.',','); ?></td>
		<td><?php if($product->category_id!=null){echo $product->getCategory()->name;}else{ echo "<center>----</center>"; }  ?></td>
		<td><?php echo $product->inventary_min; ?></td>
		<td><?php if($product->is_active): ?><i class="fa fa-check"></i><?php endif;?></td>
		

		<td style="width:70px;">
		<a href="index.php?view=editproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
		<a href="index.php?view=delproduct&id=<?php echo $product->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
		</td>
	</tr>
	<?php endforeach;?>
</table>
  </div><!-- /.box-body -->
</div><!-- /.box -->



	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay productos</h2>
		<p>No se han agregado productos a la base de datos, puedes agregar uno dando click en el boton <b>"Agregar Producto"</p>.

		<a href="./?view=categories&opt=all" class="btn btn-default"><i class="fa fa-arrow-left"></i> Regresar</a>

		</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>

</section>
