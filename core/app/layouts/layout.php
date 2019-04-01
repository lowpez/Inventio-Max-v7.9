<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Inventio Max | Panel de Administracion</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="plugins/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="plugins/dist/css/skins/skin-blue.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
          <script src="plugins/jquery/jquery-2.1.4.min.js"></script>
<script src="plugins/morris/raphael-min.js"></script>
<script src="plugins/morris/morris.js"></script>
  <link rel="stylesheet" href="plugins/morris/morris.css">
  <link rel="stylesheet" href="plugins/morris/example.css">
          <script src="plugins/jspdf/jspdf.min.js"></script>
          <script src="plugins/jspdf/jspdf.plugin.autotable.js"></script>
          <?php if(isset($_GET["view"]) && $_GET["view"]=="sell"):?>
<script type="text/javascript" src="plugins/jsqrcode/llqrcode.js"></script>
<script type="text/javascript" src="plugins/jsqrcode/webqr.js"></script>
          <?php endif;?>

  </head>

  <body class="<?php if(isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])):?>  skin-blue sidebar-mini <?php else:?>login-page<?php endif; ?>">
    <div class="wrapper">
      <!-- Main Header -->
      <?php if(isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])):?>
      <header class="main-header">
        <!-- Logo -->
        <a href="./" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>I</b>M</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">INVENTIO<b>MAX</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">


<?php

if(isset($_SESSION["user_id"])):
$msgs = MessageData::getUnreadedByUserId($_SESSION["user_id"]);
$cnt_tot = 0;
$found=false;
$products = ProductData::getAll();
//print_r($products);
foreach($products as $product){
  $q= OperationData::getQByStock($product->id,StockData::getPrincipal()->id);
if( $q==0 ||  $q<=$product->inventary_min){
  $cnt_tot++;
 
  }
}
?>
<li>
            <a href="./?view=alerts">
              <i class="fa fa-bell-o"></i>
              <span class="label label-danger"><?php echo $cnt_tot;?></span>
            </a>
  
</li>

<li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success"><?php echo count($msgs);?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tienes <?php echo count($msgs);?> mensajes nuevos</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                <?php foreach($msgs as $i):?>
                  <li><!-- start message -->
                    <a href="./?view=messages&opt=open&code=<?php echo $i->code;?>">
                      <h4>
                    <?php if($i->user_from!=$_SESSION["user_id"]):?>
                    <?php $u = $i->getFrom(); echo $u->name." ".$u->lastname;?>
                    <?php elseif($i->user_to!=$_SESSION["user_id"]):?>
                    <?php $u = $i->getTo(); echo $u->name." ".$u->lastname;?>
                  <?php endif; ?>
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>

                      </h4>
                      <p><?php echo $i->message; ?></p>
                    </a>
                  </li>
                <?php endforeach; ?>
 
                </ul>
              </li>
              <li class="footer"><a href="./?view=messages&opt=all">Todos los mensajes</a></li>
            </ul>
          </li>
<?php endif;?>

              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class=""><?php if(isset($_SESSION["user_id"]) ){ echo UserData::getById($_SESSION["user_id"])->name; 
                  if(Core::$user->kind==1){ echo " (Administrador)"; }
                  else if(Core::$user->kind==2){ echo " (Almacenista)"; }
                  else if(Core::$user->kind==3){ echo " (Vendedor)"; }

                  }else if (isset($_SESSION["client_id"])){ echo PersonData::getById($_SESSION["client_id"])->name." (Cliente)" ;}?> <b class="caret"></b> </span>

                </a>
                <ul class="dropdown-menu">
<?php if(isset($_SESSION["user_id"])):?>
 <li class="user-header">
<?php
          if(Core::$user->image!=""){
            $url = "storage/profiles/".Core::$user->image;
            if(file_exists($url)){
              echo "<img src='$url' class='img-circle'>";
            }
          }
          ?>

                <p>
                <?php echo Core::$user->name." ".Core::$user->lastname;?>
                </p>
              </li>                  <!-- The user image in the menu -->
                  <li><a href="">Cambiar de usuario</a></li>
                <?php endif; ?>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-right">
