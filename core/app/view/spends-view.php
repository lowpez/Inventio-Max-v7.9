        <section class="content">
<div class="row">
	<div class="col-md-12">
<div class="btn-group pull-right">
	<a href="index.php?view=newspend" class="btn btn-default"><i class='fa fa-th-list'></i> Nuevo Gasto</a>
</div>
		<h1>Gastos</h1>
<br>
		<?php

		$users = SpendData::getAllUnBoxed();
		if(count($users)>0){
			// si hay usuarios
			$total = 0;
			?>
              <div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title">Gastos</h3>
                </div><!-- /.box-header -->

			<table class="table table-bordered table-hover">
			<thead>
			<th>Concepto</th>
			<th>Costo</th>
			<th>Fecha</th>
			<th></th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td><?php echo $user->name; ?></td>
				<td><?php echo Core::$symbol; ?> <?php echo number_format($user->price,2,".",","); ?></td>
				<td><?php echo $user->created_at; ?></td>
				<td style="width:130px;"><a href="index.php?view=editspend&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Editar</a> <a href="index.php?action=delspend&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a></td>
				</tr>
				<?php
				$total+=$user->price;

			}

echo "</table>";
echo "<div class='box-body'><h1>Gasto Total : ".Core::$symbol." ".number_format($total,2,".",",")."</div></h1>";
echo "</div>";

		}else{
			echo "<p class='alert alert-danger'>No hay Gastos</p>";
		}


		?>


	</div>
</div>
</section>