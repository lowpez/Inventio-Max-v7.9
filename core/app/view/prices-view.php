        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Administrar Precios de Venta por Sucursal
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

<div class="row">
	<div class="col-md-12">

<br><br>

<?php
$currency = ConfigurationData::getByPreffix("currency")->val;
$stocks = StockData::getAll();
$products = ProductData::getAll();
if(count($products)>0){
?>
<div class="box box-primary">
  <div class="box-header">
    <h3 class="box-title">Administrar Precios</h3>

  </div><!-- /.box-header -->
  <div class="box-body no-padding">
<div class="box-body table-responsive">
<table class="table  table-bordered datatable table-hover">
	<thead>
		<th>Codigo</th>
		<th>Imagen</th>
		<th>Nombre</th>
		<th>Precio Default</th>
		<th>Categoria</th>
		<th></th>
	</thead>
	<?php foreach($products as $product):
$price = $product->price_out;
  ?>
	<tr>
		<td><?php echo $product->code; ?></td>
		<td>
			<?php if($product->image!=""):?>
				<img src="storage/products/<?php echo $product->image;?>" style="width:64px;">
			<?php endif;?>
		</td>
		<td><?php echo $product->name; ?></td>
		<td><?php echo $currency; ?> <?php echo number_format($product->price_out,2,'.',','); ?></td>
		<td><?php if($product->category_id!=null){echo $product->getCategory()->name;}else{ echo "<center>----</center>"; }  ?></td>
		

		<td>
<form method="post" action="./?action=updateprices">
<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
<?php foreach($stocks as $stock):
$px = PriceData::getByPS($product->id,$stock->id);
if($px){
  $price=$px->price_out;
}

?>

<div class="input-group">
  <span class="input-group-addon" id="basic-addon1"><?php echo $stock->name; ?></span>
  <input type="text" required class="form-control" value="<?php echo $price; ?>" name="price_<?php echo $stock->id; ?>_<?php echo $product->id; ?>" placeholder="Precio en <?php echo $stock->name; ?>">
</div>
<?php endforeach; ?>
<input type="submit" value="Actualizar" class="btn btn-success">
</form>

		</td>
	</tr>
	<?php endforeach;?>
</table>
</div>
  </div><!-- /.box-body -->
</div><!-- /.box -->


	<?php
}else{
	?>
	<div class="alert alert-info">
		<h2>No hay productos</h2>
		<p>No se han agregado productos a la base de datos, puedes agregar uno dando click en el boton <b>"Agregar Producto"</b>.</p>
	</div>
	<?php
}

?>
	</div>
</div>
        </section><!-- /.content -->



<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("LISTADO DE PRODUCTOS", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "Codigo", dataKey: "code"}, 
    {title: "Nombre del Producto", dataKey: "name"}, 
    {title: "Precio de entrada", dataKey: "price_in"}, 
    {title: "Precio de Salida", dataKey: "price_out"}, 
];
var rows = [
  <?php foreach($products as $product):
  ?>
    {
      "id": "<?php echo $product->id; ?>",
      "code": "<?php echo $product->code; ?>",
      "name": "<?php echo $product->name; ?>",
      "price_in": "$ <?php echo number_format($product->price_in,2,'.',',');?>",
      "price_out": "$ <?php echo number_format($product->price_out,2,'.',',');?>",
      },
 <?php endforeach; ?>
];
doc.autoTable(columns, rows, {
    theme: 'grid',
    overflow:'linebreak',
    styles: { 
        fillColor: <?php echo Core::$pdf_table_fillcolor;?>
    },
    columnStyles: {
        id: {fillColor: <?php echo Core::$pdf_table_column_fillcolor;?>}
    },
    margin: {top: 100},
    afterPageContent: function(data) {
    }
});
doc.setFontSize(12);
doc.text("<?php echo Core::$pdf_footer;?>", 40, doc.autoTableEndPosY()+25);
<?php 
$con = ConfigurationData::getByPreffix("report_image");
if($con!=null && $con->val!=""):
?>
var img = new Image();
img.src= "storage/configuration/<?php echo $con->val;?>";
img.onload = function(){
doc.addImage(img, 'PNG', 495, 20, 60, 60,'mon');	
doc.save('products-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('products-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>

