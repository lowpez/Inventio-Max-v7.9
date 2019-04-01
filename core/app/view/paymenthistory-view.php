<?php
$client = PersonData::getById($_GET["id"]);
$total = PaymentData::sumByClientId($client->id)->total;


?>

<section class="content">
<div class="row">
  <div class="col-md-12">

    <h1>Historial de pagos y credito</h1>
    <h4>Cliente: <?php echo $client->name." ".$client->lastname; ?></h4>
    <a href="./?view=credit" class="btn btn-default"> <i class="fa fa-arrow-left"></i> Regresar</a>
    <a href="./report/paymenthistory-word.php?id=<?php echo $client->id; ?>" class="btn btn-default"> <i class="fa fa-file-text"></i> Descargar Word (.docx)</a>
    <a href="./report/paymenthistory-excel.php?id=<?php echo $client->id; ?>" class="btn btn-default"> <i class="fa fa-file-text"></i> Descargar Excel (.xlsx)</a>
<br><br>
    <?php

    $users = PaymentData::getAllByClientId($_GET["id"]);
    if(count($users)>0){
      // si hay usuarios
      ?>
<div class="box box-primary">
      <table class="table table-bordered table-hover">
      <thead>
      <th>Tipo</th>
      <th>Valor</th>
      <th>Saldo</th>
      <th>Fecha</th>
      <th></th>
      </thead>
      <?php
      foreach($users as $user){
        ?>
        <tr>
        <td><?php echo $user->getPaymentType()->name; ?></td>
        <td>$ <?php echo number_format(abs($user->val),2,".",",");?></td>
        <td>$ <?php echo number_format($total,2,".",",");?></td>
        <td><?php echo $user->created_at; ?></td>
        <td style="width:230px;">
        <?php if($user->payment_type_id==2):?>
        <a href="index.php?action=delpayment&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a>
      <?php endif; ?>
        </td>
        </tr>
        <?php
        $total -=$user->val;

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