<?php if(isset($_SESSION["user_id"])):?>
                      <a href="./?view=profile" class="btn btn-default btn-flat">Mi Perfil</a>
                    <?php endif; ?>
                      <a href="./logout.php" class="btn btn-default btn-flat">Salir</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
<!--
<div class="user-panel">
            <div class="pull-left image">
              <img src="1.jpg" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p>Alexander Pierce</p>

              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          -->
          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
<!--            <li class="header">ADMINISTRACION</li> -->
            <?php if(isset($_SESSION["user_id"])):?>
                        <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
<?php if(Core::$user->kind==1||Core::$user->kind==2):


?>
<?php if(Core::$user->kind==1):?>
                        <li><a href="./index.php?view=notifs"><i class='fa fa-flash'></i> <span>Notificaciones</span></a></li>
<?php endif; ?>
<?php endif; ?>
            <li><a href="./?view=sell"><i class='fa fa-usd'></i> <span>Vender</span></a></li>
<!--            <li><a href="./?view=reandsell"><i class='fa fa-usd'></i> <span>Vender en CERO</span></a></li> -->

            <li class="treeview <?php if(isset($_GET["view"]) && ($_GET["view"]=="sells"||$_GET["view"]=="bydeliver" ||$_GET["view"]=="bycob"||$_GET["view"]=="sellscancel"||$_GET["view"]=="cotizations"||$_GET["view"]=="sellscredit"||$_GET["view"]=="onesell")){ echo "active"; }?>"   >
              <a href="#"><i class='fa fa-shopping-cart'></i> <span>Ventas</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=sells">Ventas</a></li>
