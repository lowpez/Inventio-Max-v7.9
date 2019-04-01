<?php
$clients = PersonData::getClients();
$users = UserData::getAll();
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Reportes de Vendedores Populares</h1>


<form>
<input type="hidden" name="view" value="vendorreports">
<div class="row">

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

			$operations = SellData::getSQL("select *,sum(total-discount) as st from sell where date(created_at) >= \"$_GET[sd]\" and date(created_at) <= \"$_GET[ed]\" and operation_type_id=2 and is_draft=0 and p_id=1 and d_id=1 and user_id is not NULL group by user_id order by st desc");


			 ?>

			 <?php if(count($operations)>0):?>
			 	<?php $supertotal = 0; ?>
<a onclick="thePDF()" id="makepdf" class="btn btn-default" class="">PDF (.pdf)</a><br><br>


<div class="box box-primary">
<table class="table table-bordered">
	<thead>
		<th>Id Vendedor</th>
		<th>Total Venta</th>
		<th>Vendedor</th>
	</thead>
<?php foreach($operations as $operation):?>
	<tr>
		<td><?php echo $operation->user_id; ?></td>
		<td><?php echo Core::$symbol; ?> <?php echo number_format($operation->st,2,'.',','); ?></td>
	<td> <?php if($operation->user_id!=null){$c= $operation->getUser();echo $c->name." ".$c->lastname;} ?> </td>
	</tr>
<?php
//$supertotal+= ($operation->total-$operation->discount);
 endforeach; ?>

</table>
</div>

<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("CLIENTES POPULARES", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "Total", dataKey: "total"}, 
    {title: "Vendedor", dataKey: "client"}, 
];
var rows = [
  <?php foreach($operations as $operation):
  ?>
    {
      "id": "<?php echo $operation->user_id; ?>",
      "total": "<?php echo Core::$symbol; ?> <?php echo number_format($operation->st,2,'.',','); ?>",
      "client": "<?php if($operation->user_id!=null){$c= $operation->getUser();echo $c->name." ".$c->lastname;} ?>",
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
doc.setFontSize(18);1
//doc.text("TOTAL DE VENTAS: <?php echo Core::$symbol; ?> <?php echo number_format($supertotal,2,'.',','); ?>", 40, doc.autoTableEndPosY()+25);
doc.setFontSize(12);
doc.text("<?php echo Core::$pdf_footer;?>", 40, doc.autoTableEndPosY()+45);
<?php 
$con = ConfigurationData::getByPreffix("report_image");
if($con!=null && $con->val!=""):
?>
var img = new Image();
img.src= "storage/configuration/<?php echo $con->val;?>";
img.onload = function(){
doc.addImage(img, 'PNG', 495, 20, 60, 60,'mon');	
doc.save('sellreports-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('topclients-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
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