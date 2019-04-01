
<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["reabastecer"])):
$total = 0;
// $symbol = ConfigurationData::getByPreffix("currency")->val;
$iva_name = ConfigurationData::getByPreffix("imp-name")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;


?>

<h3>Lista de Reabastecimiento</h3>
<div class="box box-primary">
<table class="table table-bordered table-hover">
<thead>
	<th style="width:30px;">Codigo</th>
	<th style="width:30px;">Cantidad</th>
	<th style="width:50px;">Unidad</th>
	<th>Producto</th>
	<th style="">Precio Entrada</th>
	<th style="">Precio Total</th>
	<th ></th>
</thead>
<?php foreach($_SESSION["reabastecer"] as $p):
$product = ProductData::getById($p["product_id"]);
?>
<tr >
	<td><?php echo $product->code; ?></td>
	<td ><?php echo $p["q"]; ?></td>
	<td><?php echo $product->unit; ?></td>
	<td><?php echo $product->name; ?></td>
	<td><b><?php echo Core::$symbol; ?> <?php echo number_format($p["price_in"],2,".",","); ?></b></td>
  <td><b><?php echo Core::$symbol; ?> <?php echo number_format($p["price_out"],2,".",","); ?></b></td>
	<td><b><?php echo Core::$symbol; ?> <?php  $pt = $product->price_in*$p["q"]; $total +=$pt; echo number_format($pt,2,".",","); ?></b></td>
	<td style="width:30px;"><a id="clearre-<?php echo $product->id; ?>" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
<script>
  $("#clearre-<?php echo $product->id; ?>").click(function(){
    $.get("index.php?view=clearre","product_id=<?php echo $product->id; ?>",function(data){
        $.get("./?action=cartofre",null,function(data2){
          $("#cartofre").html(data2);
        });

    });
  });
</script>
</tr>
<?php endforeach; ?>
</table>
</div>

<h3>Resumen</h3>

  <div class="row">
<div class="col-md-5">
<div class="box box-primary">
<table class="table table-bordered">
<tr>
	<td><p>Subtotal</p></td>
	<td><p><b><?php echo Core::$symbol; ?> <?php echo number_format($total/(1 + ($iva_val/100) ),2,'.',','); ?></b></p></td>
</tr>
<tr>
	<td><p><?php echo $iva_name." (".$iva_val."%) ";?></p></td>
	<td><p><b><?php echo Core::$symbol; ?> <?php echo number_format(($total/(1 + ($iva_val/100) ))*($iva_val/100),2,'.',','); ?></b></p></td>
</tr>
<tr>
	<td><p>Total</p></td>
	<td><p><b><?php echo Core::$symbol; ?> <?php echo number_format($total,2,'.',','); ?></b></p></td>
</tr>

</table>
</div>


</div>
<div class="col-md-7">
<form class="form-horizontal" id="processsell" method="post" action="./?action=processre">
      <input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">


<div class="col-md-12">
<div class="form-group">
    <label for="inputEmail1" class="control-label">No. Factura</label>
   <input type="text" name="invoice_code" value="" class="form-control"  placeholder="No. Factura"><div class="row">
  </div>
  </div>

<div class="col-md-6">
<div class="form-group">
    <div class="">
    <label for="inputEmail1" class="control-label">Almacen</label>
<?php if(Core::$user->kind==1):?>
    <?php 
$clients = StockData::getAll();
    ?>
    <select name="stock_id" class="form-control" required>
    <option value="">-- NINGUNO --</option>
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    <?php else:?>
      <input type="hidden" name="stock_id" value="<?php echo StockData::getPrincipal()->id; ?>">
      <p class="form-control"><?php echo StockData::getPrincipal()->name; ?></p>
    <?php endif;?>
    </div>
  </div>
  </div>
  <div class="col-md-6">
<div class="form-group">
    <div class="col-lg-12">
    <label for="inputEmail1" class="control-label">Proveedor</label>
    <?php 
$clients = PersonData::getProviders();
    ?>
    <select name="client_id" class="form-control">
    <option value="">-- NINGUNO --</option>
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name." ".$client->lastname;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>  
  </div>

  </div>
<div class="row">
  <div class="col-md-4">
<div class="form-group">
    <div class="col-lg-12">
    <label for="inputEmail1" class="control-label">Pago</label>
    <?php 
$clients = PData::getAll();
    ?>
    <select name="p_id" class="form-control">
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    </div>
    </div>
  </div>
  <div class="col-md-4">
<div class="form-group">
    <div class="col-lg-12">
    <label for="inputEmail1" class="control-label">Entrega</label>
    <?php 
$clients = DData::getAll();
    ?>
    <select name="d_id" class="form-control">
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    </div>

  </div>
  </div>
    <div class="col-md-4">
<div class="form-group">
    <div class="col-lg-12">
    <label for="inputEmail1" class="control-label">Forma de pago</label>
    <?php 
$clients = FData::getAll();
    ?>
    <select name="f_id" class="form-control">
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    </div>

  </div>
  </div>
</div>



<div class="form-group">
    <div class="col-lg-12">
    <label for="inputEmail1" class="control-label">Efectivo</label>
      <input type="text" name="money" required class="form-control" id="money" placeholder="Efectivo">
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
    <a href="index.php?view=clearre" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
        <button class="btn btn-primary"><i class="fa fa-refresh"></i> Procesar Reabastecimiento</button>
        </label>
      </div>
    </div>
  </div>
  </form>

</div>
</div>
<script>
  $("#processsell").submit(function(e){
    money = $("#money").val();
    if(money<<?php echo $total;?>){
      alert("No se puede efectuar la operacion");
      e.preventDefault();
    }else{
      go = confirm("Cambio: $"+(money-<?php echo $total;?>));
      if(go){
      e.preventDefault();
        $.post("./index.php?action=processre",$("#processsell").serialize(),function(data){
          $.get("./?action=cartofre",null,function(data2){
            $("#cartofre").html(data);
            $("#show_search_results").html("");
          });
          //alert("Abastecimiento procesado exitosamente!");
        });
      }
        else{e.preventDefault();}
    }
  });
</script>

<?php endif; ?>