<?php



if(isset($_FILES["name"])){
	$up = new Upload($_FILES["name"]);
	if($up->uploaded){
		$up->Process("./");
		if($up->processed){
if ( $file = fopen( "./" . $up->file_dst_name , "r" ) ) {

$ok = 0;
$error = 0;
$products_array = array();

    while($x=fgets($file,4096)){
    	////////
    	if($_POST["kind"]==1){
    		$data = explode(",", $x);
//            echo count($data);   
    		if(count($data)>=5){
//                echo "?";
    			$ok++;
    			$sql = "insert into product (code,name,price_in,price_out,inventary_min,user_id) value (\"$data[0]\",\"$data[1]\",$data[2],$data[3],$data[4],$_SESSION[user_id])";
    			$xy= Executor::doit($sql);
                $products_array[]= array("id"=>$xy[1],"price_in"=>$data[2],"price_out"=>$data[3],"q"=>$data[5]);


    		}else{
    			$error++;
    		}
    	}

    	else if($_POST["kind"]==2){
    		$data = explode(",", $x);
    		if(count($data)>=6){
    			$ok++;
    			$sql = "insert into person (no,name,lastname,address1,email1,phone1,kind) value (\"$data[0]\",\"$data[1]\",\"$data[2]\",\"$data[3]\",\"$data[4]\",\"$data[5]\",1)";
    			Executor::doit($sql);
    		}else{
    			$error++;
    		}
    	}
    	else if($_POST["kind"]==3){
    		$data = explode(",", $x);
    		if(count($data)>=6){
    			$ok++;
    			$sql = "insert into person (no,name,lastname,address1,email1,phone1,kind) value (\"$data[0]\",\"$data[1]\",\"$data[2]\",\"$data[3]\",\"$data[4]\",\"$data[5]\",2)";
    			Executor::doit($sql);
    		}else{
    			$error++;
    		}
    	}



    }



		}
		unlink("./".$up->file_dst_name);
	}
	
}


}

Core::alert("Importacion $ok Ok, $error Error");

    if(count($products_array)>0){
//        print_r($products_array);
        $total=0;
  foreach($products_array as $pa){ $total+=$pa["price_in"]; }
      $y = new YYData();
      $yy = $y->add();
      $sell = new SellData();
      $sell->ref_id= $yy[1];
      $sell->user_id = $_SESSION["user_id"];
      $sell->invoice_code = "";//$_POST["invoice_code"];
      $sell->p_id = 1;//$_POST["p_id"];
      $sell->d_id = 1;//$_POST["d_id"];
      $sell->f_id = 1;//$_POST["f_id"];
      $sell->total = $total;
      $sell->stock_to_id = StockData::getPrincipal()->id;//$_POST["stock_id"];
      $sell->person_id="NULL";
      $s = $sell->add_re();
    foreach($products_array as $pa){
         $op = new OperationData();
         $op->sell_id = $s[1] ;
         $op->product_id = $pa["id"] ;
         $op->stock_id = StockData::getPrincipal()->id;
         $op->operation_type_id=OperationTypeData::getByName("entrada")->id;
         $op->price_in =$pa["price_in"];
         $op->price_out= $pa["price_out"];
         $op->q= $pa["q"];
        // $op->sell_id="NULL";
        $op->is_oficial=1;
        $op->add();
    }

Core::redir("./?view=onere&id=".$s[1]);
    }

Core::redir("./?view=import");

?>