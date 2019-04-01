        <section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Nuevo Gasto</h1>
	<br>
		<form class="form-horizontal" method="post" id="addcategory" action="index.php?action=addspend" role="form">


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Concepto*</label>
    <div class="col-md-6">
      <input type="text" name="name" required class="form-control" id="name" placeholder="Concepto">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Costo*</label>
    <div class="col-md-6">
      <input type="text" name="price" required class="form-control" id="name" placeholder="Costo">
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Agregar Gasto</button>
    </div>
  </div>
</form>
	</div>
</div>
</section>