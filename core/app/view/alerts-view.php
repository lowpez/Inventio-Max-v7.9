<?php

if(Core::$user->kind==3){ Core::redir("./?view=sell"); }

?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
    <h1>Sistema de Venta Multi Sucursal</h1>
          <h4>Almacen principal: <?php echo StockData::getPrincipal()->name;  ?></h4>
        </section>

        <!-- Main content -->
        <section class="content">

  <?php
  $found=false;
$products = ProductData::getAll();
//print_r($products);
foreach($products as $product){
  $q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
if( $q==0 ||  $q<=$product->inventary_min){
    $found=true;
    break;

  }
}
  ?>
<div class="row">
  <div class="col-md-12">
<?php if($found):?>
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/alerts-word.php">Word 2007 (.docx)</a></li>
    <li><a href="report/alerts-xlsx.php">Excel 2007 (.xlsx)</a></li>
<li><a onclick="thePDF()" id="makepdf" class="">PDF (.pdf)</a>
  </ul>
</div>
<?php endif;?>
<?php if($found):?>
<?php
$page = 1;
$limit=10;
if(count($products)>0){


  ?>
<div class="clearfix"></div>
<br>

<div class="box">
  <div class="box-header">
    <h3 class="box-title">Alerta de Inventario - <?php echo StockData::getPrincipal()->name; ?></h3>

  </div><!-- /.box-header -->
  <div class="box-body">

<table class="table table-bordered table-hover datatable">
  <thead>
    <th >Codigo</th>
    <th>Nombre del producto</th>
    <th>Precio Entrada</th>
    <th>Precio Salida</th>
    <th>En Stock</th>
    <th></th>
  </thead>
  <?php
foreach($products as $product):
//  $q=OperationData::getQ($product->id);
  $q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);

  ?>
  <?php if( $q==0 ||  $q<=$product->inventary_min):?>
  <tr class="<?php if($q==0){ echo "danger"; }else if($q<=$product->inventary_min/2){ echo "danger"; } else if($q<=$product->inventary_min){ echo "warning"; } ?>">
    <td><?php echo $product->id; ?></td>
    <td><?php echo $product->name; ?></td>
    <td><?php echo Core::$symbol; ?> <?php echo number_format($product->price_in,2,'.',','); ?></td>
    <td><?php echo Core::$symbol; ?> <?php echo number_format($product->price_out,2,'.',','); ?></td>
    <td><?php echo $q; ?></td>
    <td>
    <?php if($q==0){ echo "<span class='label label-danger'>No hay existencias.</span>";}else if($q<=$product->inventary_min/2){ echo "<span class='label label-danger'>Quedan muy pocas existencias.</span>";} else if($q<=$product->inventary_min){ echo "<span class='label label-warning'>Quedan pocas existencias.</span>";} ?>
    </td>
  </tr>
<?php endif;?>
<?php
endforeach;
?>
</table>
  </div><!-- /.box-body -->
</div><!-- /.box -->


<div class="clearfix"></div>

  <?php
}else{
  ?>
  <div class="jumbotron">
    <h2>No hay alertas</h2>
    <p>Por el momento no hay alertas de inventario, estas se muestran cuando el inventario ha alcanzado el nivel minimo.</p>
  </div>
  <?php
}

?>
<?php else:?>
  <div class="jumbotron">
    <h2>No hay alertas</h2>
    <p>Por el momento no hay alertas de inventario, estas se muestran cuando el inventario ha alcanzado el nivel minimo.</p>
  </div>
<?php endif;?>
<br><br><br><br><br><br><br><br><br><br>
  </div>
</div>
        </section><!-- /.content -->





<script type="text/javascript">
        function thePDF() {
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("<?php echo ConfigurationData::getByPreffix("company_name")->val;?>", 40, 65);
        doc.setFontSize(18);
        doc.text("ALERTAS DE INVENTARIO", 40, 80);
        doc.setFontSize(12);
        doc.text("Usuario: <?php echo Core::$user->name." ".Core::$user->lastname; ?>  -  Fecha: <?php echo date("d-m-Y h:i:s");?> ", 40, 90);
var columns = [
    {title: "Id", dataKey: "id"}, 
    {title: "Producto", dataKey: "name"}, 
    {title: "Precio de entrada", dataKey: "price_in"}, 
    {title: "Precio de Salida", dataKey: "price_in"}, 
    {title: "Disponibilidad", dataKey: "q"}, 
];
var rows = [
  <?php foreach($products as $product):
$q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
  ?>
  <?php if( $q==0 ||  $q<=$product->inventary_min):?>

    {
      "id": "<?php echo $product->id; ?>",
      "name": "<?php echo $product->name; ?>",
      "price_in": "$ <?php echo number_format($product->price_in,2,'.',',');?>",
      "price_out": "$ <?php echo number_format($product->price_out,2,'.',',');?>",
      "q": "<?php echo $q;?>",
      },
 <?php endif; ?>
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
doc.save('alerts-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
}
<?php else:?>
doc.save('alerts-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
<?php endif; ?>
}
</script>


