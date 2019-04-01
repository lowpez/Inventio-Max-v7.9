<?php
$stocks = StockData::getAll();
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Reporte Por Categorias</h1>

						<form>
						<input type="hidden" name="view" value="sellsbycat">
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
	<option value="">--  CATEGORIAS --</option>
	<?php foreach(CategoryData::getAll() as $p):?>
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

			$operations = OperationData::getAllByDateOfficial($_GET["stock_id"],$_GET["sd"],$_GET["ed"]);


			 ?>

			 <?php if(count($operations)>0):
$products = ProductData::getAllByCategoryId($_GET["product_id"]);
			 ?>
<a onclick="thePDF()" id="makepdf" class="btn btn-default" class="">PDF (.pdf)</a>

<br><br>

<div class="box box-primary">
<table class="table table-bordered">
	<thead>
		<th>Id</th>
		<th>Producto</th>
		<th>Entradas</th>
		<th>$$ Entradas</th>
		<th>Salidas</th>
		<th>$$ Salidas</th>
		<th>E-S</th>
		<th>$$ E-S</th>
</thead>
<?php foreach($products as $p):
$ni = 0;
$no = 0;
foreach($operations as $o){
	if($o->operation_type_id==1&& $o->product_id==$p->id){ $ni+=$o->q; }
	else if($o->operation_type_id==2&& $o->product_id==$p->id){ $no+=$o->q; }
}
?>
	<tr>
		<td><?php echo $p->id; ?></td>
		<td><?php echo $p->name; ?></td>
		<td><?php echo $ni;?></td>
		<td><?php echo Core::$symbol;?> <?php echo $p->price_in*$ni;?></td>
		<td><?php echo $no;?></td>
		<td><?php echo Core::$symbol;?> <?php echo $p->price_out*$no;?></td>
		<td><?php echo ($ni-$no);?></td>
		<td><?php echo Core::$symbol;?> <?php echo (($p->price_in*$ni)-($p->price_out*$no));?></td>
</tr>
<?php endforeach;?>
</table>
</div>




<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("REPORTE DE POR CATEGORIA", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "Producto", dataKey: "product"}, 
    {title: "Entrada", dataKey: "i"}, 
    {title: "Salida", dataKey: "o"}, 
    {title: "E-S", dataKey: "d"}, 
];
var rows = [
  <?php foreach($products as $p):
$ni = 0;
$no = 0;
foreach($operations as $o){
	if($o->operation_type_id==1&& $o->product_id==$p->id){ $ni+=$o->q; }
	else if($o->operation_type_id==2&& $o->product_id==$p->id){ $no+=$o->q; }
}

  ?>
    {
      "id": "<?php echo $p->id; ?>",
      "product": "<?php echo $p->name; ?>",
      "i": "<?php echo $ni; ?>",
      "o": "<?php echo $no; ?>",
      "d": "<?php echo ($ni-$no); ?>",
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
doc.save('reportbycat-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('reportbycat-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
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