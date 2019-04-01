<?php 
$pro = ProductData::getById($_GET["product_id"]);
$stock_id = $_GET["stock"];
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1><?php echo $pro->name; ?> [Quitar]</h1>
	<br>
		<form class="form-horizontal" method="post" id="addcategory" action="./?action=processsub" role="form">
<input type="hidden" name="stock" value="<?php echo $stock_id; ?>">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Cantidad*</label>
    <div class="col-md-6">
      <input type="text" name="q" required class="form-control" id="name" required placeholder="Cantidad">
      <input type="hidden" name="product_id" value="<?php echo $pro->id; ?>">
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Proceder</button>
    </div>
  </div>
</form>
	</div>
</div>
</section>