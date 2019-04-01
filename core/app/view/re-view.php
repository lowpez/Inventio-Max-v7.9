<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Reabastecer Inventario</h1>
	<p><b>Buscar producto por nombre o por codigo:</b></p>
		<form id="searchp">
		<div class="row">
			<div class="col-md-6">
				<input type="hidden" name="view" value="re">
				<input type="text" name="product" id="product_name" class="form-control">
			</div>
			<div class="col-md-3">
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar</button>
			</div>
		</div>
		</form>
	</div>
	<div class="col-md-12">
<div id="show_search_results"></div>
<script>
$(document).ready(function(){
	$("#searchp").on("submit",function(e){
		e.preventDefault();

    name = $("#product_name").val();
	if(name!=""){
		$.get("./?action=searchproductre",$("#searchp").serialize(),function(data){
			$("#show_search_results").html(data);
		});
		$("#product_name").val("");
    }else{
    	$("#show_search_results").html("");
    }

	});
	});



	</script>

</div>
	<div class="col-md-12">

<div id="cartofre"></div>


</div>
</section>
<script>
$(document).ready(function(){
$.get("./?action=cartofre",null,function(data){
$("#cartofre").html(data);
});
});
</script>