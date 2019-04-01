
<section class="content">
<div class="row">
	<div class="col-md-12">
<!-- Single button -->

		<h1><i class="glyphicon glyphicon-stats"></i> Buscar Productos en Sucursales</h1>
<form class="form-inline">
<input type="hidden" name="view" value="search">
  <div class="form-group">
    <label class="sr-only" for="exampleInputEmail3">Buscar</label>
    <input type="text" name="q" required class="form-control" id="exampleInputEmail3" placeholder="Buscar ...">
  </div>
  <button type="submit" class="btn btn-default">Buscar</button>
</form>
<br>
<!-- <a onclick="thePDF()" class="btn btn-default">Descargar PDF</a><br><br> -->
<?php if(isset($_GET["q"]) && $_GET["q"]!=""):?>
<?php
$products = ProductData::getLike($_GET["q"]);
$sucursales = StockData::getAll();
if(count($products)>0){
	?>
<div class="clearfix"></div>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Buscar productos en sucursales</h3>

  </div><!-- /.box-header -->
  <div class="box-body">
  <table class="table table-bordered datatable table-hover">
	<thead>
		<th>Codigo</th>
		<th>Nombre</th>
    <?php foreach($sucursales as $suc):?>
		<th><?php echo $suc->name; ?></th>
    <?php endforeach; ?>
	</thead>
	<?php foreach($products as $product):?>
	<tr>
		<td><?php echo $product->id; ?></td>
		<td><?php echo $product->name; ?></td>
    <?php foreach($sucursales as $suc):?>
		<td>
			<?php 
  $q=OperationData::getQByStock($product->id,$suc->id);
      echo $q; ?>
		</td>
    <?php endforeach; ?>
	</tr>
	<?php endforeach;?>
</table>
  </div><!-- /.box-body -->
</div><!-- /.box -->



<div class="clearfix"></div>

	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay productos</h2>
		<p>No se encuentran productos con el termino de busqueda, por favor intente otro..</p>
	</div>
	<?php
}
endif; 
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
        doc.text("INVENTARIO GLOBAL", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);

var columns = [
//    {title: "Reten", dataKey: "reten"},
    {title: "Codigo", dataKey: "code"}, 
    {title: "Nombre del Producto", dataKey: "product"}, 
    <?php foreach($sucursales as $suc):?>
    {title: "<?php echo $suc->name; ?>", dataKey: "suc-<?php echo $suc->id; ?>"}, 

    <?php endforeach; ?>
//    ...
];



var rows = [
  <?php foreach($products as $product):
  ?>
    {
      "code": "<?php echo $product->id; ?>",
      "product": "<?php echo $product->name; ?>",
    <?php foreach($sucursales as $suc):
      $q=OperationData::getQByStock($product->id,$suc->id);?>
      "suc-<?php echo $suc->id; ?>": "<?php echo $q;?>",
      <?php endforeach; ?>
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
doc.save('inventary-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('inventary-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>


//doc.output("datauri");

        }
    </script>