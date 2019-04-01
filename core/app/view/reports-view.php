<?php
$products = ProductData::getAll();
$stocks = StockData::getAll();
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Productos y Categorias</h1>

						<form>
						<input type="hidden" name="view" value="reports">
<div class="row">
<div class="col-md-2">

<select name="stock_id" required class="form-control">
	<option value="">-- SUCURSAL --</option>
	<?php foreach($stocks as $p):?>
	<option value="<?php echo $p->id;?>" <?php if(isset($_GET["stock_id"]) && $_GET["stock_id"]==$p->id){ echo "selected"; }?>><?php echo $p->name;?></option>
	<?php endforeach; ?>
</select>

</div>
<div class="col-md-3">

<select name="product_id" class="form-control" required>
	<option value="">--  PRODUCTOS --</option>
	<?php foreach($products as $p):?>
	<option value="<?php echo $p->id;?>"><?php echo $p->name;?></option>
	<?php endforeach; ?>
</select>

</div>
<div class="col-md-3">
<input type="date" name="sd" value="<?php if(isset($_GET["sd"])){ echo $_GET["sd"]; }?>" class="form-control">
</div>
<div class="col-md-3">
<input type="date" name="ed" value="<?php if(isset($_GET["ed"])){ echo $_GET["ed"]; }?>" class="form-control">
</div>

<div class="col-md-1">
<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-file-text"></i></button>
</div>

</div>
</form>

	</div>
	</div>
<br><!--- -->
<div class="row">
	
	<div class="col-md-12">
		<?php if(isset($_GET["sd"]) && isset($_GET["ed"]) ):?>
<?php if($_GET["sd"]!=""&&$_GET["ed"]!=""):?>
			<?php 
			$operations = array();

			if($_GET["product_id"]==""){
			$operations = OperationData::getAllByDateOfficial($_GET["stock_id"],$_GET["sd"],$_GET["ed"]);
			}
			else{
			$operations = OperationData::getAllByDateOfficialBP($_GET["stock_id"],$_GET["product_id"],$_GET["sd"],$_GET["ed"]);
			} 


			 ?>

			 <?php if(count($operations)>0):?>
<a onclick="thePDF()" id="makepdf" class="btn btn-default" class="">PDF (.pdf)</a>
<a href="./report/reports-xlsx.php?stock_id=<?php echo $_GET["stock_id"]; ?>&product_id=<?php echo $_GET["product_id"]; ?>&sd=<?php echo $_GET["sd"]; ?>&ed=<?php echo $_GET["ed"]; ?>" class="btn btn-default">Excel (.xlsx)</a>

<br><br>
<div class="box box-primary">
<table class="table table-bordered">
	<thead>
		<th>Id</th>
		<th>Producto</th>
		<th>Cantidad</th>
		<th>Operacion</th>
		<th>Fecha</th>
	</thead>
<?php foreach($operations as $operation):?>
	<tr>
		<td><?php echo $operation->id; ?></td>
		<td><?php echo $operation->getProduct()->name; ?></td>
		<td><?php echo $operation->q; ?></td>
		<td><?php echo $operation->getOperationType()->name; ?></td>
		<td><?php echo $operation->created_at; ?></td>
	</tr>
<?php endforeach; ?>

</table>
</div>


<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("REPORTE DE INVENTARIO", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "Producto", dataKey: "product"}, 
    {title: "Cantidad", dataKey: "q"}, 
    {title: "Tipo de operacion", dataKey: "operation_type"}, 
    {title: "Fecha", dataKey: "created_at"}, 
];
var rows = [
  <?php foreach($operations as $product):
  ?>
    {
      "id": "<?php echo $product->id; ?>",
      "product": "<?php echo $product->getProduct()->name; ?>",
      "q": "<?php echo $product->q; ?>",
      "operation_type": "<?php echo $product->getOperationType()->name; ?>",
      "created_at": "<?php echo $product->created_at; ?>",
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
doc.save('reports-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('reports-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>






			 <?php else:
			 // si no hay operaciones
			 ?>
<script>
	$("#wellcome").hide();
</script>
<div class="jumbotron">
	<h2>No hay operaciones</h2>
	<p>El rango de fechas seleccionado no proporciono ningun resultado de operaciones.</p>
</div>

			 <?php endif; ?>
<?php else:?>
<script>
	$("#wellcome").hide();
</script>
<div class="jumbotron">
	<h2>Fecha Incorrectas</h2>
	<p>Puede ser que no selecciono un rango de fechas, o el rango seleccionado es incorrecto.</p>
</div>
<?php endif;?>

		<?php endif; ?>
	</div>
</div>

<br><br><br><br>
</section>