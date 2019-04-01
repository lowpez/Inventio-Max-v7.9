<?php
$stocks = StockData::getAll();
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Seleccionar almacen origen ...</h1>
	<br>
<div class="box box-primary"><br>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?action=selectstocks" role="form">


  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Origen*</label>
    <div class="col-md-6">
    <select name="stock_id" class="form-control" required>
    <option value="">-- NINGUNO --</option>
    <?php foreach($stocks as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>


  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Seleccionar</button>
    </div>
  </div>
</form>
<br>
</div>
	</div>
</div>
</section>