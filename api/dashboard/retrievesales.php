<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
require('../../includes/sessions.php');
require('../../includes/configdb/Dbconnection.php');
require('../../includes/configorders/Paymentorders.php');
require('../../includes/paymentcontroller/Paymentsales.php');

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD']=="GET"){
    
    $sales= new Paymentsales();
    $result= $sales->getAllSales();
    if($result ){
      echo  json_encode($result);
    }else{
        echo json_encode(["success"=>false,"message"=>'no items available']);
    }

}else{
    die(json_encode("invalid request"));
}