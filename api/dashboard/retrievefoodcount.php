<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('../../includes/sessions.php');
require('../../includes/configdb/Dbconnection.php');
require('../../includes/configmenu/Menuitem.php');
require('../../includes/menuitemcontroller/Selectmenuitem.php');

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD']=="GET"){
    $limit=$offset=$search="";
    $foodcount= new Selectmenuitem($limit,$offset,$search);
    $result=$foodcount->totalMenuItemCount();

    if($result){
      echo  json_encode(["success"=>true, "foodcount"=>$result]);
    }else{
        echo json_encode(["success"=>false,"message"=>'no items available']);
    }

}else{
    die(json_encode("invalid request"));
}