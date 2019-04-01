<?php if(isset($_GET["opt"]) && $_GET["opt"]=="all"):

$ops = SavingData::getAll();
$ins = SavingData::SumByKind(1);
$outs = SavingData::SumByKind(2);
?>
<div class="content">
<div class="row">
<div class="col-md-12">



      <div class="row">
        <div class="col-md-7">
<h1>Caja chica</h1>
<a href="./?view=smallbox&opt=new" class="btn btn-default">Nueva Operacion</a><br><br>
        </div>
        <div class="col-lg-5 col-xs-6">
          <!-- small box -->
          <br>
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo Core::$symbol; ?> <?php echo number_format($ins->s-$outs->s,2,".",",");?></h3>

              <p>Disponible</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
          </div>
        </div>
        </div>



<?php if(count($ops)>0):?>
  <div class="box box-primary">
  <div class="box box-body">
	<table class="table table-bordered table-hover datatable ">
	<thead>
	<th>Id</th>
	<th>Concepto</th>		
	<th>Descripcion</th>		
	<th>Monto</th>		
  <th>Tipo</th>    
	<th>Fecha</th>
	<th></th>
	</thead>
	<?php foreach($ops as $op):?>
	<tr>
	<td><?php echo $op->id;?></td>
	<td><?php echo $op->concept;?></td>		
	<td><?php echo $op->description;?></td>		
	<td><?php echo Core::$symbol; ?> <?php echo $op->amount;?></td>		
  <td><?php if($op->kind==1){echo "Entrada";}else{ echo "Salida"; }?></td>    
	<td><?php echo $op->date_at;?></td>		
	<td style="width:160px;">
		<a href="./?view=smallbox&opt=edit&id=<?php echo $op->id;?>" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
		<a href="./?action=operations&opt=del&id=<?php echo $op->id;?>&k=1" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>

	</td>
	</tr>
	<?php endforeach;?>
	</table>
  </div>
  </div>
<?php else:?>
<p class="alert alert-danger">No hay Operaciones</p>
<?php endif; ?>

</div>
</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="new"):?>
<div class="container">
<div class="row">
<div class="col-md-12">
<h1>Nueva Operacion</h1>
<div class="row">
<div class="col-md-8">
<form method="post" action="./?action=operations&opt=add">
  <div class="form-group">
    <label for="exampleInputEmail1">Tipo</label>
    <select name="kind" class="form-control" required>
      <option value=""> -- SELECCIONE --</option>
      <option value="1"> Entrada</option>
      <option value="2"> Salida</option>
    </select>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Fecha (yyyy-mm-dd)</label>
    <input type="date" name="date_at" required class="form-control"  >
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Concepto</label>
    <input type="text" name="concept" required class="form-control"  placeholder="Concepto">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Descripcion</label>
    <textarea class="form-control" name="description" placeholder="Descripcion"></textarea>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Monto</label>
<div class="input-group">
  <span class="input-group-addon">$</span>
  <input type="text" class="form-control"  placeholder="Monto $" name="amount">
</div>
  </div>
  <button type="submit" class="btn btn-primary">Agregar Salida</button>
</form>
</div>
</div>

</div>
</div>
</div>

<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="edit"):
$x = SavingData::getById($_GET["id"]);
?>
<div class="container">
<div class="row">
<div class="col-md-12">
<h1>Editar Operacion</h1>
<div class="row">
<div class="col-md-8">
<form method="post" action="./?action=operations&opt=update">
  <div class="form-group">
    <label for="exampleInputEmail1">Fecha (yyyy-mm-dd)</label>
    <input type="date" name="date_at" value="<?php echo $x->date_at;?>" required class="form-control"  >
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Concepto</label>
    <input type="text" name="concept" required  value="<?php echo $x->concept;?>"  class="form-control"  placeholder="Concepto">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Descripcion</label>
    <textarea class="form-control" name="description" placeholder="Descripcion"><?php echo $x->description;?></textarea>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Monto</label>
<div class="input-group">
  <span class="input-group-addon">$</span>
  <input type="text" class="form-control" value="<?php echo $x->amount;?>"  placeholder="Monto $" name="amount">
</div>
  </div>
  <input type="hidden" name="id" value="<?php echo $x->id; ?>">
  <button type="submit" class="btn btn-success">Actualizar Operacion</button>
</form>
</div>
</div>

</div>
</div>
</div>

<?php endif;?>




