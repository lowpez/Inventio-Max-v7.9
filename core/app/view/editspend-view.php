        <section class="content">
<?php $user = SpendData::getById($_GET["id"]);?>
<div class="row">
	<div class="col-md-12">
	<h1>Editar Gasto</h1>
	<br>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?action=updatespend" role="form">


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Concepto*</label>
    <div class="col-md-6">
      <input type="text" name="name" value="<?php echo $user->name;?>" class="form-control" id="name" placeholder="Concepto">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Costo*</label>
    <div class="col-md-6">
      <input type="text" name="price" value="<?php echo $user->price;?>" class="form-control" id="name" placeholder="Costo">
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
      <button type="submit" class="btn btn-primary">Actualizar Gasto</button>
    </div>
  </div>
</form>
	</div>
</div>
</section>