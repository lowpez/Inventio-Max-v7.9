<section class="content">
<?php
if(isset($_GET["product_id"])):
$stock = StockData::getById($_GET["stock"]);
$product = ProductData::getById($_GET["product_id"]);
$operations = OperationData::getAllByProductIdAndStock($product->id,$stock->id);
?>
<div class="row">
	<div class="col-md-12">
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/history-word.php?id=<?php echo $product->id;?>&stock_id=<?php echo $_GET["stock"];?>">Word 2007 (.docx)</a></li>
  </ul>
</div>
<h1><?php echo $product->name;; ?> <small>Historial</small></h1>
<ol class="breadcrumb">
  <li><a href="./?view=home">Inicio</a></li>
  <li><a href="./?view=stocks">Almacenes</a></li>
  <li><a href="./?view=inventary&stock=<?php echo $stock->id; ?>"><?php echo $stock->name;?></a></li>
  <li class="active">Historial</li>
</ol>

	</div>
	</div>

<div class="row">


	<div class="col-md-4">


	<?php
$itotal = OperationData::GetInputQByStock($product->id,$stock->id);

	?>

<div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo $itotal; ?></h3>
                  <p>Entradas</p>
                </div>
                <div class="icon">
                  <i class="fa fa-usd"></i>
                </div>
              </div>


<?php
?>

</div>

	<div class="col-md-4">
	<?php
$total = OperationData::GetQByStock($product->id,$stock->id);


	?>
<div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo $total; ?></h3>
                  <p>Disponible</p>
                </div>
                <div class="icon">
                  <i class="fa fa-cube"></i>
                </div>
              </div>

<?php
?>

</div>

	<div class="col-md-4">


	<?php
$ototal = -1*OperationData::GetOutputQYesF($product->id);

	?>

<div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $ototal; ?></h3>
                  <p>Salidas</p>
                </div>
                <div class="icon">
                  <i class="fa fa-shopping-cart"></i>
                </div>
              </div>
</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if(count($operations)>0):?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Historial</h3>

  </div><!-- /.box-header -->
  <div class="box-body no-padding">
			<table class="table table-bordered table-hover">
			<thead>
			<th></th>
			<th>Cantidad</th>
			<th>Venta/Compra/Traspaso</th>
			<th>Tipo</th>
			<th>Fecha</th>
			<th></th>
			</thead>
			<?php foreach($operations as $operation):?>
			<tr>
			<td></td>
			<td><?php echo $operation->q; ?></td>
			<td>
				<?php if($operation->operation_type_id==1 && $operation->sell_id!="" && !$operation->is_traspase):?>
					<a href='./?view=onere&id=<?php echo $operation->sell_id; ?>'>#<?php echo $operation->sell_id;?></a>
				<?php elseif($operation->operation_type_id==2 && $operation->sell_id!="" && !$operation->is_traspase):?>
					<a href='./?view=onesell&id=<?php echo $operation->sell_id; ?>'>#<?php echo $operation->sell_id;?></a>
				<?php elseif($operation->operation_type_id==6 && $operation->sell_id!="" && $operation->is_traspase):?>
					<a href='./?view=onetraspase&id=<?php echo $operation->sell_id; ?>'>#<?php echo $operation->sell_id;?></a>
				<?php endif; ?>
			</td>

			<td><?php echo $operation->getOperationType()->name; ?></td>
			<td><?php echo $operation->created_at; ?></td>
			<td style="width:40px;"><a href="#" id="oper-<?php echo $operation->id; ?>" class="btn tip btn-xs btn-danger" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a> </td>
			<script>
			$("#oper-"+<?php echo $operation->id; ?>).click(function(){
				x = confirm("Estas seguro que quieres eliminar esto ??");
				if(x==true){
					window.location = "index.php?view=deleteoperation&ref=history&pid=<?php echo $operation->product_id;?>&opid=<?php echo $operation->id;?>&stock=<?php echo $stock->id; ?>";
				}
			});

			</script>
			</tr>
			<?php endforeach; ?>
			</table>
  </div><!-- /.box-body -->
</div><!-- /.box -->

		<?php endif; ?>
	</div>
</div>
<?php endif; ?>
</section>