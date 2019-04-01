<?php if(isset($_GET["opt"]) && $_GET["opt"]=="all"):
$inbox = MessageData::getInboxByUserId($_SESSION["user_id"]);
?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Mensajes
      </h1>
      <ol class="breadcrumb">
        <li><a href="./?view=home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li >Mensajes</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-block margin-bottom">Enviar mensaje</a>





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Enviar mensaje</h4>
      </div>
      <div class="modal-body">
<form method="post" action="./?action=messages&opt=addmsg1">
  <div class="form-group">
    <label for="exampleInputEmail1">Usuario</label>
    <select class="form-control" name="user_to" required>
    	<option value="">-- SELECCIONE --</option>
    	<?php foreach(UserData::getAll() as $u):?>
    		<?php if($u->id!=$_SESSION["user_id"]):?>
    	<option value="<?php echo $u->id; ?>"><?php echo $u->name." ".$u->lastname; ?></option>
    <?php endif; ?>
    	<?php endforeach;?>
    </select>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Mensaje</label>
    <textarea class="form-control" name="message" id="exampleInputPassword1" rows="6" placeholder="Mensaje"></textarea>
  </div>
  <button type="submit" class="btn btn-default">Enviar Mensaje</button>
</form>
      </div>

    </div>
  </div>

</div>



          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Opciones</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li ><a href="./?view=messages&opt=all"><i class="fa fa-inbox"></i> Bandeja de entrada</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->

          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Inbox</h3>


              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <div class="table-responsive mailbox-messages">
              <?php if(count($inbox)>0):?>
                <table class="table table-hover table-striped datatable">
                <thead>
                	<th></th>
                	<th>Usuario</th>
                	<th>Mensaje</th>
                	<th>Fecha</th>
                </thead>
                <?php foreach($inbox as $i):?>
                  <tr>
                  <td>
                  	<a href="./?view=messages&opt=open&code=<?php echo $i->code;?>" class="btn btn-default btn-xs">Abrir</a>
                  </td>
                    <td class="mailbox-name">
                    <?php if($i->user_from!=$_SESSION["user_id"]):?>
                    <?php $u = $i->getFrom(); echo $u->name." ".$u->lastname;?>
                    <?php elseif($i->user_to!=$_SESSION["user_id"]):?>
                    <?php $u = $i->getTo(); echo $u->name." ".$u->lastname;?>
                	<?php endif; ?>
                    </td>
                    <td class="mailbox-subject">
                    <?php echo $i->message;?>

                    </td>
                    <td class="mailbox-date"><?php echo $i->created_at;?></td>
                  </tr>
              <?php 
              
              endforeach; ?>

                </table>
            <?php else:?>
            	<p class="alert alert-warning">No hay mensajes</p>
            <?php endif?>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->

          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
<?php elseif($_GET["opt"]=="open"):
$messages = MessageData::getAllBy("code",$_GET["code"]);
?>
<?php 
$theother=null;
foreach($messages as $i):?>
<?php if($i->user_from!=$_SESSION["user_id"]){ $theother=$i->user_from; }
else if($i->user_to!=$_SESSION["user_id"]){ $theother=$i->user_to;}?>
<?php endforeach; ?>


   <section class="content-header">
      <h1>
        Mensajes
      </h1>
      <ol class="breadcrumb">
        <li><a href="./?view=home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li >Mensajes</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a data-toggle="modal" data-target="#myModal" class="btn btn-success btn-block margin-bottom">Responder Conversacion</a>





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Responder Conversacion</h4>
      </div>
      <div class="modal-body">
<form method="post" action="./?action=messages&opt=addmsg2">
<input type="hidden" name="code" value="<?php echo $_GET["code"];?>">
<input type="hidden" name="user_to" value="<?php echo $theother;?>">

  <div class="form-group">
    <label for="exampleInputPassword1">Mensaje</label>
    <textarea class="form-control" name="message" id="exampleInputPassword1" rows="6" placeholder="Mensaje"></textarea>
  </div>
  <button type="submit" class="btn btn-default">Responder Conversacion</button>
</form>
      </div>

    </div>
  </div>

</div>



          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Opciones</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li ><a href="./?view=messages&opt=all"><i class="fa fa-inbox"></i> Bandeja de entrada</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->

          <!-- /.box -->
        </div>


        <div class="col-md-9">
<div class="box box-success">
            <div class="box-header">
              <i class="fa fa-comments-o"></i>

              <h3 class="box-title">Mensajes</h3>

            </div>
            <div class="box-body chat" id="chat-box">
              <!-- chat item -->
              <?php foreach($messages as $msg):
              $from = $msg->getFrom();
              ?>
              <div class="clearfix"></div>
              <br><br><div class="item">

                <p class="message">
                  <a href="javascript:void()" class="name">
                    <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?php echo $msg->created_at;?></small>
                    <?php echo $from->name." ".$from->lastname;?>
                  </a>
                  <?php echo $msg->message;?>
                </p>

                <!-- /.attachment -->
              </div>
          <?php 

if($msg->user_to==$_SESSION["user_id"]){
              	if($msg->is_read==0){ $msg->read(); }
              }
          endforeach; ?>
              <!-- /.item -->
              <!-- chat item -->
              <div class="clearfix"></div>
              <br><br>

              <!-- /.item -->
            </div>
            <!-- /.chat -->

          </div>

        </div>
        </div>
        </section>








<?php endif;?>