<section class="content">
<div class="row">
	<div class="col-md-12">
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/byreceive-word.php">Word 2007 (.docx)</a></li>
    <li><a href="report/byreceive-xlsx.php">Excel 2007 (.xlsx)</a></li>
<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a></li>

  </ul>
</div>

		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Compras por Recibir</h1>
		<div class="clearfix"></div>


<?php

$products = null;
if(Core::$user->kind==2){
$products = SellData::getResToReceiveByStockId(Core::$user->stock_id);
}else{
$products = SellData::getResToReceive();
}

if(count($products)>0){
	?>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>Folio</th>
		<th>Pago</th>
		<th>Entrega</th>

		<th>Total</th>
		<th>Fecha</th>
		<th></th>
	</thead>
	<?php foreach($products as $sell):
	$operations = OperationData::getAllProductsBySellId($sell->id);
	?>

	<tr>
		<td style="width:30px;"><a href="index.php?view=onere&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>
		<td>#<?php echo $sell->id; ?></td>

<td><?php echo $sell->getP()->name; ?></td>
<td><?php echo $sell->getD()->name; ?></td>
<td>
<?php
$total=0;
	foreach($operations as $operation){
		$product  = $operation->getProduct();
		$total += $operation->q*$product->price_in;
	}
		echo "<b>$ ".number_format($total,2,".",",")."</b>";

?>			

		</td>
		<td><?php echo $sell->created_at; ?></td>
		<td style="width:120px;">
		<a href="./?action=receive1&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary">Recibido</a>
		<a href="index.php?view=delre&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
		</td>
	</tr>

<?php endforeach; ?>

</table>
</div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay datos</h2>
		<p>No se ha realizado ninguna operacion.</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>

<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("COMPRAS POR RECIBIR", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "Proveedor", dataKey: "client"}, 
    {title: "Total", dataKey: "total"}, 
    {title: "Estado de pago", dataKey: "p"}, 
    {title: "Estado de entrega", dataKey: "d"}, 
    {title: "Almacen", dataKey: "stock"}, 
    {title: "Fecha", dataKey: "created_at"}, 
];
var rows = [
  <?php foreach($products as $sell):
  ?>
    {
      "id": "<?php echo $sell->id; ?>",
      "client": "<?php if($sell->person_id!=null){$c= $sell->getPerson();echo $c->name." ".$c->lastname;} ?>",
      "total": "<?php
$total= $sell->total-$sell->discount;
		echo "$ ".number_format($total,2,".",",");
?>	",
      "p": "<?php echo $sell->getP()->name; ?>",
      "d": "<?php echo $sell->getD()->name; ?>",
      "stock": "<?php echo $sell->getStockTo()->name; ?>",
      "created_at": "<?php echo $sell->created_at; ?>",
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
doc.save('resbyreceive-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('resbyreceive-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>
