<?php
 $company_name = ConfigurationData::getByPreffix("company_name")->val;
 $symbol = ConfigurationData::getByPreffix("currency")->val;
 $iva_val = ConfigurationData::getByPreffix("imp-val")->val;
 $sell = SellData::getById($_GET["id"]);
 $stock = StockData::getById($sell->stock_to_id);
?>
<section class="content">
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="ticket.php?id=<?php echo $_GET["id"];?>">Ticket (.pdf)</a></li>
    <li><a href="report/onesell-word.php?id=<?php echo $_GET["id"];?>">Word 2007 (.docx)</a></li>
<li><a onclick="thePDF()" id="makepdf" class=""><i class="fa fa-download"></i> Descargar PDF</a>
<li><a onclick="theInvoice()" id="makepdf" class=""><i class="fa fa-download"></i> Descargar Factura PDF</a>
  </ul>
</div>
<h1>Resumen de Venta #<?php echo $sell->id;?></h1>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;
?>
<?php
//if($product->kind==1){
if(isset($_COOKIE["selled"])){
  foreach ($operations as $operation) {
//    print_r($operation);
    $qx = OperationData::getQByStock($operation->product_id,StockData::getPrincipal()->id);
    // print "qx=$qx";
      $p = $operation->getProduct();
    if($p->kind==1&&$qx==0){
      echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> no tiene existencias en inventario.</p>";      
    }else if($p->kind==1&&$qx<=$p->inventary_min/2){
      echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene muy pocas existencias en inventario.</p>";
    }else if($p->kind==1&&$qx<=$p->inventary_min){
      echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene pocas existencias en inventario.</p>";
    }
  }
  setcookie("selled","",time()-18600);
}

?>
<div class="row">
<div class="col-md-8">
<div class="box box-primary">
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
<tr>
  <td style="width:150px;">Cliente</td>
  <td><?php echo $client->name." ".$client->lastname;?></td>
</tr>

<?php endif; ?>
<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
  <td>Atendido por</td>
  <td><?php echo $user->name." ".$user->lastname;?></td>
</tr>
<?php endif; ?>
</table>
</div>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover">
  <thead>
    <th>Codigo</th>
    <th>Cantidad</th>
    <th>Nombre del Producto</th>
    <th>Precio Unitario</th>
    <th>Total</th>

  </thead>
<?php
  foreach($operations as $operation){
    $product  = $operation->getProduct();
?>
<tr>
  <td><?php echo $product->code ;?></td>
  <td><?php echo $operation->q ;?></td>
  <td><?php echo $product->name ;?></td>
  <td><?php echo $symbol; ?> <?php echo number_format($operation->price_out,2,".",",") ;?></td>
  <td><b><?php echo $symbol; ?> <?php echo number_format($operation->q*$operation->price_out,2,".",",");$total+=$operation->q*$operation->price_out;?></b></td>
</tr>
<?php
  }
  ?>
