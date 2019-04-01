<?php
$clients = PersonData::getClients();
?>

<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Reporte de Pagos</h1>

						<form>
						<input type="hidden" name="view" value="paymentreport">
<div class="row">
<div class="col-md-3">

<select name="client_id" class="form-control">
	<option value="">--  TODOS --</option>
	<?php foreach($clients as $p):?>
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

<div class="col-md-3">
<input type="submit" class="btn btn-success btn-block" value="Procesar">
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
			if(isset($_GET["client_id"]) && $_GET["client_id"]!=""){
			$operations = PaymentData::getAllByDateAndClient($_GET["sd"],$_GET["ed"],$_GET["client_id"]);

			}else{
				$operations = PaymentData::getAllByDate($_GET["sd"],$_GET["ed"]);

			}
			 ?>

			 <?php if(count($operations)>0):?>
<?php $t=0; foreach($operations as $operation){ $t+=$operation->val; }?>
<h2>Total Recaudado: <?php echo Core::$symbol; ?> <?php echo number_format(abs($t),2,".",","); ?></h2>
<a href="./report/paymentreport-xlsx.php?client_id=<?php echo $_GET["client_id"]; ?>&sd=<?php echo $_GET["sd"]; ?>&ed=<?php echo $_GET["ed"]; ?>" class="btn btn-default">Descargar en Excel (.xlsx)</a><br><br>
<div class="box box-primary">
<table class="table table-bordered">
	<thead>
		<th>Cliente</th>
		<th>Valor</th>
		<th>Fecha</th>
	</thead>
<?php foreach($operations as $operation):?>
	<tr>
		<td><?php $c= $operation->getClient();echo $c->name." ".$c->lastname; ?></td>
		<td><?php echo Core::$symbol; ?> <?php echo number_format(abs($operation->val),2,".",","); ?></td>
		<td><?php echo $operation->created_at; ?></td>
	</tr>
<?php endforeach; ?>

</table>
</div>
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