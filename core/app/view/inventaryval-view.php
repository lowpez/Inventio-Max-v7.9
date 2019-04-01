<?php
$stock = StockData::getById($_GET["stock"]);
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
<!-- Single button -->
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a>
  </ul>
</div>
		<h1><i class="glyphicon glyphicon-stats"></i> Valor del Inventario <small><?php echo $stock->name; ?></small></h1>

<?php foreach(StockData::getAll() as $stock):?>
  <a class="btn btn-default" href="./?view=inventaryval&stock=<?php echo $stock->id; ?>"><?php echo $stock->name; ?></a>
<?php endforeach;?>
<br><br>
<?php
$products = ProductData::getAll();
if(count($products)>0){
	?>
<div class="clearfix"></div>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Valor del Inventario</h3>

  </div><!-- /.box-header -->
  <div class="box-body">
  <table class="table table-bordered datatable table-hover">
	<thead>
		<th>Codigo</th>
		<th>Nombre</th>
		<th>Disponible</th>
    <th>Valor Compra</th>
    <th>Valor Inversion</th>
    <th>Valor Ventas</th>
    <th>Ganancia</th>
		<th>Valor Actual</th>
		<th></th>
	</thead>
	<?php 
$ttin=0;
$ttout=0;
$ttou2=0;
  foreach($products as $product):
	$r=OperationData::getRByStock($product->id,$_GET["stock"]);
	$q=OperationData::getQByStock($product->id,$_GET["stock"]);
	$d=OperationData::getDByStock($product->id,$_GET["stock"]);
	?>
	<tr class="<?php if($q<=$product->inventary_min/2){ echo "danger";}else if($q<=$product->inventary_min){ echo "warning";}?>">
		<td><?php echo $product->code; ?></td>
		<td><?php echo $product->name; ?></td>
		<td>
			<?php echo $q; ?>
		</td>
		<td>
<?php echo Core::$symbol; ?>			<?php 
$otin = OperationData::getAllByOT($product->id,1,$_GET["stock"]);
$otout = OperationData::getAllByOT($product->id,2,$_GET["stock"]);
$totin = 0;
$totout = 0;
$totout2 = 0;
foreach ($otin as $o) {
  $totin+=$o->price_in*$o->q;
}
foreach ($otout as $o) {
  $totout+=$o->price_in*$o->q;
}
foreach ($otout as $o) {
  $totout2+=$o->price_out*$o->q;
}
$ttin+=$totin;
$ttout+=$totout;
$ttou2+=$totout2;
echo number_format($totin,2,".",",");
       ?>

		</td>
    <td><?php echo Core::$symbol; ?> <?php echo number_format($totout,2,".",","); ?></td>
    <td><?php echo Core::$symbol; ?> <?php echo number_format($totout2,2,".",","); ?></td>
    <td><?php echo Core::$symbol; ?> <?php echo number_format($totout2-$totout,2,".",","); ?></td>
    <td><?php echo Core::$symbol; ?> <?php echo number_format($totin-$totout,2,".",","); ?></td>
		<td style="width:93px;">
<!--		<a href="index.php?view=input&product_id=<?php echo $product->id; ?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-circle-arrow-up"></i> Alta</a>-->
		<a href="index.php?view=history&product_id=<?php echo $product->id; ?>&stock=<?php echo $_GET["stock"];?>" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-time"></i> Historial</a>
		</td>
	</tr>
	<?php endforeach;?>
  <tr>
    <td></td>
    <td>(Total)</td>
    <td></td>
    <td><?php echo Core::$symbol; ?> <?php echo number_format($ttin,2,".",","); ?></td>
    <td><?php echo Core::$symbol; ?> <?php echo number_format($ttout,2,".",","); ?></td>
    <td><?php echo Core::$symbol; ?> <?php echo number_format($ttou2,2,".",","); ?></td>
    <td><?php echo Core::$symbol; ?> <?php echo number_format($ttou2-$ttout,2,".",","); ?></td>
    <td><?php echo Core::$symbol; ?> <?php echo number_format($ttin-$ttout,2,".",","); ?></td>
    <td></td>
  </tr>
</table>
  </div><!-- /.box-body -->
</div><!-- /.box -->



<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay productos</h2>
		<p>No se han agregado productos a la base de datos, puedes agregar uno dando click en el boton <b>"Agregar Producto"</b>.</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
</section>






<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("VALOR DEL INVENTARIO: <?php echo $stock->name;?>", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);

var columns = [
//    {title: "Reten", dataKey: "reten"},
    {title: "Codigo", dataKey: "code"}, 
    {title: "Nombre del Producto", dataKey: "product"}, 
    {title: "Disponible", dataKey: "disponible"}, 
    {title: "Valor de compra", dataKey: "total_re"}, 
    {title: "Valor de ventas", dataKey: "total_sell"}, 
    {title: "Valor actual", dataKey: "total_now"}, 
//    ...
];



var rows = [
  <?php foreach($products as $product):
	$r=OperationData::getRByStock($product->id,$_GET["stock"]);
	$q=OperationData::getQByStock($product->id,$_GET["stock"]);
	$d=OperationData::getDByStock($product->id,$_GET["stock"]);

$otin = OperationData::getAllByOT($product->id,1,$_GET["stock"]);
$otout = OperationData::getAllByOT($product->id,2,$_GET["stock"]);
$totin = 0;
$totout = 0;
$totout2 = 0;
foreach ($otin as $o) {
  $totin+=$o->price_in*$o->q;
}
foreach ($otout as $o) {
  $totout+=$o->price_in*$o->q;
}
foreach ($otout as $o) {
  $totout2+=$o->price_out*$o->q;
}
//echo number_format($totin,2,".",",");
  ?>
    {
      "code": "<?php echo $product->code; ?>",
      "product": "<?php echo $product->name; ?>",
      "disponible": "<?php echo $q;?>",
      "total_re": "<?php echo Core::$symbol; ?> <?php echo number_format($totin,2,".",","); ?>",
      "total_sell": "<?php echo Core::$symbol; ?> <?php echo number_format($totout,2,".",","); ?>",
      "total_now": "<?php echo Core::$symbol; ?> <?php echo number_format($totin-$totout,2,".",","); ?>",
      },
 <?php endforeach; ?>
    {
      "code": "",
      "product": "Total",
      "disponible": "",
      "total_re": "<?php echo Core::$symbol; ?> <?php echo number_format($ttin,2,".",","); ?>",
      "total_sell": "<?php echo Core::$symbol; ?> <?php echo number_format($ttout,2,".",","); ?>",
      "total_now": "<?php echo Core::$symbol; ?> <?php echo number_format($ttin-$ttout,2,".",","); ?>",
      },
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
//        doc.text("Header", 40, 30);
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
doc.save('inventaryval-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('inventaryval-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>


//doc.output("datauri");

        }
    </script>