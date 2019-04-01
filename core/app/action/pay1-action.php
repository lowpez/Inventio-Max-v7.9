<?php

if(isset($_GET["id"])){
$sell = SellData::getById($_GET["id"]);
$sell->p_id=1;

$sell->update_p();
Core::redir("./?view=topay");
}
?>