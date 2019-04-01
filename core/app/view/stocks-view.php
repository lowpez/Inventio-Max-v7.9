<section class="content">
<div class="row">
	<div class="col-md-12">
<div class="btn-group pull-right">
	<a href="index.php?view=newstock" class="btn btn-default"><i class='fa fa-th-list'></i> Nueva Sucursal</a>
</div>
		<h1>Sucursales</h1>
<br>
		<?php

		$users = StockData::getAll();
		if(count($users)>0){
			// si hay usuarios
			?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Sucursal</h3>

  </div><!-- /.box-header -->
  <div class="box-body no-padding">

			<table class="table table-bordered table-hover">
			<thead>
			<th></th>
			<th>Nombre</th>
			<th>Direccion</th>
			<th>Telefono</th>
			<th>Email</th>
			<th>Principal</th>
			<th></th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td style="width:30px;"><a href="index.php?view=inventary&stock=<?php echo $user->id;?>" class="btn btn-default btn-xs"><i class="fa fa-chevron-right"></i></a></td>
				<td><?php echo $user->name." ".$user->lastname; ?></td>
				<td><?php echo $user->address; ?></td>
				<td><?php echo $user->phone; ?></td>
				<td><?php echo $user->email; ?></td>
				<td style="width:120px;">
				<center>
				<?php if($user->is_principal):?>
					<i class="fa fa-check"></i>
				<?php else:?>
					<a href="index.php?action=makestockprincipal&id=<?php echo $user->id;?>" class="btn btn-default btn-xs">Hacer Principal</a>
				<?php endif;?>
				</center>
				</td>
				<td style="width:130px;"><a href="index.php?view=editstock&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Editar</a> <a href="index.php?action=delstock&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a></td>
				</tr>
				<?php

			}

			?>
			</table>
  </div><!-- /.box-body -->
</div><!-- /.box -->
			
			<?php



		}else{
			echo "<p class='alert alert-danger'>No hay Categorias</p>";
		}


		?>


	</div>
</div>
</section>