<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Importar Datos</h1>
	<br>

<!-- Button trigger modal -->
<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
  Importar datos
</button>

<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal2">
  Instrucciones
</button>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Importar</h4>
      </div>
      <div class="modal-body">


    <form class="form-horizontal" method="post" id="addproduct" action="index.php?action=import" enctype="multipart/form-data" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Tipo</label>
    <div class="col-md-10">
    <select class="form-control" name="kind" required>
      <option value="">-- SELECCIONE --</option>
      <option value="1">Productos</option>
      <option value="2">Clientes</option>
      <option value="3">Proveedores</option>
    </select>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Archivo (.csv)*</label>
    <div class="col-md-10">
      <input type="file" name="name"  id="name" placeholder="Archivo (.csv)">
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Importar Datos</button>
    </div>
  </div>
</form>


      </div>

    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Instrucciones</h4>
      </div>
      <div class="modal-body">

<p>1. seleccione el tipo de datos que desea importar al sistema (Productos, Clientes, Proveedores, Usuarios)</p>
<p>2. seleccione el archivo (.csv) que contenga los datos en base al formato de cada tipo.</p>
<h4>Productos</h4>
<p>Orden de los datos [Codigo, Nombre del producto, Precio de entrada, Precio de salida, Minima en inventario,Inventario Inicial]</p>
<pre>
COC1,Coca cola,8,11,5,100
PEP1,Pepsi,7,10,10,100
</pre>
<h4>Clientes y proveedores</h4>
<p>Orden de los datos [Rut/Rfc, Nombre, Apellidos, Direccion, Email, Telefono]</p>
<p class="text-muted">Debe tener cuidado de no incluir comas extra(,) en ninguno de los campos</p>
<pre>
RAEA050892,Agustin,Ramos Escalante,Tabasco , evilnapsis@gmail.com, +52 1 914 1183199
RAES161295,Sebastian,Ramos Escalante,Mexico ,,
RAEL250901,Leonardo,Ramos Escalante,Mexico ,,
</pre>

      </div>
    </div>
  </div>
</div>


	</div>
</div>
</section>