<?php if(Core::$user->kind==1):?>
       <li><a href="./?view=sellscredit">Ventas credito</a></li>
   <?php endif; ?>
                <li><a href="./?view=bydeliver">Por Entregar</a></li>
                <li><a href="./?view=bycob">Por Cobrar</a></li>
                <li><a href="./?view=sellscancel">Ventas Canceladas</a></li>
            <li><a href="./?view=cotizations"><span>Cotizaciones</span></a></li> 
              </ul>
            </li>
            <?php if(Core::$user->kind==3):?>
            <li><a href="./?view=inventary&stock=<?php echo StockData::getPrincipal()->id;?>"><i class='fa fa-area-chart'></i> <span>Inventario</span></a></li>
                <li><a href="./?view=search"><i class='fa fa-search'></i> Buscar Productos</a></li>
                <li><a href="./index.php?view=dev"><i class='fa fa-retweet'></i> <span>Devolucion</span></a></li>
          <?php endif; ?>


            <?php if(Core::$user->kind==1 || Core::$user->kind==2):?>
            <li class="treeview <?php if(isset($_GET["view"]) && ($_GET["view"]=="res"||$_GET["view"]=="byreceive" ||$_GET["view"]=="topay"||$_GET["view"]=="rescancel"||$_GET["view"]=="onere")){ echo "active"; }?>">
              <a href="#"><i class='fa fa-clock-o'></i> <span>Compras</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=re">Nueva *</a></li>
                <li><a href="./?view=res">Compras</a></li>
                <li><a href="./?view=byreceive">Por Recibir</a></li>
          <li><a href="./?view=topay">Por Pagar</a></li>
          <li><a href="./?view=rescancel">Compras canceladas</a></li>
              </ul>
            </li>
            <?php if(Core::$user->kind==1):?>
                        <li class="treeview <?php if(isset($_GET["view"]) && ($_GET["view"]=="products"||$_GET["view"]=="categories" ||$_GET["view"]=="brands"||$_GET["view"]=="clients"||$_GET["view"]=="providers"||$_GET["view"]=="newproduct"||$_GET["view"]=="editproduct"||$_GET["view"]=="productbycategory"||$_GET["view"]=="newclient"||$_GET["view"]=="editclient"||$_GET["view"]=="newprovider"||$_GET["view"]=="editprovider"||$_GET["view"]=="stocks"||$_GET["view"]=="prices")){ echo "active"; }?>">
              <a href="#"><i class='fa fa-database'></i> <span>Catalogos</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=products">Productos</a></li>
                <li><a href="./?view=categories&opt=all">Categorias</a></li>
                <li><a href="./?view=brands&opt=all">Marcas</a></li>
                <li><a href="./?view=clients">Clientes</a></li>
                <li><a href="./?view=providers">Proveedores</a></li>
                <li><a href="./?view=stocks">Sucursales</a></li>
                <li><a href="./?view=prices">Administrar Precios</a></li>
              </ul>
            </li>

            <li class="treeview  <?php if(isset($_GET["view"]) && ($_GET["view"]=="contacts"||$_GET["view"]=="messages" ||$_GET["view"]=="newcontact"||$_GET["view"]=="editcontact")){ echo "active"; }?>">
              <a href="#"><i class='fa fa-wrench'></i> <span>Herramientas</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=contacts">Contactos</a></li>
                <li><a href="./?view=messages&opt=all">Mensajes</a></li>
              </ul>
            </li>
            <li class="treeview  <?php if(isset($_GET["view"]) && ($_GET["view"]=="credit"||$_GET["view"]=="makepayment" ||$_GET["view"]=="paymenthistory"||$_GET["view"]=="balance"||$_GET["view"]=="spends"||$_GET["view"]=="newspend"||$_GET["view"]=="editspend")){ echo "active"; }?>">
              <a href="#"><i class='fa fa-briefcase'></i> <span>Finanzas</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=credit">Credito</a></li>
                <li><a href="./?view=balance">Balance</a></li>
                <li><a href="./?view=spends">Gastos</a></li>
                <li><a href="./?view=smallbox&opt=all">Caja Chica</a></li>
                <li><a href="./?view=box">Caja</a></li>
              </ul>
            </li>
          <?php endif; ?>
            <li class="treeview  <?php if(isset($_GET["view"]) && ($_GET["view"]=="inventary"||$_GET["view"]=="search" ||$_GET["view"]=="inventaries"||$_GET["view"]=="selectstock"||$_GET["view"]=="inventaryval"||$_GET["view"]=="dev"||$_GET["view"]=="trasps"||$_GET["view"]=="devs"||$_GET["view"]=="re"||$_GET["view"]=="traspase"||$_GET["view"]=="onetraspase"||$_GET["view"]=="onedev")){ echo "active"; }?>">
              <a href="#"><i class='fa fa-area-chart'></i> <span>Inventario</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=inventary&stock=<?php echo StockData::getPrincipal()->id;?>">Inventario Principal</a></li>
                <li><a href="./?view=re">Abastecer</a></li>
            <?php if(Core::$user->kind==1):?>
                <li><a href="./?view=inventaryval&stock=<?php echo StockData::getPrincipal()->id;?>">Valor del Inventario</a></li>
                <li><a href="./?view=search">Buscar Productos</a></li>
                <li><a href="./?view=inventaries">Inventario Global</a></li>
                <li><a href="./?view=selectstock">Traspasar</a></li>
                <li><a href="./?view=dev">Devolucion</a></li>
                <li><a href="./?view=trasps">Traspasos</a></li>
                <li><a href="./?view=devs">Devoluciones</a></li>
              <?php endif; ?>
              </ul>
            </li>
            <?php if(Core::$user->kind==1):?>
                        <li class="treeview <?php if(isset($_GET["view"]) && ($_GET["view"]=="inventorylog"||$_GET["view"]=="sellsbycat" ||$_GET["view"]=="sellreports"||$_GET["view"]=="resreport"||$_GET["view"]=="paymentreport"||$_GET["view"]=="paymentreport"||$_GET["view"]=="clientreports"||$_GET["view"]=="vendorreports"||$_GET["view"]=="popularproductsreport")){ echo "active"; }?>">
              <a href="#"><i class='fa fa-file-text-o'></i> <span>Reportes</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=inventorylog">Log de Inventario</a></li>
                <li><a href="./?view=sellsbycat">Por Categorias</a></li>
                <li><a href="./?view=sellreports">Ventas</a></li>
                <li><a href="./?view=resreport">Compras</a></li>
                <li><a href="./?view=paymentreport">Reporte de pagos [credito]</a></li>
                <li><a href="./?view=clientreports">Clientes Populares</a></li>
                <li><a href="./?view=vendorreports">Vendedores Populares</a></li>
                <li><a href="./?view=popularproductsreport">Productos Populares</a></li>
              </ul>
            </li>


            <li class="treeview <?php if(isset($_GET["view"]) && ($_GET["view"]=="users"||$_GET["view"]=="settings" ||$_GET["view"]=="import"||$_GET["view"]=="newuser"||$_GET["view"]=="edituser")){ echo "active"; }?>">
              <a href="#"><i class='fa fa-cog'></i> <span>Administracion</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=users">Usuarios</a></li>
                <li><a href="./?view=settings">Configuracion</a></li>
                <li><a href="./?view=import">Importar Datos</a></li>

              </ul>
            </li>
          <?php endif; ?>
          <?php endif; ?>
            <?php elseif(isset($_SESSION["client_id"])):?>
            <li><a href="./index.php?view=clienthome"><i class='fa fa-dashboard'></i> <span>Dashboard</span></a></li>
            <li><a href="./?view=cotizations"><i class='fa fa-square-o'></i> <span>Cotizaciones</span></a></li>
            <li class="treeview <?php if(isset($_GET["view"]) && ($_GET["view"]=="sells"||$_GET["view"]=="bydeliver" ||$_GET["view"]=="bycob")){ echo "active"; }?>"   >
              <a href="#"><i class='fa fa-shopping-cart'></i> <span>Mis Compras</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="./?view=sells">Todas</a></li>
                <li><a href="./?view=bydeliver">Por Recibir</a></li>
                <li><a href="./?view=bycob">Por Pagar</a></li>
              </ul>
            </li>
          <?php endif;?>

          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>
    <?php endif;?>

      <!-- Content Wrapper. Contains page content -->
      <?php if(isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])):?>
      <div class="content-wrapper">
        <?php View::load("index");?>
      </div><!-- /.content-wrapper -->

        <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 7.9
        </div>
        <strong>Copyright &copy; 2017 <a href="http://evilnapsis.com/" target="_blank">Evilnapsis</a></strong>
      </footer>
      <?php else:?>
        <?php if(isset($_GET["view"]) && $_GET["view"]=="clientaccess"):?>
