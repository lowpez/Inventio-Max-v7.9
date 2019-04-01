<section class="content"> 
<div class="row">
	<div class="col-md-12">

<?php if(isset($_SESSION["client_id"])):?>
		<h1><i class='fa fa-flash'></i> Mis Compras</h1>
<?php else:?>
		<h1><i class='fa fa-flash'></i> Devoluciones Pendientes</h1>
<?php endif;?>
		<div class="clearfix"></div>


<?php
$products = null;
if(isset($_SESSION["user_id"])){
if(Core::$user->kind==3){
$products = SellData::getAllBySQL(" where user_id=Core::$user->id and operation_type_id=5 and status=0 order by created_at desc");

}
else if(Core::$user->kind==2){
$products = SellData::getAllBySQL(" where operation_type_id=5 and status=0 and stock_to_id=Core::$user->stock_id order by created_at desc");
}
else{
$products = SellData::getAllBySQL(" where operation_type_id=5 and status=0");

}
}else if(isset($_SESSION["client_id"])){
$products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=5 and status=0 order by created_at desc");	
}

if(count($products)>0){

	?>
<br>
<div class="box box-primary">
<div class="box-header">
<h3 class="box-title" Devoluciones Pendientes</h3></div>
<div class="box-body">
<table class="table table-bordered table-hover table-responsive datatable	">
	<thead>
		<th></th>
		<th>Folio</th>	
		<th>Total</th>
		<th>Cliente</th>
		<th>Vendedor</th>
		<th>Almacen</th>
		<th>Fecha</th>
		<th></th>
	</thead>
	<?php foreach($products as $sell):
	$operations = OperationData::getAllProductsBySellId($sell->id);
	?>

	<tr>
		<td style="width:30px;">
		<a href="index.php?view=onedev&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>
		<td>#<?php echo $sell->id; ?></td>

		<td>

<?php
$total= $sell->total-$sell->discount;
		echo "<b>$ ".number_format($total,2,".",",")."</b>";

?>			
		</td>
	<td> <?php if($sell->person_id!=null){$c= $sell->getPerson();echo $c->name." ".$c->lastname;} ?> </td>
	<td> <?php if($sell->user_id!=null){$c= $sell->getUser();echo $c->name." ".$c->lastname;} ?> </td>
		<td><?php echo $sell->getStockTo()->name; ?></td>
		<td><?php echo $sell->created_at; ?></td>
		<td style="width:130px;">
<?php if(isset($_SESSION["user_id"])):?>
<!--
		<a href="index.php?action=cancelsell&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger">Cancelar</a> 
		-->
		<a href="index.php?view=applydev&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Aceptar<a>
		<a href="index.php?view=deldev&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i><a>
<?php endif;?>
		</td>
	</tr>

<?php endforeach; ?>

</table>
</div>
</div>

<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay Devoluciones Pendientes</h2>
		<p>No se ha realizado ninguna devolucion.</p>
	</div>
	<?php
}

?>
	</div>
</div>
</section>


<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("VENTAS", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "Cliente", dataKey: "client"}, 
    {title: "Total", dataKey: "total"}, 
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
doc.save('sells-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('sells-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>


