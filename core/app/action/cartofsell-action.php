<?php
 $symbol = ConfigurationData::getByPreffix("currency")->val;
$iva_name = ConfigurationData::getByPreffix("imp-name")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
?>
<?php if(isset($_SESSION["errors"])):?>
<h2>Errores</h2>
<p></p>
<table class="table table-bordered table-hover">
<tr class="danger">
	<th>Codigo</th>
	<th>Producto</th>
	<th>Mensaje</th>
</tr>
<?php foreach ($_SESSION["errors"]  as $error):
$product = ProductData::getById($error["product_id"]);
?>
<tr class="danger">
	<td><?php echo $product->id; ?></td>
	<td><?php echo $product->name; ?></td>
	<td><b><?php echo $error["message"]; ?></b></td>
</tr>

<?php endforeach; ?>
</table>
<?php
unset($_SESSION["errors"]);
 endif; ?>


<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["cart"])):
$total = 0;
?>


<div class="row">
<div class="col-md-8">


<h2>Lista de venta</h2>
<div class="box box-primary">
<table class="table table-bordered table-hover">
<thead>
  <th style="width:30px;">Codigo</th>
  <th style="width:30px;">Cantidad</th>
  <th style="width:30px;">Unidad</th>
  <th>Producto</th>
  <th style="width:90px;">Precio Unitario</th>
  <th style="width:90px;">Precio Total</th>
  <th ></th>
</thead>
<?php foreach($_SESSION["cart"] as $p):
$product = ProductData::getById($p["product_id"]);
$price = $product->price_out;
    $px = PriceData::getByPS($product->id,StockData::getPrincipal()->id);
    if($px!=null){ $price = $px->price_out; }

?>
<tr >
  <td><?php echo $product->code; ?></td>
  <td ><?php echo $p["q"]; ?></td>
  <td><?php echo $product->unit; ?></td>
  <td><?php echo $product->name; ?></td>
  <td><b><?php echo $symbol; ?> <?php echo number_format($price,2,".",","); ?></b></td>
  <td><b><?php echo $symbol; ?> <?php  $pt = $price*$p["q"]; $total +=$pt; echo number_format($pt,2,".",","); ?></b></td>
  <td style="width:30px;"><a id="clearcart-<?php echo $product->id; ?>" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-remove"></i> Quitar</a>

<script>
  $("#clearcart-<?php echo $product->id; ?>").click(function(){
    $.get("index.php?view=clearcart","product_id=<?php echo $product->id; ?>",function(data){
        $.get("./?action=cartofsell",null,function(data2){
          $("#cartofsell").html(data2);
        });
    });
  });
</script>

  </td>
</tr>

<?php endforeach; ?>
</table>
</div>



</div>
<div class="col-md-4">


<form method="post" class="form-horizontal" id="processsell" enctype="multipart/form-data">
<h2>Resumen</h2>

<div class="row">
<div class="col-md-12">
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Principal</a></li>
    <li role="presentation"><a href="#extra"  aria-controls="extra" role="tab" data-toggle="tab">Extra</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">
<div class="row">

<div class="col-md-12">
    <label class="control-label">No. Factura</label>
    <div class="col-lg-12">
      <input type="text" name="invoice_code" class="form-control"  placeholder="No. Factura">
    </div>
  </div>
  </div>
<div class="row">

<div class="col-md-6">
    <label class="control-label">Almacen</label>
    <div class="col-lg-12">
    <h4 class=""><?php 
    echo StockData::getPrincipal()->name;
    ?></h4>
    </div>
  </div>

<div class="col-md-6">
    <label class="control-label">Cliente</label>
    <div class="col-lg-12">
    <?php 
$clients = PersonData::getClients();
    ?>
    <select name="client_id" id="client_id" class="form-control">
    <option value="">-- NINGUNO --</option>
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name." ".$client->lastname;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>
  </div>

<div class="row">

<div class="col-md-6">
    <label class="control-label">Pago</label>
    <div class="col-lg-12">
    <?php 