<div class="login-box">
      <div class="login-logo">
        <a href="./">INVENTIO<b>MAX</b></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
      <center><h4>Cliente</h4></center>
        <form action="./?action=processloginclient" method="post">
          <div class="form-group has-feedback">
            <input type="text" name="username" required class="form-control" placeholder="Usuario"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" required class="form-control" placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">

            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Acceder</button>
              <a href="./" class="btn btn-default btn-block btn-flat"><i class="fa fa-arrow-left"></i> Regresar</a>
            </div><!-- /.col -->
          </div>
        </form>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->  
        <?php else:?>
<div class="login-box" >
      <div class="login-logo">
        <a href="./">INVENTIO<b>MAX</b></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body" >
      <center><h4>Admin</h4></center>
        <form action="./?action=processlogin" method="post">
          <div class="form-group has-feedback">
            <input type="text" name="username" required class="form-control" placeholder="Usuario"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" required class="form-control" placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">

            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Acceder</button>
              <!--
              <a href="./?view=clientaccess" class="btn btn-default btn-block btn-flat">Acceso al cliente <i class="fa fa-arrow-right"></i> </a>-->
            </div><!-- /.col -->
          </div>
        </form>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->  
      <?php endif;?>
      <?php endif;?>


    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery 2.1.4 -->
    <!-- Bootstrap 3.3.2 JS -->
    <script src="plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="plugins/dist/js/app.min.js" type="text/javascript"></script>

    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $(".datatable").DataTable({
          "language": {
        "sProcessing":    "Procesando...",
        "sLengthMenu":    "Mostrar _MENU_ registros",
        "sZeroRecords":   "No se encontraron resultados",
        "sEmptyTable":    "Ningún dato disponible en esta tabla",
        "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":   "",
        "sSearch":        "Buscar:",
        "sUrl":           "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":    "Último",
            "sNext":    "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
        });
      });
    </script>
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
          Both of these plugins are recommended to enhance the
          user experience. Slimscroll is required when using the
          fixed layout. -->
  </body>
</html>