</table>
</div>
<br><br>
<div class="row">
<div class="col-md-4">
<div class="box box-primary">
<table class="table table-bordered">
  <tr>
    <td><h4>Subtotal:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($total/(1+($iva_val/100)),2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Impuesto:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($total/(1+($iva_val/100)) * ($iva_val/100) ,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Descuento:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($sell->discount,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Subtotal:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($total,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Total:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($total-  $sell->discount,2,'.',','); ?></h4></td>
  </tr>
</table>
</div>

<?php if($sell->person_id!=""):
$credit=PaymentData::sumByClientId($sell->person_id)->total;

?>
<div class="box box-primary">
<table class="table table-bordered">
  <tr>
    <td><h4>Saldo anterior:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($credit-$total,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Saldo Actual:</h4></td>
    <td><h4><?php echo $symbol; ?> <?php echo number_format($credit,2,'.',','); ?></h4></td>
  </tr>
</table>
</div>
<?php endif;?>
</div>
</div>


</div>
<div class="col-md-4">
<form method="post" class="form-horizontal" action="./?action=updatesell" id="processsell" enctype="multipart/form-data">
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
      <input type="text" name="invoice_code" value="<?php echo $sell->invoice_code;?>" class="form-control"  placeholder="No. Factura">
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
      <option value="<?php echo $client->id;?>" <?php if($client->id==$sell->person_id){ echo "selected"; }?>><?php echo $client->name." ".$client->lastname;?></option>
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
      <option value="<?php echo $client->id;?>" <?php if($client->id==$sell->f_id){ echo "selected"; }?>><?php echo $client->name;?></option>
    <?php endforeach;?>
      </select>
    </div>
  </div>

</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="extra">

<div class="row">

<div class="col-md-12">
    <label class="control-label">Archivo Factura</label>
    <div class="col-lg-12">
    <?php if($sell->invoice_file!=""):?>
      <a href="./storage/invoice_files/<?php echo $sell->invoice_file;?>" target="_blank" class="btn btn-default"><i class="fa fa-file"></i> Archivo Factura (<?php echo $sell->invoice_file; ?>)</a>
      <br><br>
    <?php endif; ?>
      <input type="file" name="invoice_file"  placeholder="Archivo Factura">
    </div>
  </div>
  </div>

<div class="row">

<div class="col-md-12">
    <div class="">
    <label class="control-label">Comentarios</label>
      <textarea name="comment"  placeholder="Comentarios" class="form-control" rows="10"><?php echo $sell->comment;?></textarea>
    </div>
  </div>
  </div>

    </div>
  </div>

</div>
</div>
</div>




<input type="hidden" name="id" value="<?php echo $sell->id; ?>">
  <div class="row">
<div class="col-md-12">

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
        <button class="btn btn-success"> Actualizar Venta</button>
        </label>
      </div>
    </div>
  </div>
</form>
</div>

</div>






<script type="text/javascript">
        function thePDF() {

var columns = [
//    {title: "Reten", dataKey: "reten"},
    {title: "Codigo", dataKey: "code"}, 
    {title: "Cantidad", dataKey: "q"}, 
    {title: "Nombre del Producto", dataKey: "product"}, 
    {title: "Precio unitario", dataKey: "pu"}, 
    {title: "Total", dataKey: "total"}, 
//    ...
];


var columns2 = [
//    {title: "Reten", dataKey: "reten"},
    {title: "", dataKey: "clave"}, 
    {title: "", dataKey: "valor"}, 
//    ...
];

var rows = [
  <?php foreach($operations as $operation):
  $product  = $operation->getProduct();
  ?>

    {
      "code": "<?php echo $product->code; ?>",
      "q": "<?php echo $operation->q; ?>",
      "product": "<?php echo $product->name; ?>",
      "pu": "<?php echo Core::$symbol;?> <?php echo number_format($operation->price_out,2,".",","); ?>",
      "total": "<?php echo Core::$symbol;?> <?php echo number_format($operation->q*$operation->price_out,2,".",","); ?>",
      },
 <?php endforeach; ?>
];

var rows2 = [
<?php if($sell->person_id!=""):
$person = $sell->getPerson();
?>

    {
      "clave": "Cliente",
      "valor": "<?php echo $person->name." ".$person->lastname; ?>",
      },
      <?php endif; ?>
    {
      "clave": "Atendido por",
      "valor": "<?php echo $user->name." ".$user->lastname; ?>",
      },

];

var rows3 = [

    {
      "clave": "Subtotal",
      "valor": "<?php echo Core::$symbol;?> <?php echo number_format($sell->total/(1+($iva_val/100)),2,'.',',');; ?>",
      },
    {
      "clave": "Impuesto",
      "valor": "<?php echo Core::$symbol;?> <?php echo number_format($sell->total/(1+($iva_val/100)) * ($iva_val/100),2,'.',',');; ?>",
      },
    {
      "clave": "Descuento",
      "valor": "<?php echo Core::$symbol;?> <?php echo number_format($sell->discount,2,'.',',');; ?>",
      },
    {
      "clave": "Subtotal",
      "valor": "<?php echo Core::$symbol;?> <?php echo number_format($sell->total,2,'.',',');; ?>",
      },
    {
      "clave": "Total",
      "valor": "<?php echo Core::$symbol;?> <?php echo number_format($sell->total-$sell->discount,2,'.',',');; ?>",
      },
];


// Only pt supported (not mm or in)
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("NOTA DE VENTA #<?php echo $sell->ref_id; ?>", 40, 65);
        doc.setFontSize(14);
        doc.text("Fecha: <?php echo $sell->created_at; ?>", 40, 80);
//        doc.text("Operador:", 40, 150);
//        doc.text("Header", 40, 30);
  //      doc.text("Header", 40, 30);

doc.autoTable(columns2, rows2, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    margin: {top: 100},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});


doc.autoTable(columns, rows, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    margin: {top: doc.autoTableEndPosY()+15},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});

doc.autoTable(columns2, rows3, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    margin: {top: doc.autoTableEndPosY()+15},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});

//doc.setFontsize
//img = new Image();
//img.src = "liberacion2.jpg";
//doc.addImage(img, 'JPEG', 40, 10, 610, 100, 'monkey'); // Cache the image using the alias 'monkey'
doc.setFontSize(20);
doc.setFontSize(12);
doc.text("Generado por el Sistema de inventario", 40, doc.autoTableEndPosY()+25);
doc.save('sell-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
//doc.output("datauri");

        }

        function theInvoice(){

var columns = [
//    {title: "Reten", dataKey: "reten"},
    {title: "Codigo", dataKey: "code"}, 
    {title: "Cantidad", dataKey: "q"}, 
    {title: "Nombre del Producto", dataKey: "product"}, 
    {title: "Precio unitario", dataKey: "pu"}, 
    {title: "Total", dataKey: "total"}, 
//    ...
];


var columns2 = [
//    {title: "Reten", dataKey: "reten"},
    {title: "", dataKey: "clave"}, 
    {title: "", dataKey: "valor"}, 
//    ...
];

var rows = [
  <?php foreach($operations as $operation):
  $product  = $operation->getProduct();
  ?>

    {
      "code": "<?php echo $product->code; ?>",
      "q": "<?php echo $operation->q; ?>",
      "product": "<?php echo $product->name; ?>",
      "pu": "<?php echo Core::$symbol;?> <?php echo number_format($operation->price_out,2,".",","); ?>",
      "total": "<?php echo Core::$symbol;?> <?php echo number_format($operation->q*$operation->price_out,2,".",","); ?>",
      },
 <?php endforeach; ?>
];


var rows3 = [

    {
      "clave": "Subtotal",
      "valor": "<?php echo Core::$symbol;?> <?php echo number_format($sell->total/(1+($iva_val/100)),2,'.',',');; ?>",
      },
    {
      "clave": "Impuesto",
      "valor": "<?php echo Core::$symbol;?> <?php echo number_format($sell->total/(1+($iva_val/100)) * ($iva_val/100),2,'.',',');; ?>",
      },
    {
      "clave": "Descuento",
      "valor": "<?php echo Core::$symbol;?> <?php echo number_format($sell->discount,2,'.',',');; ?>",
      },
    {
      "clave": "Subtotal",
      "valor": "<?php echo Core::$symbol;?> <?php echo number_format($sell->total,2,'.',',');; ?>",
      },
    {
      "clave": "Total",
      "valor": "<?php echo Core::$symbol;?> <?php echo number_format($sell->total-$sell->discount,2,'.',',');; ?>",
      },
];


// Only pt supported (not mm or in)
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(24);
        doc.text("<?php echo $company_name; ?>", 40, 35);
//        doc.text("Factura #<?php echo $sell->ref_id; ?>", 40, 65);
        doc.setFontSize(11);

        doc.text("<?php echo $stock->name; ?>", 40, 45);
        doc.text("<?php echo $stock->address; ?>", 40, 55);
        doc.text("TEL.: <?php echo $stock->phone; ?>", 40, 65);
        <?php if($stock->email!=""):?>
        doc.text("Email: <?php echo $stock->email; ?>", 40, 75);
          <?php endif; ?>

        doc.text("FACTURA: #<?php echo $sell->id; ?>", 420, 45);
        doc.text("FECHA: <?php echo $sell->created_at; ?>", 420, 57);
//        doc.text("Operador:", 40, 150);
//        doc.text("Header", 40, 30);
  //      doc.text("Header", 40, 30);
doc.line(40, 77, 560, 77);
<?php if($sell->person_id!=""):
$person = $sell->getPerson();
?>
doc.text("CLIENTE: <?php echo $person->name." ".$person->lastname; ?>", 40, 88);
doc.text("RUT/RFC: <?php echo $person->no; ?>", 40, 98);
doc.text("DIRECCION: <?php echo $person->address1; ?>", 40, 108);
doc.text("TELEFONO: <?php echo $person->phone1; ?>", 40, 118);

<?php endif; ?>

doc.autoTable(columns, rows, {
    overflow:'linebreak',
    margin: {top: 120},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});

doc.autoTable(columns2, rows3, {
    overflow:'linebreak',
    margin: {top: doc.autoTableEndPosY()+15},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});

//doc.setFontsize
//img = new Image();
//img.src = "liberacion2.jpg";
//doc.addImage(img, 'JPEG', 40, 10, 610, 100, 'monkey'); // Cache the image using the alias 'monkey'
doc.setFontSize(20);
doc.setFontSize(12);
//doc.text("Generado por el Sistema de inventario", 40, doc.autoTableEndPosY()+25);
doc.save('invoice-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
//doc.output("datauri");

        }
    </script>

<script>
  $(document).ready(function(){
  //  $("#makepdf").trigger("click");
  });
</script>




<?php else:?>
  501 Internal Error
<?php endif; ?>
</section>