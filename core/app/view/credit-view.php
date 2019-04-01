<section class="content">
<div class="row">
  <div class="col-md-12">

    <h1>Credito</h1>
    <h4>Lista de clientes con credito</h4>
<br>
    <?php

    $users = PersonData::getClientsWithCredit();
    if(count($users)>0){
      // si hay usuarios
      ?>
    <a href="./report/credit-word.php" class="btn btn-default"> <i class="fa fa-file-text"></i> Descargar Word (.docx)</a>
    <a href="./report/credit-excel.php" class="btn btn-default"> <i class="fa fa-file-text"></i> Descargar Excel (.xlsx)</a>
<br><br>
<div class="box box-primary">
      <table class="table table-bordered table-hover">
      <thead>
      <th>Nombre completo</th>
      <th>Direccion</th>
      <th>Telefono</th>
      <th>Credito</th>
      <th>Saldo Pendiente</th>
      <th></th>
      </thead>
      <?php
      foreach($users as $user){
        ?>
        <tr>
        <td><?php echo $user->name." ".$user->lastname; ?></td>
        <td><?php echo $user->address1; ?></td>
        <td><?php echo $user->phone1; ?></td>
        <td><?php if($user->has_credit){ echo "<i class='fa fa-check'></i>"; }; ?></td>
        <td>$ <?php

$sells = SellData::getCreditsByClientId($user->id);

$total=0;
foreach ($sells as $sell) {
$tx = PaymentData::sumBySellId($sell->id)->total;
if($tx>0){
$total+=$tx;
}
}
echo $total;

         //echo number_format(PaymentData::sumByClientId($user->id)->total,2,".",","); 



         ?></td>
        <td style="width:230px;">
        <a href="index.php?view=makepayment&id=<?php echo $user->id;?>" class="btn btn-default btn-xs">Realizar Pago</a>
        <a href="index.php?view=paymenthistory&id=<?php echo $user->id;?>" class="btn btn-default btn-xs">Historial</a>
        </td>
        </tr>
        <?php

      }?>
      </table>
      </div>
      <?php
    }else{
      echo "<p class='alert alert-danger'>No hay clientes</p>";
    }


    ?>


  </div>
</div>
</section>