$clients = PData::getAll();
    ?>
    <select name="p_id" id="p_id" class="form-control">
    <?php foreach($clients as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>
<div class="col-md-6">
    <label class="control-label">Entrega</label>

    <div class="col-lg-12">
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
<div class="row">

<div class="col-md-12">
    <label class="control-label">Forma de pago</label>
    <div class="col-lg-12">
    <?php 
$clients = FData::getAll();
    ?>
    <select name="f_id" id="p_id" class="form-control">
    <?php foreach(FData::getAll() as $client):?>
      <option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>

</div>
<div class="row">

<div class="col-md-6">
    <label class="control-label">Descuento %</label>
    <div class="col-lg-12">
      <input type="text" name="discount_percen" class="form-control" required value="0" id="discount_percen" placeholder="Descuento %">
      <input type="hidden" name="discount" class="form-control" required value="0" id="discount" placeholder="Descuento">
    </div>
  </div>
 <div class="col-md-6">
    <label class="control-label">Efectivo</label>
    <div class="col-lg-12">
      <input type="text" name="money" value="0" class="form-control" id="money" placeholder="Efectivo">
    </div>
  </div>
  </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="extra">

<div class="row">

<div class="col-md-12">
    <div class="">
    <label class="control-label">Comentarios</label>
      <textarea name="comment"  placeholder="Comentarios" class="form-control" rows="10"></textarea>
    </div>
  </div>
  </div>

    </div>
  </div>

</div>
</div>
</div>

<script>
  $("#discount_percen").keyup(function(){
    $("#discount").val( ($("#discount_percen").val()/100)*<?php echo $total; ?>  );
  });
</script>




      <input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
      <div class="clearfix"></div>
<br>
  <div class="row">
<div class="col-md-12">
<div class="box box-primary">
<table class="table table-bordered">
<tr>
  <td><p>Subtotal</p></td>
  <td><p><b><?php echo $symbol; ?> <?php echo number_format($total/(1 + ($iva_val/100) ),2,'.',','); ?></b></p></td>
</tr>
<tr>
  <td><p><?php echo $iva_name." (".$iva_val."%) ";?></p></td>
  <td><p><b><?php echo $symbol; ?> <?php echo number_format(($total/(1 + ($iva_val/100) )) *($iva_val/100),2,'.',','); ?></b></p></td>
</tr>
<tr>
  <td><p>Total</p></td>
  <td><p><b><?php echo $symbol; ?> <?php echo number_format($total,2,'.',','); ?></b></p></td>
</tr>

</table>
</div>
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
          <input name="is_oficial" type="hidden" value="1">
        </label>
      </div>
    </div>
  </div>
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
    <a href="index.php?view=clearcart" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
        <button class="btn btn-primary"><i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-usd"></i> Finalizar Venta</button>
        </label>
      </div>
    </div>
  </div>
</form>



</div>
</div>




<script>
	$("#processsell").submit(function(e){
		discount = $("#discount").val();
    p = $("#p_id").val();
    client = $("#client_id").val();
		money = $("#money").val();
    if(money!=""){
    if(p!=4){
		if(money<(<?php echo $total;?>-discount)){
			alert("Efectivo insificiente!");
			e.preventDefault();
		}else{
			if(discount==""){ discount=0;}
			go = confirm("Cambio: $"+(money-(<?php echo $total;?>-discount ) ) );
			if(go){
      e.preventDefault();
        $.post("./index.php?action=processsell",$("#processsell").serialize(),function(data){
          $.get("./?action=cartofsell",null,function(data2){
            $("#cartofsell").html(data);
            $("#show_search_results").html("");
          });
        });

      }
				else{e.preventDefault();}
		}
    }else if(p==4){ // usaremos credito
      if(client!=""){
        // procedemos
        cli=Array();
        lim=Array();
        cur=Array();
        <?php 
        foreach(PersonData::getClients() as $cli){
          echo " cli[$cli->id]=$cli->has_credit ;";
          echo " lim[$cli->id]=$cli->credit_limit ;";
$sells = SellData::getCreditsByClientId($cli->id);

$totalx=0;
foreach ($sells as $sell) {
$tx = PaymentData::sumBySellId($sell->id)->total;
if($tx>0){
$totalx+=$tx;
}
}
//echo $totalx;
          echo " cur[$cli->id]=$totalx ;";


        }
        ?>
//console.log(lim[client]);
//console.log(cur[client]+(<?php echo $total; ?>-discount));
        if(cli[client]==1){
          // si el cliente tiene credito entonces procedemos a hacer la venta a credito :D
          e.preventDefault();
if(lim[client]>=cur[client]+(<?php echo $total; ?>-discount)){
          $.post("./index.php?action=processsell",$("#processsell").serialize(),function(data){
            $.get("./?action=cartofsell",null,function(data2){
              $("#cartofsell").html(data);
              $("#show_search_results").html("");
            });
          });
}else{
            alert("El cliente ha alcanzado el limite de credito, no se puede procesar la venta!");

}
        }else{
          // el cliente no tiene credito
          alert("El cliente seleccionado no cuenta con credito!");
          e.preventDefault();

        }
      }else{
        // 
        alert("Debe seleccionar un cliente!");
        e.preventDefault();
      }

    }
  }else{
    alert("Campo de pago vacio")
    e.preventDefault();
  }
	});
</script>
</div>
</div>

<?php endif; ?>
